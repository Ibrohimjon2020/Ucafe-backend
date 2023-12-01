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
        Schema::create('order-item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price');
$table->unsignedBigInteger('discount_price');
$table->foreignId('course_id');
$table->boolean('active');
$table->dateTime('active_from');
$table->dateTime('active_till');
$table->dateTime('discount_price_active_till');
$table->foreignId('created_by');
$table->foreignId('updated_by');
$table->foreignId('deleted_by');
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
        Schema::dropIfExists('order-item');
    }
};
