<?php
/**
 * request_items のマイグレーション
 * Copyright (C) 2019 J.Kawahara
 * 2019.3.16 J.Kawahara 新規作成
 */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_type');
            $table->integer('status');
            $table->integer('confirm_status');
            $table->integer('hurry_flag')->nullable();

            $table->string('sender')->nullable();
             $table->string('worker')->nullable();
            
            $table->string('worker')->nullable();
      
            $table->string('worker')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name')->nullable();
            $table->text('senders_comment')->nullable();
            $table->text('workers_comment')->nullable();

            $table->timestamp('registed_dt')->nullable();
            $table->timestamp('limit_dt')->nullable();
            $table->timestamp('complete_dt')->nullable();
            $table->timestamp('confirm_dt')->nullable();

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
        Schema::dropIfExists('request_items');
    }
}
