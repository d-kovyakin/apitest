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
        if($request->status == null)
            $request->status = 'all';

        if ($request->status != 'all') {
            return OrderResource::collection(Orders::where('status', '=', $request->status)->get()) ;

        } else {
            return OrderResource::collection(Orders::all());
        }
    }

    /**
     * @OA\Get(
     *      path="/order/create?data={data}",
     *      operationId="createOrders",
     *      tags={"Orders"},
     *      summary="Создание, получение списка заказов и их редактирование.",
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
     *      path="/order/{order}/edit?product_id={id}&count={count}&{delete}=true&add={add}",
     *      operationId="editOrdersProducts",
     *      tags={"Orders"},
     *      summary="Редактирование списка продуктов в заказе. ",
     *      description="Метод возвращает результат. Приоритет операции отдается delete!!! В документации указаны методы, использовать
     * отдельно , например /order/{order}/edit?product_id={id}&count={count} или /order/{order}/edit?product_id={id}&{delete}=true  или
     *     /order/{order}/edit?add={add} ",
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
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="count",
     *          description="change count product",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *      ),
     *           @OA\Parameter(
     *          name="delete",
     *          description="delete product - input delete",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *      ),
     *           @OA\Parameter(
     *          name="add",
     *          description="add product - [{''id'':2,''count'':1},{''id'':2,''count'':1}] и так далее, где id = id
     * товара, count =  количество товара. Data отсюда не копировать!",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *      ),
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
        $delete = (bool)$request->delete;

        $add = $request->add;

        if ($request->add && $request->add != null) {
            $add = json_decode($add);
        }

        if ($request->add != null) {
            if ($add != null && count($add) > 0) {
                foreach ($add as $el) {
                    if (isset($el->id) && isset($el->count) && is_int($el->id) && is_int($el->count)) {
                        if (isset(Products::find($el->id)->id)) {
                            Order_product::create(
                                [
                                    'orders_id' => Orders::find($id)->id,
                                    'product_id' => Products::find($el->id)->id,
                                    'count' => $el->count,
                                ]
                            );
                        }
                    }
                }
                if ($delete != true) {
                    return ['success' => 'products was added'];
                }
            } else {
                return ['error' => 'json is not verify'];
            }
        } else {
            $product = Order_product::where('product_id','=',$product_id)->where('orders_id', '=', $id)->first();
            if ($product_id && $product_id != null && is_int($product_id)
                && isset($id) && $product != null) {


                if ($count && is_int($count)) {
                    $product->update(
                        [
                            'count' => $count,
                        ]
                    );

                    if (!isset($request->add) && $request->add == '' && $delete != true) {
                        return ['success' => 'product from orders was changed'];
                    }
                } else {
                    return ['error' => 'count is not corrected'];
                }
            } else {
                return ['error' => 'product_id is not corrected'];
            }

        }

        if ($delete && $delete == 'true' && Order_product::find($product_id)) {
            $product->delete();
            return ['success' => 'product was deleted'];
        } else {
            return ['error' => 'product was not finded'];
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
            return ['error' => 'Status was NOT changed!'];
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
