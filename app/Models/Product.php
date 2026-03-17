<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image'];

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : asset('images/default-product.png');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
