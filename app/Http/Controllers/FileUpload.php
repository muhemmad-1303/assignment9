<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUpload extends Controller
{
    //
    public function upload(Request $request){
        $request->validate([
            'file' => 'required|mimes:csv,',
        ]);
        $csvFile=$request->file('file');
        $csvData = array_map('str_getcsv', file($csvFile->path()));
        return response()->json([
            'data' => $csvData,
            
        ], 200);
    }
}
