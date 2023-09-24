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
        Schema::create('item_condition_search_list', function (Blueprint $table) {
            $table->foreignId('id')->constrained('search_lists')->cascadeOnDelete();
            $table->foreignId('item_condition_id')->constrained('item_conditions');
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
        Schema::dropIfExists('item_condition_search_list');
    }
};
