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
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            // 🔥 STORE
            $table->foreignId('store_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔥 TYPE PRODUIT
            $table->foreignId('product_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->text('description')->nullable();

            $table->text('purchase_instructions')->nullable();

            $table->decimal('price', 10, 2);

            $table->integer('quantity')->default(0);

            $table->integer('min_quantity')->default(1);

            $table->integer('max_quantity')->nullable();

            $table->decimal('price_promo', 10, 2)->nullable();

            $table->timestamp('start_promo')->nullable();

            $table->timestamp('end_promo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};