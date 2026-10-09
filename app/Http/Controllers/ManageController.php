<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\SupabaseStorage;
use App\Support\Format;
use Throwable;

class ManageController extends Controller
{
    // The "My Portfolios" page
    public function index()
    {
        $items = [];
        $error = false;

        try {
            $items = Portfolio::orderByDesc('created_at')
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
            report($e); // the real error goes to the log, the user sees a friendly box
            $error = true;
        }

        return view('manage.index', [
            'items'  => $items,
            'error'  => $error,
            'counts' => collect($items)->countBy('template'),
        ]);
    }

    // Delete a portfolio. The database "ON DELETE CASCADE" removes its related rows.
    public function destroy(Portfolio $portfolio, SupabaseStorage $storage)
    {
        $image = $portfolio->profile_image;

        try {
            $portfolio->delete();
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors(['delete' => 'The portfolio could not be deleted. Please try again.']);
        }

        $storage->delete($image); // remove the picture file too

        return redirect('/manage?notice=deleted');
    }
}