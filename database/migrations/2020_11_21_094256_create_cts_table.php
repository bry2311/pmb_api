<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cts', function (Blueprint $table) {
            $table->id();
            $table->string("name",255);
            $table->longText("description");
            $table->date("date");
            $table->dateTime("start",0);
            $table->dateTime("end",0);
            $table->integer("duration");
            $table->bigInteger('years_id')->unsigned();
            $table->foreign('years_id')->references('id')->on('years')->onDelete('cascade');
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
        Schema::dropIfExists('cts');
    }
}
