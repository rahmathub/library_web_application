<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    // Komponen bagian Create Catalog jika menggunakan Catalog::create($request->all());
    // di file CatalogController.php
    protected $fillable = ['name', 'email', 'phone_number', 'address'];

    public function books() {
        return $this->hasMany('App\Models\Book', 'publisher_id');
    }
}
