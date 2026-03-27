<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id('id_article');

            // Contenu principal
            $table->string('affiche_film')->nullable();
            $table->json('images_montre')->nullable();
            $table->string('mouvement_montre', 100)->nullable();
            $table->boolean('en_vente')->default(false);
            $table->text('lien_vente')->nullable();
            $table->string('taille_montre', 50)->nullable();
            $table->text('description')->nullable();

            // Relations avec noms explicites pour éviter les conflits
            $table->foreignId('id_film')
                ->constrained('films', 'id')
                ->cascadeOnDelete()
                ->comment('FK vers films');

            $table->foreignId('id_montre')
                ->constrained('montres', 'id_montre')
                ->cascadeOnDelete()
                ->comment('FK vers montres');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

