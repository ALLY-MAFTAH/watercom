<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'stock_id',
        'name',
        'quantity',
        'volume',
        'measure',
        'unit',
        'order_id'

    ];

    protected $dates = [
        'deleted_at'
    ];

    public function order()
    {
        return  $this->belongsTo(Order::class,'order_id');
    }
}
