<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoalPgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soal_pgs', function (Blueprint $table) {
            $table->id();
            $table->integer("number");
            $table->longText("question");
            $table->string("A",255);
            $table->string("B",255);
            $table->string("C",255);
            $table->string("D",255);
            $table->string("E",255);
            $table->string("key",5);
            $table->bigInteger('cts_id')->unsigned();
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
        Schema::dropIfExists('soal_pgs');
    }
}
