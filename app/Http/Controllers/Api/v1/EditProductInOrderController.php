<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order_product;
use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;

class EditProductInOrderController extends Controller {
    /**
     * @OA\Get(
     *      path="/order/{id_order}/product/{id_product}/delete",
     *      operationId="delete Product from Order",
     *      tags={"Orders"},
     *      summary="Удаление продукта из заказа ",
     *      description="Метод возвращает результат",
     *     @OA\Parameter(
     *          name="id_order",
     *          description="id_order",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="id_product",
     *          description="id_product",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/Orders")
     *       ),
     *     )
     */

    public function delete($id_order, $id_product) {
        $product = Order_product::where('product_id', '=', $id_product)->where('orders_id', '=', $id_order)->first();
        if ($product != null) {
            $product->delete();
            return ['success' => 'product was delete from order'];
        } else {
            return ['error' => 'product was not finded'];
        }
    }
    /**
     * @OA\Get(
     *      path="/order/{id_order}/product/add?data={data}",
     *      operationId="add Product from Order",
     *      tags={"Orders"},
     *      summary="добавление продукта в заказ",
     *      description="Метод возвращает результат",
     *     @OA\Parameter(
     *          name="id_order",
     *          description="id_order",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *   @OA\Parameter(
     *          name="data",
     *          description="json",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/Orders")
     *       ),
     *     )
     */

    public function add(OrderRequest $request, $id_order) {
        if(Orders::find($id_order) == null){
            return ['error' => 'this is order was not finded'];
        }

        $add = $request->data;
        if ($request->data && $request->data != null) {
            $add = json_decode($add);
        }
        if ($add != null && count($add) > 0) {
            foreach ($add as $el) {
                if (isset($el->id) && isset($el->count) && is_int($el->id) && is_int($el->count)) {
                    if (isset(Products::find($el->id)->id)) {
                        if (Order_product::where('product_id', '=', $el->id)->first()) {
                            return ['error' => 'this is product was added early'];
                        } else {
                            Order_product::create(
                                [
                                    'orders_id' => Orders::find($id_order)->id,
                                    'product_id' => Products::find($el->id)->id,
                                    'count' => $el->count,
                                ]
                            );
                            $message= ['success' => 'product added to order'];
                        }
                    } else {
                        return ['error' => 'this product was not finded in db'];
                    }

                } else {
                    return ['error' => 'json is not corrected!'];
                }
            }
            return $message;
        } else {
            return ['error' => 'json is not corrected'];
        }
    }
}
