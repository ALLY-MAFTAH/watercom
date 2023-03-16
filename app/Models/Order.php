<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'number',
        'served_date',
        'status',
        'created_by',
        'user_id',

    ];

    protected $dates = [
        'deleted_at'
    ];

    public function items()
    {
        return  $this->hasMany(Item::class);
    }
    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id');
    }
}
