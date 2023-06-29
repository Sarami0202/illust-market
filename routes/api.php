<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/illust/{id?}', 'App\Http\Controllers\IllustController@index');
Route::get('/illust_search/{name?}/{num?}/{page?}', 'App\Http\Controllers\IllustController@getIllust');
Route::get('/illust_count/{name?}', 'App\Http\Controllers\IllustController@Count');
Route::post('/illust', 'App\Http\Controllers\IllustController@store');
Route::post('/illust/{id?}', 'App\Http\Controllers\IllustController@update');
Route::get('/illust_recommend/{num?}/{date?}', 'App\Http\Controllers\IllustController@recommendIllust');
Route::get('/illust_connect/{category?}/{num?}', 'App\Http\Controllers\IllustController@connectIllust');
Route::delete('/illust/{id?}', 'App\Http\Controllers\IllustController@destroy');
Route::get('/illust_new/{num?}', 'App\Http\Controllers\IllustController@newIllust');
Route::get('/date/{open?}/{close?}', 'App\Http\Controllers\dateController@index');
Route::get('/date_all', 'App\Http\Controllers\dateController@get');

Route::get('/category/{id?}', 'App\Http\Controllers\CategoryController@index');
Route::post('/category', 'App\Http\Controllers\CategoryController@store');
Route::post('/category/{id?}', 'App\Http\Controllers\CategoryController@update');
Route::delete('/category/{id?}', 'App\Http\Controllers\CategoryController@destroy');
Route::get('/illust_category/{id?}', 'App\Http\Controllers\IllustCategoryController@getCategory');
Route::get('/category_illust/{id?}/{num?}/{page?}', 'App\Http\Controllers\IllustCategoryController@getIllust');
Route::get('/category_illust_count/{id?}', 'App\Http\Controllers\IllustCategoryController@Count');
Route::post('/illust_category', 'App\Http\Controllers\IllustCategoryController@store');

Route::get('/illust_tags/{id?}', 'App\Http\Controllers\IllustTagsController@getIllustTags');
Route::get('/tags', 'App\Http\Controllers\IllustTagsController@getTags');
Route::get('/tags_illust/{tag?}/{num?}/{page?}', 'App\Http\Controllers\IllustTagsController@getIllust');
Route::get('/tags_illust_count/{tag?}', 'App\Http\Controllers\IllustTagsController@Count');
Route::post('/illust_tags', 'App\Http\Controllers\IllustTagsController@store');

Route::get('/illust_download/{id?}', 'App\Http\Controllers\DownloadController@download');
Route::post('/download', 'App\Http\Controllers\DownloadController@store');
Route::get('/download/{id?}', 'App\Http\Controllers\DownloadController@index');

Route::post('/favorite', 'App\Http\Controllers\FavoriteController@InsertFavorite');
Route::get('/favorite', 'App\Http\Controllers\FavoriteController@getFavorite');
Route::delete('/favorite/{id?}', 'App\Http\Controllers\FavoriteController@destroy');

Route::post('/aff', 'App\Http\Controllers\AffController@store');
Route::get('/aff_info/{id?}', 'App\Http\Controllers\AffController@index');
Route::get('/level/{id?}', 'App\Http\Controllers\AffController@level');
Route::post('/aff_info/{id?}', 'App\Http\Controllers\AffController@update');
Route::delete('/aff_info/{id?}', 'App\Http\Controllers\AffController@destroy');
Route::get('/random', 'App\Http\Controllers\AffController@random');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
