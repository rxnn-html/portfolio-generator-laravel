<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->query('id');

        // Only YOUR portfolio is found here
        $portfolio = (is_string($id) && ctype_digit($id))
            ? auth()->user()->portfolios()->find($id)
            : null;

        return view('templates.index', ['portfolio' => $portfolio]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        Gate::authorize('update', $portfolio); // 403 if it is not yours

        $data = $request->validate([
            'template' => ['required', 'in:'.implode(',', Portfolio::TEMPLATES)],
        ]);

        $portfolio->update(['template' => $data['template']]);

        $notice = $request->input('from') === 'select' ? 'ready' : 'template';

        return redirect('/portfolio/'.$portfolio->id.'?notice='.$notice);
    }
}