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
        Schema::create('search_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained()->cascadeOnDelete();
            $table->string('keyword');
            $table->string('exclude_keyword')->nullable();
            // $table->foreignId('category_id')->nullable()->constrained('categories');
            // $table->foreignId('brand_id')->nullable()->constrained('brands');
            // $table->string('size_id')->nullable();
            $table->integer('price_min')->nullable();
            $table->integer('price_max')->nullable();
            // $table->foreignId('item_condition_id')->constrained('item_conditions');
            // $table->foreignId('shipping_payer_id')->nullable()->constrained('shipping_payers');
            // $table->foreignId('color_id')->nullable();
            // $table->string('shipping_method_id')->nullable()->constrained('colors');
            // $table->foreign('size_id')->references('id')->on('sizes');
            // $table->foreign('shipping_method_id')->references('id')->on('shipping_methods');
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
        Schema::dropIfExists('search_lists');
    }
};
