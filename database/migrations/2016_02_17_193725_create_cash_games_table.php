<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashGames', function (Blueprint $table) {
            $table->increments('id');
            $table->string('casino');
            $table->string('stakes');
            $table->string('tables');
            $table->string('game');
            $table->integer('update');
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
        Schema::drop('cashGames');
    }
}
