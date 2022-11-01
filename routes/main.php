<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['prefix'=>'categories','namespace'=>'API\General'],function (){

    Route::get('get-all', 'CategoryController@index');
    Route::post('add', 'CategoryController@store');
    Route::post('update/{id}', 'CategoryController@update');
    Route::delete('delete', 'CategoryController@destroy');

});

Route::group(['prefix'=>'tags','namespace'=>'API\General'],function (){

    Route::get('get-all', 'TagController@index');
    Route::post('add', 'TagController@store');
    Route::post('update/{id}', 'TagController@update');
    Route::delete('delete', 'TagController@destroy');

});


Route::group(['prefix'=>'ads','namespace'=>'API\Ads'],function (){

    Route::get('get-all', 'AdsController@index');
    Route::get('userAds/{id}', 'AdsController@userAds');
    Route::get('show/{id}', 'AdsController@show');
    Route::post('add', 'AdsController@store');
    Route::post('update/{id}', 'AdsController@update');
    Route::delete('delete', 'AdsController@destroy');

});

Route::group(['prefix'=>'ads','namespace'=>'API\Ads'],function (){

    Route::get('categoryFilter', 'FiltersController@filterByCategory');
    Route::get('tagsFilter', 'FiltersController@filterByTags');
});

Route::group(['prefix'=>'mail','namespace'=>'API\Mail'],function (){

    Route::get('check', 'HelperController@check');

});




