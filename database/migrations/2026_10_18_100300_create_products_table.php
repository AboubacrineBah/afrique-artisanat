<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('artisan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->text('description');
            $table->unsignedInteger('price');            // en FCFA, sans décimales
            $table->unsignedInteger('stock')->default(0);
            $table->string('material', 100)->nullable();
            $table->string('origin', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();                       // conserve l'historique des commandes

            $table->index(['is_active', 'is_featured']);
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
