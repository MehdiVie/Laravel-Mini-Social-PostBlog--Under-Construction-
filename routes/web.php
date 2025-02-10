<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;

/*Route::get('/admins-only', function() {
    if (Gate::allows('visitAdminPages')) {
        return 'Only admins should be able too see this page.';
    }
    return 'You can not view this page.'; 
});*/
Route::get('/test-error', function () {
    throw new Exception('Test Exception');
});

Route::get('/phpinfo', function () {
    phpinfo();
});


Route::get('/admins-only', function() {
    return 'Only admins should be able to see this page.';
})->middleware('can:visitAdminPages');

Route::get('/', [UserController::class , "showCorrectHomePage"])->name('login');
Route::post('/register' , [UserController::class , "register"] )
->middleware('guest');
Route::post('/login' , [UserController::class , "login"] )->middleware('guest');
Route::post('/logout' , [UserController::class , "logout"] )
->middleware('mustBeLoggedIn');
Route::get('/change-avatar', [UserController::class , "showAvatarForm"])
->middleware('mustBeLoggedIn');
Route::post('/change-avatar', [UserController::class , "storeAvatar"])
->middleware('mustBeLoggedIn');



Route::post('/create-follow/{user:username}', [FollowController::class , 'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class , 'removeFollow'])->middleware('mustBeLoggedIn');

Route::get('/create-post', [PostController::class , "showCreateForm"])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class , "storeNewPost"])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class , "viewSinglePost"]);
Route::delete('/post/{post}', [PostController::class , "delete"])
->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class , "showEditForm"])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class , "updatePost"])
->middleware('can:update,post');
Route::get('/search/{term}' , [PostController::class , "search"] );

Route::get('/profile/{user}' , [UserController::class , 'profilePosts']);
Route::get('/profile/{user}/followers' , [UserController::class , 'profileFollowers']);
Route::get('/profile/{user}/following' , [UserController::class , 'profileFollowing']);