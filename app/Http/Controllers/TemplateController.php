<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    // The "choose a template" page: /templates?id=...
    public function index(Request $request)
    {
        $id = $request->query('id');
        $portfolio = (is_string($id) && Str::isUuid($id)) ? Portfolio::find($id) : null;

        return view('templates.index', ['portfolio' => $portfolio]);
    }

    // Save the chosen template (only the three allowed values pass validation)
    public function update(Request $request, Portfolio $portfolio)
    {
        $data = $request->validate([
            'template' => ['required', 'in:'.implode(',', Portfolio::TEMPLATES)],
        ]);

        $portfolio->update(['template' => $data['template']]);

        $notice = $request->input('from') === 'select' ? 'ready' : 'template';

        return redirect('/portfolio/'.$portfolio->id.'?notice='.$notice);
    }
}