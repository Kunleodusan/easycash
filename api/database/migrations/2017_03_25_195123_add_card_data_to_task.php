<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCardDataToTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->bigInteger('cardno');
            $table->text('card_detail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('cardno');
            $table->dropColumn('card_detail');
        });
    }
}
