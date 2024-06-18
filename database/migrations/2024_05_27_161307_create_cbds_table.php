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
        Schema::create('cbds', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique(); 
            $table->string('supplier_raw_material_code');
            $table->string('item');
            $table->string('sample_code');                                                                         
            $table->string('remark')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbds');
    }
};
