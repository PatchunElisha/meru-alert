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
        Schema::create('search_result_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('search_lists_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->integer('price');
            $table->string('url')->unique();
            $table->string('image_url')->unique();
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
        Schema::dropIfExists('search_result_stocks');
    }
};
