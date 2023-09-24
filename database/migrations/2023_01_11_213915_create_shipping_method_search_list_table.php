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
        Schema::create('shipping_method_search_list', function (Blueprint $table) {
            $table->foreignId('id')->constrained('search_lists')->cascadeOnDelete();
            $table->string('shipping_method_name');
            $table->foreign('shipping_method_name')->references('name')->on('shipping_methods');
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
        Schema::dropIfExists('shipping_method_search_list');
    }
};
