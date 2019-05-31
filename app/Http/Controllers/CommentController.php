<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Comment;

class CommentController extends Controller
{

   
    public function remove(Request $request)
    {
        $id = intval($request->get('id', 0));
        $record = Comment::find($id);
        if ($record != null) {
            $record->delete();
        }
        return response()->json(['result' => true]);
    }


    public function store(Request $request)
    {
        $record = null;
        $id = intval($request->get('id', 0));
        $record = Comment::find($id);
        if ($record == null) {
            $record = new Comment();
        }
        $record->fill($request->all());

        $status = $record->save();
        $result = [
            'result' => $status,
            'record' => $record,
        ];
        return response()->json($result);
    }

}
