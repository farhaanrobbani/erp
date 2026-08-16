<?php

use App\Http\Controllers\Public\CareerController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PostController;
use App\Http\Controllers\Public\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/proyek', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/proyek/{slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/berita', [PostController::class, 'index'])->name('posts.index');
Route::get('/berita/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/karier', [CareerController::class, 'index'])->name('careers.index');
Route::post('/karier/lamaran', [CareerController::class, 'apply'])->name('careers.apply');

Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');
