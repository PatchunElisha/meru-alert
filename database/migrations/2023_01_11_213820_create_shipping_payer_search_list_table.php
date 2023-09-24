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
        Schema::create('shipping_payer_search_list', function (Blueprint $table) {
            $table->foreignId('id')->constrained('search_lists')->cascadeOnDelete();
            $table->foreignId('shipping_payer_id')->constrained('shipping_payers');
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
        Schema::dropIfExists('shipping_payer_search_list');
    }
};
