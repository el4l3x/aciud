<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiudadanosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciudadanos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->integer('ci')->unique();
            $table->string('institucion')->nullable();
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->enum('parroquia', ['villa de cura', 'augusto mijares', 'magdaleno', 'san francisco de asis', 'valles de tucutunemo'])->default('villa de cura');
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
        Schema::dropIfExists('ciudadanos');
    }
}
