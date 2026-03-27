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
    Schema::create('montres', function (Blueprint $table) {
        $table->id('id_montre');
        $table->string('image_montre', 100);
        $table->string('info_montre', 50);
        $table->foreignId('id_marque')->constrained('marques', 'id_marque')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('montres');
    }
};
