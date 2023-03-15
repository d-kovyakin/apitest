<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @OA\Schema(
 *     title="Orders",
 *     description="Orders model",
 *     @OA\Xml(
 *         name="Orders"
 *     )
 * )
 */
class Orders extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'orders';
    protected $fillable=[
        'client_name',
        'client_phone',
        'client_address',
        'client_wishes',
        'status',
    ];
    protected $hidden=[
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function products(){
       return $this->hasMany(Order_product::class);
    }

}
