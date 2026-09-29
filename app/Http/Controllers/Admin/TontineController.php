<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tontine;
use App\Services\TontineCycleService;

class TontineController extends Controller
{
	public function activer(Tontine $tontine)
	{
		if ($tontine->statut !== 'en_attente') {
			return redirect()->back()->with('error', 'Cette tontine ne peut pas être activée.');
		}

		try {
			DB::transaction(function () use ($tontine) {
				$tontine->statut = 'active';
				$tontine->save();

				$service = new TontineCycleService();
				$service->genererCycles($tontine);
			});

			return redirect()->back()->with('success', 'Tontine activée avec succès.');
		} catch (\Throwable $exception) {
			return redirect()->back()->with(
				'error',
				"L'activation de la tontine a échoué. Veuillez réessayer."
			);
		}
	}
}
