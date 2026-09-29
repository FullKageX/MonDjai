<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Tontine;
use App\Models\User;

use Illuminate\Http\Request;

class TontineController extends Controller
{
    public function store(Request $request)
    {
        $donneesValidees = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'montant_cotisation' => 'required|numeric|min:0',
            'frequence' => 'required|in:hebdomadaire,mensuelle,autre',
            'jour_echeance' => 'nullable|integer',
            'ordre_versement' => 'required|in:aleatoire,fixe',
            'date_debut' => 'required|date',
            'intervalle_jours' => 'required_if:frequence,autre|integer|min:1',

        ]);

        $tontine = new Tontine($donneesValidees);
        $tontine->createur_id = $request->user()->id;
        $tontine->save();

        return response()->json(['message' => 'Tontine créée avec succès', 'tontine' => $tontine], 201);

    }

    public function ajouterMembre(Request $request, Tontine $tontine)
    {
        if ($request->user()->id !== $tontine->createur_id) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à ajouter des membres à cette tontine',
            ], 403);
        }

        $donneesValidees = $request->validate([
            'telephone' => 'required|string|exists:users,telephone',
        ]);

        $utilisateur = User::where('telephone', $donneesValidees['telephone'])->first();

        if (Membre::where('tontine_id', $tontine->id)
            ->where('user_id', $utilisateur->id)
            ->exists()) {
            return response()->json([
                'message' => 'Cet utilisateur est déjà membre de cette tontine',
            ], 409);
        }

        $membre = new Membre();
        $membre->user_id = $utilisateur->id;
        $membre->ordre_tour = $tontine->membres()->count() + 1;
        $tontine->membres()->save($membre);

        return response()->json([
            'message' => 'Membre ajouté avec succès',
            'membre' => $membre,
        ], 201);
    }

    public function mesTontines(Request $request)
    {
        $utilisateur = $request->user();
        $tontinesCreees = $utilisateur->tontinesCreees()->get();
        $tontinesMembre = $utilisateur->membreDe()
            ->with('tontine')
            ->get()
            ->pluck('tontine')
            ->filter()
            ->values();

        return response()->json([
            'tontines_creees' => $tontinesCreees,
            'tontines_membre' => $tontinesMembre,
        ], 200);
    }

    public function show(Tontine $tontine, Request $request)
    {
        if ($request->user()->id !== $tontine->createur_id && !$tontine->membres()->where('user_id', $request->user()->id)->exists()) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à voir les détails de cette tontine.',
            ], 403);
        }

            $tontine->load('membres.utilisateur', 'cycles.cotisations');
            return response()->json([
            'tontine' => $tontine,
        ], 200);
    }
}
