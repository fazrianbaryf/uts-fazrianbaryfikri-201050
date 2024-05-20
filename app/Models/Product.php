<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
     //set kolom apa saja yang bisa dilakukan insert secara langsung
     protected $table = 'products';
     protected $fillable = ['name', 'description', 'price', 'image', 'category_id', 'expired_at', 'modified_by'];
     protected $dates = ['deleted_at'];

     public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}