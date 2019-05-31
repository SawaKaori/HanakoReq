<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentsenderCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comment', function (Blueprint $table) {
            $table->string('comment');  //カラム追加
            $table->string('sender');  //カラム追加
            $table->string('request_id');  //カラム追
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comment', function (Blueprint $table) {
            $table->dropColumn('comment');  //カラム追加
            $table->dropColumn('sender');  //カラム追加
            $table->dropColumn('request_id');  //カラム追加
        });
    }
}
