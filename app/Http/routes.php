<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/',
    [
        'uses' => 'MainPageController@index',
        'as'  => 'main'
    ]
);

//Route::prefix('movies')->group(function () {
//    Route::get('/',
//        [
//            'uses' => 'MoviesController@index',
//            'as'  => 'all'
//        ]
//    );
//});

Route::group(['prefix' => 'movies'], function () {
    Route::get('/',
        [
            'uses' => 'MoviesController@index',
            'as'  => 'all'
        ]
    );
    Route::get('/searchByName',
        [
            'uses' => 'MoviesController@searchByName',
            'as'  => 'searchByName'
        ]
    );
    Route::get('/searchByTag',
        [
            'uses' => 'MoviesController@searchByTag',
            'as'  => 'searchByTag'
        ]
    );
    Route::get('/searchByCollection',
        [
            'uses' => 'MoviesController@searchByCollection',
            'as'  => 'searchByCollection'
        ]
    );
    Route::get('/searchByLanguage',
        [
            'uses' => 'MoviesController@searchByLanguage',
            'as'  => 'searchByLanguage'
        ]
    );
});

Route::group(['prefix' => 'shows'], function () {
    Route::get('/',
        [
            'uses' => 'ShowsController@index',
            'as'  => 'all'
        ]
    );
    Route::get('/searchByName',
        [
            'uses' => 'ShowsController@searchByName',
            'as'  => 'searchByName'
        ]
    );
    Route::get('/searchByTag',
        [
            'uses' => 'ShowsController@searchByTag',
            'as'  => 'searchByTag'
        ]
    );
});



//Route::get('/movies',
//    [
//        'uses' => 'MoviesController@index',
//        'as'  => 'all'
//    ]
//);
//
//
//Route::get('/movies/searchByName/{name}',
//    [
//        'uses' => 'MoviesController@index',
//        'as'  => 'all'
//    ]
//);


//Route::get('/shows',
//    [
//        'uses' => 'ShowsController@index',
//        'as'  => 'main'
//    ]
//);

Route::get('/wishing-list',
    [
        'uses' => 'WishingListController@index',
        'as'  => 'main'
    ]
);


Route::get('/setting',[
        'uses' => 'SettingController@index',
        'as'  => 'setting'
    ]
);

Route::get('/movie-details/{id}',
    [
        'uses' => 'MovieDetailsController@index',
        'as'  => 'movieDetails'
    ]
);

Route::get('/show-details/{id}',
    [
        'uses' => 'ShowDetailsController@index',
        'as'  => 'movieDetails'
    ]
);

Route::get('/season/{id}',
    [
        'uses' => 'SeasonController@index',
        'as'  => 'season'
    ]
);

Route::get('api/validate_folder',[
        'uses' => 'Api\SettingApiController@validateFolder',
        'as'  => 'api.setting.validate'
    ]
);

Route::get('api/sync_folders',[
        'uses' => 'Api\SettingApiController@syncFolders',
        'as'  => 'api.setting.sync'
    ]
);

Route::get('api/sync_show',[
        'uses' => 'Api\SettingApiController@syncShow',
        'as'  => 'api.setting.sync.show'
    ]
);

Route::get('api/clear_buff',[
    'uses' => 'Api\SettingApiController@clearBuff',
    'as'  => 'api.setting.clear.buff'
]
);

Route::get('api/add_movie_to_wishing_list',[
        'uses' => 'Api\SettingApiController@addMovieToWishingList',
        'as'  => 'api.setting.add.movie'
    ]
);

Route::get('api/add_show_to_library',[
        'uses' => 'Api\SettingApiController@addShowToLibrary',
        'as'  => 'api.setting.add.movie'
    ]
);

Route::get('api/update_last_watch',[
        'uses' => 'Api\SettingApiController@updateLastWatch',
        'as'  => 'api.setting.update'
    ]
);