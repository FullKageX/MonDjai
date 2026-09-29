<?php

        namespace App\Services;
        use App\Models\Cycle;
        use App\Models\Cotisation;
        use App\Models\Tontine;

            class TontineCycleService
            {
                public function genererCycles(Tontine $tontine)
                {
                    $membres = $tontine->membres()->where('statut', 'actif')->orderBy('ordre_tour')->get();  

                    $dateCourante = $tontine->date_debut;


                    foreach ($membres as $index => $membre) {

                            $dateEcheance = match ($tontine->frequence) {
                                'hebdomadaire' => $dateCourante->copy()->addDays(7),
                                'mensuelle' => $dateCourante->copy()->addMonth(),
                                'autre' => $dateCourante->copy()->addDays($tontine->intervalle_jours),
                                default => throw new \InvalidArgumentException("Fréquence de tontine invalide: {$tontine->frequence}"),
                            };

                        $cycle = Cycle::create([

                            'numero_cycle' => $index + 1,

                            'tontine_id' => $tontine->id,

                            'beneficiaire_id' => $membre->id,

                            'date_debut' => $dateCourante,

                            'date_echeance' => $dateEcheance

                        ]);

                        $dateCourante = $dateEcheance;

                        foreach ($membres as $membreCotisant) {  
                            Cotisation::create([
                                'cycle_id' => $cycle->id,
                                'membre_id' => $membreCotisant->id,
                                'montant_attendu' => $tontine->montant_cotisation,
                            ]);

                        }

                    }
                }
            }   