<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_votes', function (Blueprint $table) {
            $table->unsignedInteger('comment_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('vote_type_id');

            $table->primary(['comment_id', 'user_id']);
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vote_type_id')->references('id')->on('vote_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments_votes');
    }
};
