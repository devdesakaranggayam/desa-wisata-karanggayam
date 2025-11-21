<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    public function index() 
    {
        $configs = Config::all();
        return view('dashboard.config.index', compact('configs'));
    }

    public function update(Request $request)
    {
        $config = Config::findOrFail($request->id);
        $config->value = $request->value;
        $config->save();

        return back()->with('success', 'Config updated');
    }

}
