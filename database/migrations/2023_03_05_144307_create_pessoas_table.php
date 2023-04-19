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
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo',['ALUNO','FAMILIAR','FUNCIONARIO','VISITANTE','PRESTADOR']);
            $table->string('nome');
            $table->string('cpf')->unique();
            $table->string('ra')->nullable();
            $table->string('foto')->nullable();
            $table->string('foto_doc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoas');
    }
};
