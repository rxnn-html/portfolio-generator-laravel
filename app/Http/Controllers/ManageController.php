<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\SupabaseStorage;
use App\Support\Format;
use Illuminate\Support\Facades\Gate;
use Throwable;

class ManageController extends Controller
{
    // Only the signed-in user's portfolios
    public function index()
    {
        $items = [];
        $error = false;

        try {
            $items = auth()->user()->portfolios()
                ->orderByDesc('created_at')
                ->get(['id', 'full_name', 'email', 'profile_image', 'template', 'created_at'])
                ->map(fn ($p) => [
                    'id'            => $p->id,
                    'full_name'     => $p->full_name,
                    'email'         => $p->email,
                    'profile_image' => $p->profile_image,
                    'template'      => $p->template,
                    'initials'      => Format::initials($p->full_name),
                    'created'       => $p->created_at?->timezone('Asia/Manila')->format('M j, Y'),
                ])
                ->all();
        } catch (Throwable $e) {
            report($e);
            $error = true;
        }

        return view('manage.index', [
            'items'  => $items,
            'error'  => $error,
            'counts' => collect($items)->countBy('template'),
        ]);
    }

    public function destroy(Portfolio $portfolio, SupabaseStorage $storage)
    {
        Gate::authorize('delete', $portfolio); // 403 if it is not yours

        $image = $portfolio->profile_image;

        try {
            $portfolio->delete(); // ON DELETE CASCADE removes the related rows
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors(['delete' => 'The portfolio could not be deleted. Please try again.']);
        }

        $storage->delete($image);

        return redirect('/manage?notice=deleted');
    }
}