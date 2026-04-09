<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManualController extends Controller
{
    //


public function store(Request $request)
{
    // juste test pour commencer
    return back()->with('success', 'Ajout réussi');
}
}
