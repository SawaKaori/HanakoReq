<?php
/**
 * UploadedFile.php
 * アップロードファイル管理
 * Copyright (C) 2019 J.Kawahara
 * 2019.3.31 J.Kawahara 新規作成
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadedFile extends Model
{
    protected $fillable = [
        'request_id',
        'number',
        'original_name',
        's3_key'
    ];

    public function request_items()
    {
        return $this->belongsTo('App\RequestItem', 'id', 'request_id');
    }
}
