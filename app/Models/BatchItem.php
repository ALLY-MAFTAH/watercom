<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'batch_id',
        'name',
        'volume',
        'measure',
        'quantity',
        'unit',
        'category',
        // 'price',
        'type',
        'status',

    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user()
    {
        return  $this->belongsTo(User::class);
    }
    public function customer()
    {
        return  $this->belongsTo(Customer::class);
    }
    public function product()
    {
        return  $this->belongsTo(Product::class, 'product_id');
    }
    public function batch()
    {
        return  $this->belongsTo(Batch::class, 'batch_id');
    }
}
