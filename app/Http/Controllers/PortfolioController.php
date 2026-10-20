<?php

namespace App\Http\Controllers;

use App\Http\Requests\PortfolioRequest;
use App\Models\Portfolio;
use App\Services\SupabaseStorage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class PortfolioController extends Controller
{
    public function create()
    {
        return view('portfolios.form', ['portfolio' => null, 'form' => $this->formData(null)]);
    }

    // Saves a NEW portfolio that belongs to the signed-in user
    public function store(PortfolioRequest $request, SupabaseStorage $storage)
    {
        $data = $request->validated();
        $imageUrl = null;

        try {
            if ($request->hasFile('image')) {
                $imageUrl = $storage->upload($request->file('image'));
            }

            $portfolio = DB::transaction(function () use ($data, $imageUrl) {
                // Through the relationship, so user_id is always the logged-in user
                $portfolio = auth()->user()->portfolios()->create($this->mainFields($data) + [
                    'profile_image' => $imageUrl,
                    'template' => 'simple',
                ]);
                $this->saveChildren($portfolio, $data);

                return $portfolio;
            });
        } catch (Throwable $e) {
            report($e);
            $storage->delete($imageUrl);

            return back()->withInput()->withErrors(['save' => 'Your portfolio could not be saved. Please try again.']);
        }

        return redirect('/templates?id='.$portfolio->id);
    }

    public function edit(Portfolio $portfolio)
    {
        Gate::authorize('update', $portfolio); // 403 if it is not yours

        $portfolio->load('education', 'skills', 'projects', 'experiences', 'socialLinks');

        return view('portfolios.form', ['portfolio' => $portfolio, 'form' => $this->formData($portfolio)]);
    }

    public function update(PortfolioRequest $request, Portfolio $portfolio, SupabaseStorage $storage)
    {
        Gate::authorize('update', $portfolio); // 403 if it is not yours

        $data = $request->validated();
        $oldImage = $portfolio->profile_image;
        $newImage = $oldImage;
        $uploaded = null;

        try {
            if ($request->hasFile('image')) {
                $uploaded = $newImage = $storage->upload($request->file('image'));
            } elseif ($request->boolean('remove_image')) {
                $newImage = null;
            }

            DB::transaction(function () use ($portfolio, $data, $newImage) {
                $portfolio->update($this->mainFields($data) + ['profile_image' => $newImage]);
                $this->saveChildren($portfolio, $data, replace: true);
            });
        } catch (Throwable $e) {
            report($e);
            $storage->delete($uploaded);

            return back()->withInput()->withErrors(['save' => 'Your changes could not be saved. Please try again.']);
        }

        if ($oldImage && $newImage !== $oldImage) {
            $storage->delete($oldImage);
        }

        return redirect('/portfolio/'.$portfolio->id.'?notice=saved');
    }

    private function mainFields(array $data): array
    {
        return [
            'full_name'      => $data['full_name'],
            'email'          => $data['email'],
            'contact_number' => $data['contact_number'] ?? null,
            'address'        => $data['address'] ?? null,
            'about_me'       => $data['about_me'] ?? null,
        ];
    }

    private function saveChildren(Portfolio $portfolio, array $data, bool $replace = false): void
    {
        if ($replace) {
            $portfolio->education()->delete();
            $portfolio->skills()->delete();
            $portfolio->projects()->delete();
            $portfolio->experiences()->delete();
            $portfolio->socialLinks()->delete();
        }

        $portfolio->education()->createMany($data['education'] ?? []);
        $portfolio->skills()->createMany(
            collect($data['skills'] ?? [])->map(fn ($name) => ['skill_name' => $name])->all()
        );
        $portfolio->projects()->createMany($data['projects'] ?? []);
        $portfolio->experiences()->createMany($data['experiences'] ?? []);
        $portfolio->socialLinks()->createMany($data['social_links'] ?? []);
    }

    private function formData(?Portfolio $p): array
    {
        $rows = fn ($collection, array $keys) => ($collection && $collection->isNotEmpty())
            ? $collection->map(fn ($row) => $row->only($keys))->values()->all()
            : [array_fill_keys($keys, '')];

        return [
            'education'    => $rows($p?->education, ['school', 'degree', 'year', 'description']),
            'skills'       => $p ? $p->skills->pluck('skill_name')->values()->all() : [],
            'projects'     => $rows($p?->projects, ['project_name', 'description', 'technologies', 'project_link']),
            'experiences'  => $rows($p?->experiences, ['job_title', 'company', 'start_date', 'end_date', 'description']),
            'social_links' => $rows($p?->socialLinks, ['platform', 'url']),
        ];
    }
}