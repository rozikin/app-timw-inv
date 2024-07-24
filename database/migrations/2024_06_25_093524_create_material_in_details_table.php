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
        Schema::create('material_in_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_in_id');
            $table->unsignedBigInteger('item_id')->unsigned();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('qty');
            $table->string('purchase_order_id')->nullable();
            $table->string('batch')->nullable();
            $table->string('no_roll')->nullable();
            $table->string('gw')->nullable();
            $table->string('nw')->nullable();
            $table->string('width')->nullable();
            $table->string('gramasi')->nullable();
            $table->string('mo')->nullable();
            $table->string('style')->nullable();
            $table->string('rak_id')->nullable();
            $table->string('remark')->nullable();
            $table->string('satus')->nullable();




            $table->timestamps();

             // Adding foreign key constraints
             $table->foreign('material_in_id')->references('id')->on('material_ins')->onDelete('cascade');
             $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_in_details');
    }
};
