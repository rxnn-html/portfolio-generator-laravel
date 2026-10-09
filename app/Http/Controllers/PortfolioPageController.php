<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;

class PortfolioPageController extends Controller
{
    // The preview page: /portfolio/{id}
    public function show(Portfolio $portfolio)
    {
        // Load all related rows in the order they were saved
        $portfolio->load([
            'education'   => fn ($q) => $q->orderBy('id'),
            'skills'      => fn ($q) => $q->orderBy('id'),
            'projects'    => fn ($q) => $q->orderBy('id'),
            'experiences' => fn ($q) => $q->orderBy('id'),
            'socialLinks' => fn ($q) => $q->orderBy('id'),
        ]);

        return view('portfolios.show', ['portfolio' => $portfolio]);
    }
}