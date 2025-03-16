<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description');
            $table->decimal('prix', 10, 2);
            $table->string('image')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('disponible')->default(true);
            $table->foreignId('categorie_id')->constrained('categories');
            $table->timestamps();
            $table->softDeletes(); // Pour archiver au lieu de supprimer
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};