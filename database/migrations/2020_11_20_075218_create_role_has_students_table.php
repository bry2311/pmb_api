<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleHasStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_has_students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('years_id')->unsigned();
            $table->foreign('years_id')->references('id')->on('years')->onDelete('cascade');
            $table->bigInteger('roles_id')->unsigned();
            $table->foreign('roles_id')->references('id')->on('roles')->onDelete('cascade');
            $table->bigInteger('students_id')->unsigned();
            $table->foreign('students_id')->references('id')->on('students')->onDelete('cascade');
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
        Schema::dropIfExists('role_has_students');
    }
}
