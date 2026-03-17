<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashback extends Model
{
    use HasFactory;

    protected $table = 'cashback';

    protected $fillable = [
        'percentage',
        'type',
        'status',
    ];

    public function getCashbackStatusAttribute(){
       
        return match ($this->status) {
            '0' => 'Inactive',
            '1' => 'Active',
          
            default => 'Unknown',
        };
    }
}
