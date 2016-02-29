<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeklyCashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cash_id');
            $table->string('casino');
            $table->string('stakes');
            $table->string('tables');
            $table->string('game');
            $table->string('weekday');
            $table->dateTime('update_time');
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
        chema::drop('weekly_stats');
    }
}
