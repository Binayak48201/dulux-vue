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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('trading_as')->nullable();
            $table->string('abn')->nullable();
            $table->string('account_number')->nullable();
            $table->string('street_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('postcode')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->unsignedBigInteger('rep_id')->default(3);
            $table->enum('status', ['suspended', 'blacklisted', 'active', 'pending', 'temp', 'restricted', 'limited'])->default('pending');
            $table->longText('additional_terms_and_conditions')->nullable();
            $table->integer('business_owner_id')->nullable();
            $table->integer('secondary_contact_id')->nullable();
            $table->tinyInteger('is_premium')->default(0);
            $table->date('limited_access_to_date')->nullable();
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
        Schema::dropIfExists('companies');
    }
};
