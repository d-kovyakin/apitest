<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order_product extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='order_products';
    protected $fillable=[
        'orders_id',
        'product_id',
        'count',
    ];
    protected $hidden=[
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function order(){
        return  $this->belongsTo(Orders::class);
    }
}
