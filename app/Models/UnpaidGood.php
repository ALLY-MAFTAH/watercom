<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnpaidGood extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'user_id',
        'seller',
        'amount_paid',
        'date',
        'status',

    ];

    protected $dates = [
        'deleted_at'
    ];


    public function unpaidPurchases()
    {
        return  $this->hasMany(UnpaidSale::class);
    }
    public function customer()
    {
        return  $this->belongsTo(Customer::class,'customer_id');
    }
}
