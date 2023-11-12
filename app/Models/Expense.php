<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'amount',
        'date',
        'status',
        'user_id',

    ];

    protected $dates = [
        'deleted_at'
    ];


    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id');
    }
}
