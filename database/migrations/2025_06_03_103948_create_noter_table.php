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
        Schema::create('noter', function (Blueprint $table) {
            $table->foreignId('id_montre')->constrained('montres', 'id_montre')->onDelete('cascade');
            $table->foreignId('id_article')->constrained('article_films', 'id_article')->onDelete('cascade');
            $table->integer('note_montre');
        
            $table->primary(['id_montre', 'id_article']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noter');
    }
};
