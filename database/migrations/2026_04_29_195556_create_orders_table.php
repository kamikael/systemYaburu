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
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            $table->decimal('amount_total', 10, 2);

            $table->string('order_number')->unique();

            $table->string('transaction_id')->nullable();

            $table->string('transaction_status')->default('pending');

            $table->string('payment_method')->nullable();

            $table->string('client_firstname');

            $table->string('client_lastname')->nullable();

            $table->string('client_email')->nullable();

            $table->string('client_phone');

            $table->string('client_contact')->nullable();

            $table->string('client_country')->nullable();

            $table->string('client_district')->nullable();

            $table->string('client_city')->nullable();

            $table->text('client_address')->nullable();

            $table->foreignId('store_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};