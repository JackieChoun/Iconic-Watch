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
        Schema::create('articles', function (Blueprint $table) {
            $table->id('id_article');
            
            // Contenu principal
            $table->string('affiche_film')->nullable(); // grande image en haut
            $table->json('images_montre')->nullable();  // jusqu'à 4 images (stockées en JSON)
            $table->string('mouvement_montre', 100)->nullable();
            $table->boolean('en_vente')->default(false); // dispo oui/non
            $table->string('lien_vente')->nullable();    // lien vers site officiel
            $table->string('taille_montre', 50)->nullable();
            $table->text('description')->nullable();

            // Relations
            $table->foreignId('id_film')->constrained('films')->onDelete('cascade');
            $table->foreignId('id_montre')->constrained('montres', 'id_montre')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
