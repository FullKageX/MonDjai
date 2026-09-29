<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Admin;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $donneesValidees = $request->validate([
        'telephone' => 'required|string',
        'password' => 'required|string',
    ]);

    if (Auth::guard('admin')->attempt(['telephone' => $donneesValidees['telephone'], 'password' => $donneesValidees['password']])) {
        // $admin = Auth::guard('admin')->user();
        $request->session()->regenerate(); 
        return redirect()->intended('admin/dashboard');
    } else {
        return response()->json(['message' => 'Identifiants invalides'], 401);
    }


    }

    public function showLoginForm()
    {
        return view('admin.login');
    }
}
