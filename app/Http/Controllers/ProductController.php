<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data=Product::where('user_id',auth()->user()->id)->get();
        return response()->json(['data'=>$data]);




    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv',
        ]);
        $uploadedFile = $request->file('file');
        $csvData = array_map('str_getcsv', file($uploadedFile->path()));
        array_shift($csvData);
        foreach ($csvData as $row) {
            $product = Product::where('sku', $row[1])->first();
            if ($product&&$product->user_id==auth()->user()->id) {
                $product->update([
                    'name' => $row[0],
                    'price' => floatval($row[2]),
                    'description' => $row[3],
                ]);
            } else {
                Product::create([
                    'user_id' => auth()->user()->id, 
                    'name' => $row[0],
                    'sku' => $row[1],
                    'price' => floatval($row[2]),
                    'description' => $row[3],
                ]);
            }
        }
        return response()->json(['message'=>"file uploaded"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
