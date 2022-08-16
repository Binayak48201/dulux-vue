<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->longText('area_of_specialisation')->nullable();
            $table->integer('number_of_employees')->nullable();
            $table->longText('overview_of_business')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_summaries');
    }
};
