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
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->longText('dulux_account_share')->nullable();
            $table->longText('total_annual_potential_sales')->nullable();
            $table->longText('other_main_supplier')->nullable();
            $table->longText('last_6_months_credit_payments')->nullable();
            $table->longText('last_3_largest_commercial_projects')->nullable();
            $table->longText('customers_business_strategy')->nullable();
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
        Schema::dropIfExists('company_profiles');
    }
};
