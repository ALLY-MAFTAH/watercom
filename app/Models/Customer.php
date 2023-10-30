<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'phone',
        'status',

    ];

    protected $dates = [
        'deleted_at'
    ];

    public function goods()
    {
        return  $this->hasMany(Good::class);
    }
    public function unpaidGoods()
    {
        return  $this->hasMany(UnpaidGood::class);
    }
}
