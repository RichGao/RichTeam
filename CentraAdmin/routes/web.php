<?php

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

Route::get('/market', 'IndexController@index');
Route::get('/centralized', 'Exchange\CentralizedController@index');
Route::get('/decentralized', 'Exchange\DecentralizedController@index');

//API
Route::get('/gettradedata/{product}', 'ExchangeController@getTradeData');
Route::get('/getpricechartdata/{product}', 'ExchangeController@getPriceChartData');


Route::post('/addorder', 'ExchangeController@addOrder')->name('addorder');
Route::post('/assetdeposit/{product}', 'ExchangeController@assetDeposit')->name('assetdeposit');
Route::post('/assetwithdraw/{product}', 'ExchangeController@assetWithdraw')->name('assetwithdraw');
Route::get('/getassetbalance/{product}', 'ExchangeController@getUserAssetBalance')->name('getassetbalance');
//Route::get('/', function () {
//
//    return view('welcome');
//});

Auth::routes();
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/account', 'User\UserController@index')->name('account');
Route::post('/genqrcode', 'User\UserController@generateqrcode')->name('genqrcode');

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
/*** API Defined area  ***/
Route::get('/geterc20tokens', 'APIController@getERC20Tokens')->name('geterc20tokens');
/*************************/

// Blockchain Route
Route::post('/generate/{coin}', 'BlockchainController@generate')->name('generate');
Route::post('/userwalletinfo/{coin}', 'BlockchainController@getUserWalletInfo')->name('userwalletinfo');

//Exchange Management Routing

Route::get('/showfeemngform', 'ExchangeMngController@showFeeRegForm')->name('showfeemngform');
Route::post('/regfee', 'ExchangeMngController@RegFee')->name('regfee');

Route::get('/admin', 'Admin\AdminController@index')->name('admin');
Route::get('/erc20tokenreg', 'Admin\AdminController@showErc20tokenForm')->name('erc20tokenreg');
Route::post('/regerc20token', 'Admin\AdminController@registerErc20token')->name('regerc20token');
Route::get('/geterc20tokens', 'Admin\AdminController@getErc20Tokens')->name('geterc20tokens');
Route::get('/pairmng', 'Admin\AdminController@pairManagement')->name('pairmng');
Route::post('/pairregister', 'Admin\AdminController@pairRegister')->name('pairregister');
Route::get('/getofferasset/{want_asset}', 'Admin\AdminController@getOfferAsset')->name('getofferasset');