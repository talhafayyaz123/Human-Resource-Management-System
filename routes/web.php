<?php

use App\Http\Controllers\ExtractTextController;
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

/*Route::get('/test', function () {
    $redis = new \Redis();
    $redis->connect(getenv('LOGGING_IP'), getenv('LOGGING_PORT'));
    $redis->auth('dh_redis_pw');
    $response = $redis->LRANGE('log_queue', 0, -1);
    dd($response);
}); */

/** temporary route to extract text */
Route::get('/extract-text', function () {
    return view('extractText');
});
Route::post('/extract-text', [ExtractTextController::class, 'extract']);
/** */

Route::get('/{any}', function () {
    return view('welcome');
})->where("any", ".*");
