<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;

class PortfolioPageController extends Controller
{
    // Public: anyone with the link can view. The view hides edit tools unless you own it.
    public function show(Portfolio $portfolio)
    {
        $portfolio->load([
            'education'   => fn ($q) => $q->orderBy('id'),
            'skills'      => fn ($q) => $q->orderBy('id'),
            'projects'    => fn ($q) => $q->orderBy('id'),
            'experiences' => fn ($q) => $q->orderBy('id'),
            'socialLinks' => fn ($q) => $q->orderBy('id'),
        ]);

        $isOwner = auth()->check() && $portfolio->user_id !== null && auth()->id() === $portfolio->user_id;

        return view('portfolios.show', ['portfolio' => $portfolio, 'isOwner' => $isOwner]);
    }
}