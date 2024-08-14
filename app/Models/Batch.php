<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'user_id',
        'type',
        // 'amount_paid',
        // 'receipt_number',
        'date',

    ];

    protected $dates = [
        'deleted_at'
    ];


    public function batchItems()
    {
        return  $this->hasMany(BatchItem::class);
    }
    public function customer()
    {
        return  $this->belongsTo(Customer::class,'customer_id');
    }
    public function user()
    {
        return  $this->belongsTo(User::class,'user_id');
    }
}
