<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator; // Panggil validator untuk memvalidasi inputan
use App\Models\Product; // Panggil model Product.php
use Illuminate\Http\Request;

class ProductCT extends Controller
{
    // Menambahkan data ke database
    public function AddProduct(Request $request)
    {
        // Memvalidasi inputan
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'image' => 'nullable|max:255',
            'category_id' => 'required|exists:categories,id',
            'expired_at' => 'required|date',
        ]);

        // Kondisi apabila inputan yang diinginkan tidak sesuai
        if ($validator->fails()) {
            // Response json akan dikirim jika ada inputan yang salah
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();
        $userEmail = $request->attributes->get('user_email');

        // Masukkan inputan yang benar ke database
        Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'image' => $validated['image'],
            'category_id' => $validated['category_id'],
            'expired_at' => $validated['expired_at'],
            'modified_by' => $userEmail, // Mengisi email pengguna yang sedang login
        ]);

        // Response json akan dikirim jika inputan benar
        return response()->json([
            'msg' => 'Data product berhasil disimpan'
        ], 200);
    }

    // Menampilkan semua data produk
    public function ShowProduct() {
        $products = Product::all();

        return response()->json([
            'message' => 'Data products',
            'data' => $products
        ], 200);
    }

    // Memperbarui data produk
    public function UpdateProduct(Request $request, $id) {
        // Memvalidasi inputan
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'image' => 'nullable|max:255',
            'category_id' => 'required|exists:categories,id',
            'expired_at' => 'required|date',
            'modified_by' => 'required|date'
        ]);

        // Kondisi apabila inputan yang diinginkan tidak sesuai
        if($validator->fails()){
            // Response json akan dikirim jika ada inputan yang salah
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();
        $product = Product::find($id);

        if ($product) {
            Product::where('id', $id)->update($validated);

            return response()->json("Data dengan id: {$id} berhasil diupdate", 200);
        }
        return response()->json("Data dengan id: {$id} tidak ditemukan", 404);
    }

    // Menghapus data produk
    public function DeleteProduct($id){
        $product = Product::find($id);

        if($product){
            $product->delete();
            // Response
            return response()->json("Data dengan id: {$id} berhasil dihapus", 200);
        }
        return response()->json("Data dengan id: {$id} tidak ditemukan", 404);
    }
}