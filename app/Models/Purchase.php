<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'total_price'];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
