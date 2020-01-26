<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('theme_id')->nullable();
            $table->string('nickname');
            $table->string('tweet');
            $table->unsignedInteger('vote_count')->default(0);
            $table->unsignedInteger('report_count')->default(0);
            $table->unsignedInteger('show_count')->default(0);
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
            $table->index('vote_count', 'vote_count_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweets');
    }
}
