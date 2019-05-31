<?php
/**
 * RequestItem.php
 * リクエスト依頼のレコード
 * Copyright (C) 2019 J.Kawahara
 * 2019.3.16 J.Kawahara 新規作成
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $fillable = [
        'id',
        'request_type',
        'status',
        'confirm_status',
        'hurry_flag',
        'sender',
        'worker',
        'worker_sub',
        'product_code',
        'product_name',
        'senders_comment',
        'workers_comment',

        'registed_dt',
        'limit_dt',
        'complete_dt',
        'confirm_dt',
    ];

    public function uploaded_files()
    {
        return $this->hasMany('App\UploadedFile', 'request_id');

     /*   return $this->hasManyThrough('App\UploadedFile','App\Comment', 'request_id', 'request_id','id', 'id');
  */
    }
    public function comments()
    {
        return $this->hasMany('App\Comment', 'request_id');
         
    }
}
