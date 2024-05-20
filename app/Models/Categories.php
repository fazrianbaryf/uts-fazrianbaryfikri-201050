<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

        //set kolom apa saja yang bisa dilakukan insert secara langsung
        protected $table = 'categories';
        protected $fillable = ['name'];
        protected $dates = ['deleted_at'];
}