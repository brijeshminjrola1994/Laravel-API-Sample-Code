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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->Integer('age')->nullable();
            $table->enum('blood_type', ['A', 'B', 'AB', 'O'])->default('A');
            $table->string('postal_code', 50)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('phone_number', 50)->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
