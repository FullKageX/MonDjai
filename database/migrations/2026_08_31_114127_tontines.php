<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tontines', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('description')->nullable();
            $table->decimal('montant_cotisation', 10, 2);
            $table->enum('frequence', ['hebdomadaire', 'mensuelle', 'autre']);
            $table->unsignedTinyInteger('jour_echeance')->nullable();
            $table->enum('ordre_versement', ['fixe', 'aleatoire']);
            $table->enum('statut', ['en_attente', 'active', 'terminee', 'suspendue'])->default('en_attente');
            $table->date('date_debut')->nullable();
            $table->unsignedSmallInteger('intervalle_jours')->nullable();
            $table->foreignId('createur_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tontines');
    }
};
