<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotisationController extends Controller
{
    public function marquerPaye(Request $request, Cotisation $cotisation)
    {
        if ($cotisation->cycle->tontine->createur_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à modifier cette cotisation.',
            ], 403);
        }

        if ($cotisation->statut_paiement === 'paye') {
            return response()->json([
                'message' => 'Cette cotisation est déjà payée.',
            ], 409);
        }

        $donneesValidees = $request->validate([
            'type' => 'sometimes|in:paiement_manuel,mobile_money,ajustement',
            'reference_externe' => 'nullable|string|max:100',
        ]);

        $resultat = DB::transaction(function () use ($cotisation, $request, $donneesValidees) {
            $cotisation->update([
                'statut_paiement' => 'paye',
                'date_marquage' => now(),
                'marque_par_id' => $request->user()->id,
            ]);

            return Transaction::create([
                'cotisation_id' => $cotisation->id,
                'type' => $donneesValidees['type'] ?? 'paiement_manuel',
                'montant' => $cotisation->montant_attendu,
                'reference_externe' => $donneesValidees['reference_externe'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Cotisation marquée comme payée avec succès.',
            'transaction' => $resultat,
        ], 200);
    }
}
