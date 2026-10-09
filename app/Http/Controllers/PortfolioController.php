<?php

namespace App\Http\Controllers;

use App\Http\Requests\PortfolioRequest;
use App\Models\Portfolio;
use App\Services\SupabaseStorage;
use Illuminate\Support\Facades\DB;
use Throwable;

class PortfolioController extends Controller
{
    // Show the empty form
    public function create()
    {
        return view('portfolios.form', ['portfolio' => null, 'form' => $this->formData(null)]);
    }

    // Save a NEW portfolio
    public function store(PortfolioRequest $request, SupabaseStorage $storage)
    {
        $data = $request->validated(); // only data that passed validation
        $imageUrl = null;

        try {
            if ($request->hasFile('image')) {
                $imageUrl = $storage->upload($request->file('image'));
            }

            // A real database transaction: everything is saved, or nothing is
            $portfolio = DB::transaction(function () use ($data, $imageUrl) {
                $portfolio = Portfolio::create($this->mainFields($data) + [
                    'profile_image' => $imageUrl,
                    'template' => 'simple', // the user picks the real one next
                ]);
                $this->saveChildren($portfolio, $data);

                return $portfolio;
            });
        } catch (Throwable $e) {
            report($e); // details go to storage/logs/laravel.log, not to the user
            $storage->delete($imageUrl); // undo the upload

            return back()->withInput()->withErrors(['save' => 'Your portfolio could not be saved. Please try again.']);
        }

        return redirect('/templates?id='.$portfolio->id);
    }

    // Show the form filled with saved data
    public function edit(Portfolio $portfolio)
    {
        $portfolio->load('education', 'skills', 'projects', 'experiences', 'socialLinks');

        return view('portfolios.form', ['portfolio' => $portfolio, 'form' => $this->formData($portfolio)]);
    }

    // Save changes to an EXISTING portfolio (no duplicate is created)
    public function update(PortfolioRequest $request, Portfolio $portfolio, SupabaseStorage $storage)
    {
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
                // The template is NOT touched here
                $portfolio->update($this->mainFields($data) + ['profile_image' => $newImage]);
                $this->saveChildren($portfolio, $data, replace: true);
            });
        } catch (Throwable $e) {
            report($e);
            $storage->delete($uploaded);

            return back()->withInput()->withErrors(['save' => 'Your changes could not be saved. Please try again.']);
        }

        // Success: remove the OLD picture file if it was replaced or removed
        if ($oldImage && $newImage !== $oldImage) {
            $storage->delete($oldImage);
        }

        return redirect('/portfolio/'.$portfolio->id.'?notice=saved');
    }

    /* ---------------------------------------------------------- helpers */

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

    // Education, skills, projects, experiences, social links
    private function saveChildren(Portfolio $portfolio, array $data, bool $replace = false): void
    {
        if ($replace) {
            // Editing a list is simplest as: delete the old rows, insert the new ones
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

    // Saved data -> plain arrays for the form (one blank row when a list is empty)
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