<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'description',
    ];

    public const ADMIN = 'System Admin';
    public const CASHIER = 'System Cashier';

    public function users()
    {
        return  $this->hasMany(User::class);
    }

}
