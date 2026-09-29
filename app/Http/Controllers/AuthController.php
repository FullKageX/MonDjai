<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

    class AuthController extends Controller
    {
        public function login(Request $request)
        {
            $donneesValidees = $request->validate([
                'telephone' => 'required|string',
                'password' => 'required|string',
            ]);

            $user = User::where('telephone', $donneesValidees['telephone'])->first();

            if (!$user || !Hash::check($donneesValidees['password'], $user->password)) {
                return response()->json(['message' => 'Identifiants invalides'], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['message' => 'Connexion réussie', 'token' => $token]);
        }

        public function register(Request $request)
        {
            $donneesValidees = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'telephone' => 'required|string|max:20|unique:users',
                'sexe' => 'required|string|in:homme,femme'
            ]);

            $user = User::create([
                'nom' => $donneesValidees['nom'],
                'prenom' => $donneesValidees['prenom'],
                'email' => $donneesValidees['email'],
                'password' => $donneesValidees['password'],
                'telephone' => $donneesValidees['telephone'],
                'sexe' => $donneesValidees['sexe']
            ]);

            return response()->json(['message' => 'Utilisateur enregistré avec succès', 'user' => $user], 201);
        }

        public function logout(Request $request)
        {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Déconnexion réussie']);
        }


    }
