<?php
/**
 * uploaded_files のマイグレーション
 * Copyright (C) 2019 J.Kawahara
 * 2019.3.31 J.Kawahara 新規作成
 */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadedFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploaded_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('request_id');
            $table->integer('number');
            $table->string('original_name');
            $table->string('s3_key');

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
        Schema::dropIfExists('uploaded_files');
    }
}
