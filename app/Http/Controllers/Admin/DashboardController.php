<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tontine;

class DashboardController extends Controller
{
    public function index()
    {
        $tontinesEnAttente = Tontine::where('statut', 'en_attente')->get();
        return view('admin.dashboard', compact('tontinesEnAttente'));
    }
}
