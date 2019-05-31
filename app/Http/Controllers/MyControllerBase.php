<?php
/**
 * コントローラの基底クラス
 * Copyright (C) 2019 J.Kawahara
 * 2019.3.16 J.Kawahara 新規作成
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyControllerBase extends Controller
{
    protected function error($data = [], $statusCode = 200)
    {
        return response()->json($data, $statusCode);
    }

    protected function error401()
    {
        return $this->error(['error' => 'invalid_credentials'], 401);
    }

    protected function error500()
    {
        return $this->error(['error' => 'internal_server_error'], 500);
    }
}
