<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')
                    ->references('id')
                    ->on('suppliers')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')
                    ->references('id')
                    ->on('staff')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('unit_selling_price');
            $table->unsignedBigInteger('unit_buying_price');
            $table->string('payment_type');
            $table->unsignedBigInteger('quantity');
            $table->boolean('status')->default(1);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
};
