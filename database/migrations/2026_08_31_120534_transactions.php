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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotisation_id')->constrained('cotisations')->onDelete('restrict');
            $table->enum('type', ['paiement_manuel', 'mobile_money', 'ajustement'])->default('paiement_manuel');
            $table->decimal('montant', 10, 2);
            $table->string('reference_externe', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
