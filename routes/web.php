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

use App\User;

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::group([
    'prefix' =>'/user'
    ], function() {
        Route::get('/', [
            'uses' => 'UserController@index',
            'as' => 'user.index'
        ]);

        Route::get('/posts', [
                'uses' => 'PostController@index',
                'as' => 'user.posts'
        ]);

        Route::get('/post/create', [
                'uses' => 'PostController@create',
                'as' => 'user.post.create'
        ]);

        Route::post('/post/store', [
            'uses' => 'PostController@store',
            'as' => 'user.post.store'
        ]);

        Route::get('/post/edit', [
            'uses' => 'PostController@edit',
            'as' => 'user.post.edit'
        ]);

        Route::post('/post/update/{id}', [
            'uses' => 'PostController@update',
            'as' => 'user.post.update'
        ]);
        Route::post('/post/delete/{id}', [
            'uses' => 'PostController@destroy',
            'as' => 'user.post.delete'
        ]);
    }
);


Route::group([
    'prefix' =>'/comment'
    ],
    function() {
        Route::post('/store', [
            'uses' => 'CommentController@store',
            'as' => 'comment.store'
        ]);
    }
);

Route::get('comments', 'CommentController@index');

Route::group([
    'prefix' =>'/like'
    ],
    function() {
        Route::post('/store', [
            'uses' => 'LikeController@store',
            'as' => 'like.store'
        ]);

        Route::post('/destroy', [
            'uses' => 'LikeController@destroy',
            'as' => 'like.destroy'
        ]);

        Route::get('/is-liked/{postId}/{userId}', [
            'uses' => 'LikeController@isLiked',
            'as' => 'like.isliked'
        ]);
    }
);
Route::get('likes/{postId}', 'LikeController@index');
Route::get('posts', 'PostController@index');

Route::group(array('prefix' => '{username}', 'before' => 'auth'), function()
{
    Route::get('/', 'UserController@show');
});

Route::bind('username', function ($value, $route)
{
    $user = User::where('username', $value)->first();

    if ($user == null) {
        App::abort(404);
    }
    return $user;
});
