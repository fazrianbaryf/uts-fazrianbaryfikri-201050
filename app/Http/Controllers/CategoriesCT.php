<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator; //panggil validator untuk memvalidasi inputan
use App\Models\Categories; //panggil model Product.php
use Illuminate\Http\Request;

class CategoriesCT extends Controller
{
    //menambahkan data ke database
    public function AddCategories(Request $request){
        //memvalidasi inputan
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
        ]);
        //kondiri aoaobila inputan tang diinginkan tidak sesuai
        if($validator->fails()){
            //response json akan di kirim jika ada inputan yang salah
            return response()->json( $validator->messages() )->setStatusCode(422);
        }
        
        $validated = $validator->validated();
        //masukan inputan yang benar ke database
        Categories::create([
            'name' => $validated['name'],

        ]);
        //response json akan di kirim jika inputan benar
        return response()->json([
            'msg' => 'Data categories berhasil di simpan'
        ],200);
    }
    
    public function ShowCategories() {
        $categories = Categories::all();

        return response()->json([
            'message' => 'data categories',
            'data' => $categories
        ], 200);
    }

    public function UpdateCategories( Request $request, $id) {
        //memvalidasi inputan
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
        ]);
        //kondiri apabila inputan tang diinginkan tidak sesuai
        if($validator->fails()){
            //response json akan di kirim jika ada inputan yang salah
            return response()->json( $validator->messages() )->setStatusCode(422);
        }

        $validated = $validator->validated();
        $categories = Categories::find( $id );

        if ($categories) {
            Categories::where( 'id', $id)->update($validated);

            return response()->json("Data dengan id: {$id} berhasil di update", 200);
        }
            return response()->json("Data dengan id: {$id} Tidak di temukan", 404);

    }

    public function DeleteCategories($id){
        $categories = Categories::where('id', $id)->get();

        if($categories){
            Categories::where('id', $id)->delete();
            //response
            return response()->json("Data dengan id: {$id} berhasil di deleted", 200);
        }
            return response()->json("Data dengan id: {$id} Tidak di temukan", 404);
    }

}