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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('business_detail_id');
            $table->string('image')->nullable();
            $table->enum('gender', ["male", "female", "other"]);
            $table->date('date_of_birth')->nullable();
            $table->string('cnic_number')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('phone_no_one');
            $table->string('phone_no_two')->nullable();
            $table->string('address_one');
            $table->string('address_two')->nullable();
            $table->enum('account_type', ["Company", "Supplier", "Customer"]);
            $table->date('joining_date')->nullable();
            $table->date('leaving_date')->nullable();
            $table->string('salary_per_month')->nullable();
            $table->string('trn_no')->nullable();
            $table->string('responsible_name')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('user_details');
        Schema::table('user_details', function (Blueprint $table) {
            //
            $table->dropSoftDeletes();
        });
    }
};
