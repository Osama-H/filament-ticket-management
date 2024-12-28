<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    \App\Models\User::find(1)->hasPermssion('permission_create');
    return view('welcome');
});
