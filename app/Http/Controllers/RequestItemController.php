<?php
/**
 * RequestItemController.php
 * Copyright (C) 2019 J.Kawahara
 * 2019.3.16 J.Kawahara 新覝作戝
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RequestItem;

class RequestItemController extends MyControllerBase
{
    public function list(Request $request)
    {
        $limitTime = time() - 366 * 24 * 60 * 60;
        $limitDt = date('Y-m-d', $limitTime);

        $query = RequestItem::with('uploaded_files')
            ->with('comments')
            ->where('registed_dt', '>', $limitDt)
            ->orderBy('id', 'desc');

        $recordList = $query->get();

        if ($recordList == null) {
            return $this->error500();
        }

        $result = [];
        $result['result'] = true;
        $result['records'] = $recordList;

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $record = null;
        $id = intval($request->get('id', 0));
        $record = RequestItem::find($id);
        if ($record == null) {
            $record = new RequestItem();
        }
        $record->fill($request->all());

        $status = $record->save();
        $result = [
            'result' => $status,
            'record' => $record,
        ];
        return response()->json($result);
    }

    public function remove(Request $request)
    {
        $id = intval($request->get('id', 0));
        $record = RequestItem::find($id);
        if ($record != null) {
            $record->delete();
        }
        return response()->json(['result' => true]);
    }
}
