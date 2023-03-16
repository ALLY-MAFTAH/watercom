<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'volume',
        'quantity',
        'type',
        'measure',
        'unit',
        'cost',
        'status',

    ];

    protected $dates = [
        'deleted_at'
    ];


    public function product()
    {
        return  $this->hasOne(Product::class);
    }
    public function sales()
    {
        return  $this->hasMany(Sale::class);
    }
    public function orders()
    {
        return  $this->belongsToMany(Order::class);
    }
}
