<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/upload-cv', [AdminController::class, 'uploadCV'])->name('upload.cv');
    Route::delete('/delete-cv', [AdminController::class, 'deleteCV'])->name('delete.cv');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects/{project}', [ProjectController::class, 'show'])
    ->name('projects.show');


Route::get('/test', function () {
    return view('home');
});
Route::delete('/admin/project-images/{image}', 
    [ProjectController::class, 'deleteImage']
)->name('admin.images.delete');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';

