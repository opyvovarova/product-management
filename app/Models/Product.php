<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = false;
    protected $table = 'products';

    protected $fillable = [
        'name',
        'quantity',
        'price'
    ];

}
