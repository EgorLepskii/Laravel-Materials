<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TagManageController;
use App\Http\Controllers\TagPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
\Illuminate\Support\Facades\Lang::setLocale('ru');
Route::get(
    '/', function () {
    return redirect(\route('material.index'));
}
);


Route::group(
    ['prefix' => 'tag'], function () {
    Route::post('/store', [TagController::class, 'store'])->name('tag.store');
    Route::delete('/destroy/{tag}', [TagController::class, 'destroy'])->name('tag.destroy');
    Route::get('/create', [TagController::class, 'create'])->name('tag.create');
    Route::get('/{page?}', [TagController::class, 'index'])->name('tag.index');
}
);

Route::group(
    ['prefix' => 'tagPage'], function () {

}
);

Route::group(
    ['prefix' => 'category'], function () {
    Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
    Route::delete('/destroy/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
    Route::get('/{page?}', [CategoryController::class, 'index'])->name('category.index');

}
);


Route::group(
    ['prefix' => 'material'], function () {
    Route::get('/create', [MaterialController::class, 'create'])->name('material.create');
    Route::get('/', [MaterialController::class, 'index'])->name('material.index');
    Route::get('/{material}', [MaterialController::class, 'show'])->name('material.show');

    Route::get('/{material}/edit', [MaterialController::class, 'edit'])->name('material.edit');
    Route::post('/', [MaterialController::class, 'store'])->name('material.store');
    Route::post('/{material}/update', [MaterialController::class, 'update'])->name('material.update');
    Route::delete('/destroy/{material}', [MaterialController::class, 'destroy'])->name('material.destroy');
}
);

Route::group(
    ['prefix' => 'tagManage'], function () {
    Route::post('/', [TagManageController::class, 'store'])->name('tagManage.store');
    Route::delete('/destroy/', [TagManageController::class, 'destroy'])->name('tagManage.destroy');
}
);

Route::group(
    ['prefix' => 'link'], function () {

    Route::post('/', [LinkController::class, 'store'])->name('link.store');
    Route::post('/{link}/update', [LinkController::class, 'update'])->name('link.update');
    Route::delete('/destroy/{link}', [LinkController::class, 'destroy'])->name('link.destroy');
    Route::get('/{link}/edit', [LinkController::class, 'edit'])->name('link.edit');

}
);


