<?php

/*
 * |--------------------------------------------------------------------------
 * | Application Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register all of the routes for an application.
 * | It's a breeze. Simply tell Laravel the URIs it should respond to
 * | and give it the controller to call when that URI is requested.
 * |
 *
 * Http Method Spoofing :
 * <form action="/foo/bar" method="POST">
 * <input type="hidden" name="_method" value="PUT">
 * <input type="hidden" name="_token" value="{{ csrf_token() }}">
 * </form>
 *
 * $app->get('user/profile', [
 * 'as' => 'profile', 'uses' => 'App\Http\Controllers\UserController@showProfile'
 * ]);
 */
$app->get('/', [
    'as' => 'Home',
    'uses' => 'App\Http\Controllers\Controller@home'
]);
$app->get('/about', [
    'as' => 'About',
    'uses' => 'App\Http\Controllers\Controller@about'
]);

$app->group([
    'prefix' => 'api'
], function ($app) {
    $app->get('stats', [
        'as' => 'ApiStats',
        'uses' => 'App\Http\Controllers\ApiController@stats'
    ]);
});

$app->group([
    'prefix' => 'channel'
], function ($app) {
    $app->get('/list', [
        'as' => 'ChannelList',
        'uses' => 'App\Http\Controllers\ChannelController@channelList'
    ]);
    $app->get('{id:[0-9]+}', [
        'as' => 'ChannelGet',
        'uses' => 'App\Http\Controllers\ChannelController@channelGet'
    ]);
    $app->get('/new', [
        'as' => 'ChannelNew',
        'uses' => 'App\Http\Controllers\ChannelController@channelNew'
    ]);
    $app->get('{id:[0-9]+}/edit', [
        'as' => 'ChannelEdit',
        'uses' => 'App\Http\Controllers\ChannelController@channelEdit'
    ]);
    $app->post('', [
        'as' => 'ChannelSave',
        'uses' => 'App\Http\Controllers\ChannelController@channelSave'
    ]);
    $app->post('{id:[0-9]+}', [
        'as' => 'ChannelUpdate',
        'uses' => 'App\Http\Controllers\ChannelController@channelUpdate'
    ]);
    $app->delete('{id:[0-9]+}', [
        'as' => 'ChannelDelete',
        'uses' => 'App\Http\Controllers\ChannelController@channelDelete'
    ]);
});

/*

$app->group( [ 'prefix' => 'rent' ], 
	function ( Laravel\Lumen\Application $app )
	{
		$app->get( '', ['as'=>'RentNew', 'uses' => 'App\Http\Controllers\RentController@editNew'] );
		$app->get( '{id:[0-9]+}', ['as'=>'RentShow', 'uses' =>'App\Http\Controllers\RentController@show'] );
		$app->get( '{id:[0-9]+}/edit', ['as'=>'RentEdit', 'uses'=>'App\Http\Controllers\RentController@edit'] );
		$app->post( '', ['as'=>'RentSave', 'uses'=>'App\Http\Controllers\RentController@save'] );
		$app->post( '{id:[0-9]+}', ['as'=>'RentUpdate','uses'=>'App\Http\Controllers\RentController@update'] );

	} );

$app->group( [ 'prefix' => 'api' ],
	function ( $app )
	{
		$app->get( 'rentsCount', 'App\Http\Controllers\ApiController@rentsCount' );
		// example: http://prixdesloyers.localhost/api/findRentsInBBox/36.03133177633187/-12.963867187499998/54.367758524068385/33.92578125
		$app->get(
			'rentsFindInBBox/{swLat:[0-9\.\-]+}/{swLng:[0-9\.\-]+}/{neLat:[0-9\.\-]+}/{neLng:[0-9\.\-]+}',
			'App\Http\Controllers\ApiController@rentsFindInBBox' );
		$app->get( 'rentsExport/{format:json|csv|ods}', 'App\Http\Controllers\ApiController@rentsExport' );
	} );

*/
