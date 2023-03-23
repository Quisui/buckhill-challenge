<?php

use Carbon\Carbon;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('order_status_id');
            $table->foreign('order_status_id')->references('uuid')->on('categories');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('uuid')->on('users');
            $table->uuid('payment_id');
            $table->foreign('payment_id')->references('uuid')->on('payments');
            $table->jsonb('products')->nullable();
            $table->jsonb('address')->nullable();
            $table->float('deliery_fee', 8, 2)->nullable();
            $table->float('amount', 8, 2);
            $table->timestamps();
            $table->dateTime('shipped_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
