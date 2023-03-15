<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsResource;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *      path="/products",
     *      operationId="getProducts",
     *      tags={"Products"},
     *      summary="Получение списка продуктов для добавления в заказ",
     *      description="Метод возвращает данные ...",

     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/Products")
     *       ),
     *     )
     */


   public function getProducts(){
       /**
        * * @OA\Get(
        *      path="/products",
        *      operationId="getProducts",
        *      tags={"Products"},
        *      summary="Получить список всех доступных товаров",
        *      description="Получаем список всех доступных товаров",
        *     @OA\Response(
        *         response=200,
        *          description="successful operation",
        *          @OA\JsonContent(ref="/products")
        *       ),
        *      @OA\Response(
        *         response=400,-n
        *         description="Invalid ID supplied"
        *      )
        *     )
        */
       return ProductsResource::collection(Products::paginate(20));
   }
}
