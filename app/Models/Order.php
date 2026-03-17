<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'total','discount_percentage','discount_type', 'status', 'address', 'city', 'postal_code', 'phone'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            0 => 'Cancelled',
            1 => 'Confirmed',
          
            default => 'Unknown',
        };
    }

}
