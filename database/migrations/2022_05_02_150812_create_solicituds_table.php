<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicituds', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['peticion', 'reclamo', 'denuncia'])->default('peticion');
            $table->string('codigo');
            $table->text('desarrollo');
            $table->enum('status', ['pendiente', 'en proceso', 'realizado', 'en espera de']);
            $table->unsignedBigInteger('institucion_id')->nullable();
            $table->unsignedBigInteger('organismo_id');
 
            $table->foreign('institucion_id')->references('id')->on('institucions'); 
            $table->foreign('organismo_id')->references('id')->on('organismos');
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
        Schema::dropIfExists('solicituds');
    }
}
