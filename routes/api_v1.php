<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products', [\App\Http\Controllers\Api\v1\ProductController::class, 'getProducts']);
Route::resource('/order', \App\Http\Controllers\Api\v1\OrderController::class);
Route::get('/order/{id_order}/product/{id_product}/delete',
    [\App\Http\Controllers\Api\V1\EditProductInOrderController::class, 'delete']);
Route::get('/order/{id_order}/product/add',
    [\App\Http\Controllers\Api\V1\EditProductInOrderController::class, 'add']);
