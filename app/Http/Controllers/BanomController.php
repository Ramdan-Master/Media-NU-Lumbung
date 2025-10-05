<?php

namespace App\Http\Controllers;

use App\Models\Banom;
use Illuminate\Http\Request;

class BanomController extends Controller
{
    public function index()
    {
        $banoms = Banom::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('banom.index', compact('banoms'));
    }

    public function show($slug)
    {
        $banom = Banom::where('slug', $slug)
            ->where('is_active', true)
            ->with('management')
            ->firstOrFail();

        return view('banom.show', compact('banom'));
    }
}
