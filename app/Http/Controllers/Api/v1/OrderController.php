<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;

use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order_product;
use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;


class OrderController extends Controller {

    /**
     * @OA\Get(
     *      path="/order?status={status}",
     *      operationId="getOrders",
     *      tags={"Orders"},
     *      summary="Получение списка заказов ",
     *      description="Метод возвращает данные",
     *     @OA\Parameter(
     *          name="status",
     *          description="status of order, all - all orders, 0 -don't completed order, 1 - working order, 2 - completed order",
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
    public function index(Request $request) {
        if ($request->status == null) {
            $request->status = 'all';
        }

        if ($request->status != 'all') {
            return OrderResource::collection(Orders::where('status', '=', $request->status)->get());

        } else {
            return OrderResource::collection(Orders::all());
        }
    }

    /**
     * @OA\Get(
     *      path="/order/create?data={data}",
     *      operationId="createOrders",
     *      tags={"Orders"},
     *      summary="Создание,заказов.",
     *      description="data = [{''id'':2,''count'':1},{''id'':2,''count'':1}] и так далее, где id = id
     * товара, count =  количество товара. Data отсюда не копировать!",
     *     @OA\Parameter(
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

    public function create(OrderRequest $request) {
        ///order/create?data=[{"id":2,"count":1},{"id":2,"count":1}]
        $request->validate(
            [
                'data' => 'json',
            ]
        );
        $data = json_decode($request->data);


        Orders::create(
            [
                'client_name' => 'name',
                'client_phone' => 'phone',
                'client_address' => 'address',
                'client_wishes' => 'wishes',
                'status' => 0,
            ]
        );
        foreach ($data as $el) {
            if (isset($el->id) && isset($el->count) && is_int($el->id) && is_int($el->count)) {
                if (isset(Products::find($el->id)->id)) {
                    Order_product::create(
                        [
                            'orders_id' => Orders::all()->last()->id,
                            'product_id' => Products::find($el->id)->id,
                            'count' => $el->count,
                        ]
                    );
                } else {
                    return ['error' => 'product was not finded'];
                }
            } else {
                return ['error' => 'Json is not corrected'];
            }
        }
        return ['success' => 'order was created'];
    }


    public function store(Request $request) {
        return redirect('/');
    }

    /**
     * @OA\Get(
     *      path="/order/{order}",
     *      operationId="getOrdersProducts",
     *      tags={"Orders"},
     *      summary="Получение списка продуктов в заказе.",
     *      description="Метод возвращает данные.",
     *     @OA\Parameter(
     *          name="order",
     *          description="id заказа",
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
    public function show(string $id) {
        $products = Orders::find($id);
        if ($products && $products != null) {
            return Orders::find($id)->products;
        } else {
            return ['error', 'is not order'];
        }
    }

    /**
     * @OA\Get(
     *      path="/order/{order}/edit?product_id={id}&count={count}",
     *      operationId="editOrdersProducts",
     *      tags={"Orders"},
     *      summary="Редактирование списка продуктов в заказе. ",
     *      description="Метод возвращает результат.",
     *     @OA\Parameter(
     *          name="order",
     *          description="id заказа",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="id product",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="count",
     *          description="change count product",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/Orders")
     *       ),
     *     )
     */
    public function edit(OrderRequest $request, string $id) {
        $product_id = (int)$request->product_id;
        $count = (int)$request->count;

        $product = Order_product::where('product_id', '=', $product_id)->where('orders_id', '=', $id)->first();
        if ($product_id && $product_id != null && is_int($product_id)
            && isset($id) && $product != null) {


            if ($count && is_int($count)) {
                $product->update(
                    [
                        'count' => $count,
                    ]
                );
                    return ['success' => 'product from orders was changed'];

            } else {
                return ['error' => 'count is not corrected'];
            }
        } else {
            return ['error' => 'order_id or product_id is not corrected'];
        }

    }
    /**
     * @OA\Put(
     *      path="/order/{order}?status={status}",
     *      operationId="change status order",
     *      tags={"Orders"},
     *      summary="Изменение статуса заказа.",
     *      description="Метод возвращает результат.",
     *     @OA\Parameter(
     *          name="order",
     *          description="id заказа",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="status",
     *          description="status  = 0,1 or 2",
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
    public function update(OrderRequest $request, string $id) {
        $order = Orders::find($id);
        if ($order && $order != null) {
            $order->update(
                [
                    'status' => $request->status,
                ]
            );
            return ['success' => 'Status was changed'];
        } else {
            return ['error' => 'Order was finded!'];
        }
    }

    /**
     * @OA\Delete(
     *      path="/order/{order}",
     *      operationId="delete order",
     *      tags={"Orders"},
     *      summary="Удаление заказа.",
     *      description="Метод возвращает результат.",
     *     @OA\Parameter(
     *          name="order",
     *          description="id заказа",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/Orders")
     *       ),
     *     )
     */
    public
    function destroy(
        string $id
    ) {
        $order = Orders::find($id);
        if ($order && $order != null) {
            Orders::find($id)->delete();
            return ['success' => 'order destroy'];
        } else {
            return ['error' => 'Error'];
        }
    }
}
