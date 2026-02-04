<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('cedula', 10)->nullable()->unique();
            $table->string('provincia', 100)->nullable();
            $table->string('canton', 100)->nullable();
        });
    }


    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['cedula', 'provincia', 'canton']);
        });
    }

};
