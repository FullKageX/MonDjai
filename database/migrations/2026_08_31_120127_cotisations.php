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
        Schema::create('cotisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('cycles')->onDelete('cascade');
            $table->foreignId('membre_id')->constrained('membres')->onDelete('restrict');
            $table->decimal('montant_attendu', 10, 2);
            $table->enum('statut_paiement', ['en_attente', 'paye', 'retard'])->default('en_attente');
            $table->timestamp('date_marquage')->nullable();
            $table->foreignId('marque_par_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('notes', 255)->nullable();
            $table->timestamps();
            $table->unique(['cycle_id', 'membre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotisations');
    }
};
