<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/********** Login & Register for User **********/
Route::group(['prefix'=>'advertiser','namespace'=>'API\Advertisers'],function (){

    Route::get('get-all', 'AdvertiserController@index');
    Route::post('add', 'AdvertiserController@store');
    Route::post('update/{id}', 'AdvertiserController@update');
    Route::delete('delete', 'AdvertiserController@destroy');
});


