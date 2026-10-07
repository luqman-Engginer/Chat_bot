<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('toko', [
    'products' => Product::orderBy('kategori')->get(),
]))->name('home');

Route::view('/chat', 'chat')->name('chat');
