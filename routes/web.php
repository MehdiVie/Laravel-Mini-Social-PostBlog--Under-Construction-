<?php

use Pusher\Pusher;
use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

Route::get('/create-post', [PostController::class , "showCreateForm"])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class , "storeNewPost"])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class , "viewSinglePost"]);
Route::delete('/post/{post}', [PostController::class , "delete"])
->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class , "showEditForm"])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class , "updatePost"])
->middleware('can:update,post');
Route::get('/search/{term}' , [PostController::class , "search"] );

Route::get('/profile/{user:username}' , [UserController::class , 'profilePosts']);
Route::get('/profile/{user:username}/followers' , [UserController::class , 'profileFollowers']);
Route::get('/profile/{user:username}/following' , [UserController::class , 'profileFollowing']);

Route::middleware('cache.headers:public;max_age=20;etag')->group(function() {

    Route::get('/profile/{user:username}/raw' , [UserController::class , 'profilePostsRaw']);
    Route::get('/profile/{user:username}/followers/raw' , [UserController::class , 'profileFollowersRaw']);
    Route::get('/profile/{user:username}/following/raw' , [UserController::class , 'profileFollowingRaw']);

});




Route::post('/create-follow/{user:username}', [FollowController::class , 'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class , 'removeFollow'])->middleware('mustBeLoggedIn');

Route::post('/send-chat-message', function (Request $request) {
    try {
        // Debug incoming request
        Log::info('Request data:', $request->all());

        // Validate the input
        $formFields = $request->validate([
            'textvalue' => 'required|string',
        ]);

        if (!trim(strip_tags($formFields['textvalue']))) {
            return response()->json(['error' => 'Message cannot be empty or just whitespace.'], 422);
        }

        // Ensure the user is authenticated
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }

        // Broadcast the message
        broadcast(new ChatMessage([
            'username' => $user->username,
            'textvalue' => trim(strip_tags($formFields['textvalue'])),
            'avatar' => $user->avatar,
        ]))->toOthers();

        return response()->json(['status' => 'Message sent successfully.'], 200);
    } catch (\Throwable $e) {
        // Log the error
        Log::error('Error in /send-chat-message route:', [
            'message' => $e->getMessage(),
            'stack' => $e->getTraceAsString(),
        ]);

        return response()->json(['error' => 'An internal server error occurred.'], 500);
    }
})->middleware("mustBeLoggedIn");

use App\Http\Controllers\ProfileController;

Route::post('/profile/update', [ProfileController::class, 'update']);


/*
Route::get('/test-broadcast', function () {
    broadcast(new ChatMessage([
        'username' => 'Nila',
        'avatar' => 'http://127.0.0.1:8000/storage/avatars/2-67a7fff74e497.jpg',
        'textvalue' => 'Test Message',
    ]))->toOthers();

    return 'Broadcast test successful.';
});



Route::get('/test-pusher', function () {
    try {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true,
            ]
        );

        $pusher->trigger('test-channel', 'test-event', ['message' => 'Hello World!']);
        return 'Pusher connection test successful.';
    } catch (\Throwable $e) {
        Log::error('Pusher connection test failed:', [
            'message' => $e->getMessage(),
            'stack' => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => 'Pusher connection failed. Check logs for details.'], 500);
    }
});



Route::get('/debug-env', function () {
    return response()->json([
        'PUSHER_APP_ID' => env('PUSHER_APP_ID'),
        'PUSHER_APP_KEY' => env('PUSHER_APP_KEY'),
        'PUSHER_APP_SECRET' => env('PUSHER_APP_SECRET'),
        'PUSHER_APP_CLUSTER' => env('PUSHER_APP_CLUSTER'),
    ]);
});

Route::get('/debug-env-details', function () {
    return response()->json([
        'env_loaded' => file_exists(base_path('.env')),
        'app_key' => config('app.key'),
        'broadcast_driver' => config('broadcasting.default'),
        'pusher_app_id' => config('broadcasting.connections.pusher.app_id'),
        'pusher_app_key' => config('broadcasting.connections.pusher.key'),
    ]);
});
Route::get('/test-error', function () {
    throw new Exception('Test Exception');
});

Route::get('/phpinfo', function () {
    phpinfo();
});
*/
