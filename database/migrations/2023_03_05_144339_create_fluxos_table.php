<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fluxos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_pessoa')->unsigned();
            $table->foreign('id_pessoas')->references('id')->on('pessoas');
            $table->bigInteger('id_user_resp')->unsigned();
            $table->foreign('id_user_resp')->references('id')->on('users');
            $table->enum('sentido',['ENTRADA','SAIDA'])->default('ENTRADA');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fluxos');
    }
};
