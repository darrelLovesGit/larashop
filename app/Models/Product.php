<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model

{
    use HasFactory;
    protected $fillable = ['name', 'price', 'quantity', 'brand_id', 'category_id', 'user_id'];
    public function histories() {
        return $this->hasMany(History::class);
    }
}
