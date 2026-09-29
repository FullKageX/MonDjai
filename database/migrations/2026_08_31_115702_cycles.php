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
        Schema::create('cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tontine_id')->constrained('tontines')->onDelete('cascade');
            $table->unsignedSmallInteger('numero_cycle');
            $table->foreignId('beneficiaire_id')->constrained('membres')->onDelete('restrict');
            $table->date('date_debut');
            $table->date('date_echeance');
            $table->enum('statut', ['en_cours', 'cloture', 'annule'])->default('en_cours');
            $table->unique(['tontine_id', 'numero_cycle']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycles');
    }
};
