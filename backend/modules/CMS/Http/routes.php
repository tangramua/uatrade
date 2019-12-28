<?php

Route::namespace('Admin')->prefix('/admin')->middleware('jwt.auth')->group(function () {
    Route::prefix('page')->name('page.')->group(function () {

        Route::match(['get', 'post'], 'paginate', 'PageController@paginate')->middleware("permission:page.get");
        Route::resource('', 'PageController', ['parameters' => ['' => 'page']])->only(['index', 'store', 'show', 'update', 'destroy'])->middleware("permission");
    });

    Route::post('file', 'FileManagerController@saveFile');
    Route::get('file/{name}', 'FileManagerController@getFilePath');
    Route::get('files/{relative_path?}', 'FileManagerController@getFilesList');
    Route::delete('file/{name}', 'FileManagerController@deleteFile');
});

Route::namespace('Client')->prefix('/client')->middleware('jwt.auth')->group(function () {

    Route::get('favorites/{object_alias}', 'FavoritesController@getAuthUserFavorites')->middleware("permission:favorites.get");
    Route::post('favorites', 'FavoritesController@setFavoritesForAuthUser')->middleware("permission:favorites.add");
    Route::delete('favorites/{object_alias}/{foreign_key}', 'FavoritesController@deleteFromFavoritesForAuthUser')->middleware("permission:favorites.remove");
});

Route::namespace('Guest')->prefix('guest')->group(function () {
    Route::prefix('page')->name('page.')->group(function () {

        Route::match(['get', 'post'], 'paginate', 'PageController@paginate');
        Route::resource('', 'PageController', ['parameters' => ['' => 'page']])->only(['index', 'show']);
    });
});
