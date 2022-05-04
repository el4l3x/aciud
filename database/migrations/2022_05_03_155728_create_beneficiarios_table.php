<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiarios', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['solicitante', 'beneficiario']);
            $table->unsignedBigInteger('solicitud_id');
            $table->unsignedBigInteger('ciudadano_id');
 
            $table->foreign('solicitud_id')->references('id')->on('solicituds'); 
            $table->foreign('ciudadano_id')->references('id')->on('ciudadanos');
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
        Schema::dropIfExists('beneficiarios');
    }
}
