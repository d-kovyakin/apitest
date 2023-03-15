<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     title="Products",
 *     description="Products model",
 *     @OA\Xml(
 *         name="Products"
 *     )
 * )
 */
class Products extends Model
{
    /**
     * @OA\Property(
     *     title="id",
     *     description="id",
     *     format="int64",
     *     example=1
     * )
     * @var bigInteger
     */
    private $id;
     /**
     * @OA\Property(
     *     title="product",
     *     description="product name",
     *     format="string(255)",
     *     example="product"
     * )
     * @var String
     */
     private $product;
    /**
     * @OA\Property(
     *     title="price",
     *     description="product price",
     *     format="double",
     *     example="8.1"
     * )
     * @var Float
     */
    private $price;
    /**
     * @OA\Property(
     *     title="created_at",
     *     description="created_at",
     *     format="timestamp",
     *     example="2023-03-14 14:16:36"
     * )
     * @var Timestamp
     */
    private $created_at;
    /**
     * @OA\Property(
     *     title="updated_at",
     *     description="updated_at",
     *     format="timestamp",
     *     example="2023-03-14 14:16:36"
     * )
     * @var Timestamp
     */
    private $updated_at;


    use HasFactory;
    protected $table='products';
    protected $fillable=[
        'product',
        'price',
    ];
    protected $hidden=[
        'created_at',
        'updated_at'
    ];
}
