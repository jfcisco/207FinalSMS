<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    public function uploadFile(Request $request) {
        $validator = Validator::make($request->all(),[
            'file' => 'required|max:5048',
        ]);

        if($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $filePath = $request->file('file')->store('files', 'public');

        return response(['data' => $filePath], 200);
    }
}
