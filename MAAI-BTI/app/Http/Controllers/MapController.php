<?php

namespace App\Http\Controllers;

use App\Models\Bangunan;
use App\Models\Petak;
use App\Models\Saluran;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $bangunans = Bangunan::all();
        $salurans = Saluran::all();
        $petaks = Petak::all();

        return view('map.index', compact('bangunans', 'salurans', 'petaks'));
    }
}
