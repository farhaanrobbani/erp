<?php

use App\Http\Controllers\Public\ContactController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');
