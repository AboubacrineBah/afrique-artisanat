<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('number', 30)->unique();
            $table->string('status', 20)->default('pending')->index();
            $table->string('payment_method', 30);
            $table->string('payment_status', 20)->default('pending');
            $table->string('payment_reference', 60)->nullable();

            // Montants en FCFA (entiers)
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('shipping_fee')->default(0);
            $table->unsignedInteger('total');

            // Adresse de livraison copiée au moment de la commande (l'historique ne change pas si le client modifie son carnet)
            $table->string('shipping_name', 120);
            $table->string('shipping_phone', 30);
            $table->string('shipping_city', 100);
            $table->string('shipping_address');
            $table->text('notes')->nullable();

            $table->timestamp('placed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
