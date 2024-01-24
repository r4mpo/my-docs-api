<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\AuthController as AuthJWT;
use App\Http\Controllers\Docs\TypesController as Types;
use App\Http\Controllers\Docs\MyDocsController as MyDocs;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [AuthJWT::class, 'login'])->name('login');
    Route::post('logout', [AuthJWT::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('create', [AuthJWT::class, 'create'])->name('register');
});

Route::group(['prefix' => 'docs', 'middleware' => 'auth:api'], function () {
    Route::resource('types', Types::class);
    Route::resource('my-docs', MyDocs::class);
});