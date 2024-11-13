<?php

namespace App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use hasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'id',
        'name',
        'address',
        'price',
        'currency',
    ];

    protected $casts = [
        'address' => 'array'
    ];

    public $incrementing = false;
    protected $guarded = [];
    protected $keyType = 'string';
}
