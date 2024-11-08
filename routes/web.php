<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\CarModelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\RentalReturnController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  Route::get('cars/list', [CarController::class, 'getCars'])->name('cars.list');
  Route::get('/cars', [CarController::class, 'index'])->name('cars.index');

  Route::group(['middleware' => ['permission:create cars']], function () {
    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
  });

  Route::group(['middleware' => ['permission:edit cars']], function () {
    Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
  });

  Route::group(['middleware' => ['permission:delete cars']], function () {
    Route::delete('/cars/{id}', [CarController::class, 'destroy'])->name('cars.destroy');
  });

  Route::get('rentals/list', [RentalController::class, 'getRentals'])->name('rentals.list');
  Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');

  Route::group(['middleware' => ['permission:create rentals']], function () {
    Route::get('/rentals/create', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
  });

  Route::group(['middleware' => ['permission:edit rentals']], function () {
    Route::get('/rentals/{rentals}/edit', [RentalController::class, 'edit'])->name('rentals.edit');
    Route::put('/rentals/{rentals}', [RentalController::class, 'update'])->name('rentals.update');
  });

  Route::group(['middleware' => ['permission:delete rentals']], function () {
    Route::delete('/rentals/{id}', [RentalController::class, 'destroy'])->name('rentals.destroy');
  });

  Route::get('/get-models-by-brand/{brand_id}', [CarModelController::class, 'getModelsByBrand'])->name('models.getByBrand');

  Route::get('returns/list', [RentalReturnController::class, 'getReturns'])->name('returns.list');
  Route::get('/returns', [RentalReturnController::class, 'index'])->name('returns.index');

  Route::group(['middleware' => ['permission:create returns']], function () {
    Route::get('/returns/create', [RentalReturnController::class, 'create'])->name('returns.create');
    Route::post('/returns', [RentalReturnController::class, 'store'])->name('returns.store');
  });

  Route::group(['middleware' => ['permission:edit returns']], function () {
    Route::get('/returns/{returns}/edit', [RentalReturnController::class, 'edit'])->name('returns.edit');
    Route::put('/returns/{returns}', [RentalReturnController::class, 'update'])->name('returns.update');
  });
});

require __DIR__ . '/auth.php';
