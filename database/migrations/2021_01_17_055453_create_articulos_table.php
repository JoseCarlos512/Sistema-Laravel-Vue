<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_categoria')->unsigned(); //Columna sin firmar para unirlo a una fk
            $table->string('codigo', 50)->nullable();
            $table->string('nombre', 100)->unique();
            $table->decimal('precio_venta', 11, 2); // 11 digitos totales y 2 decimales
            $table->integer('stock');
            $table->string('descripcion', 256)->nullable();
            $table->boolean('condicion')->default(1);
            $table->timestamps();

            $table->foreign('id_categoria')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulos');
    }
}
