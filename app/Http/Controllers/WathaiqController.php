<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bayan;
class WathaiqController extends Controller
{
public function index()
{
    $bayans = Bayan::withCount('dossiers')->orderBy('group_index')->get();
    return view('wataiq.montaja', compact('bayans')); // ← garder montaja
}
}
