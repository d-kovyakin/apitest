<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products',[\App\Http\Controllers\Api\v1\ProductController::class,'getProducts']);
Route::resource('/order',\App\Http\Controllers\Api\v1\OrderController::class);
