<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use App\Events\OurEvent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;



class UserController extends Controller
{
    
    public function storeAvatar(Request $request) {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096'
        ]);
        $upload = $request->file('avatar');
        $image = Image::read($upload)
        ->resize(120,120);

        $fileName = auth()->user()->id . '-' . uniqid() . '.' . 'jpg';

        Storage::disk('public')->put('avatars/' . $fileName, $image->encodeByExtension('jpg'));

        $user = auth()->user();
        $oldAvatar=$user->avatar;
        $ff=explode('/', $oldAvatar);
        $user->avatar = $fileName;
        $user->save();

        if (end($ff) != 'fallback-avatar.jpg') {
            Storage::disk('public')->delete('avatars/' . end($ff));
        }

        return redirect('/profile/'.$user->username)->with('success', 'Avatar changed successfully!');
        
    }
    
    public function showAvatarForm() {
        return view('showAvatarForm');
    }

    private function getSharedData($user) {
        $isFollowed = 0;

        if (auth()->check()) {
            $isFollowed = Follow::where([['user_id','=',auth()->user()->id],
            ['followed_user','=', $user->id]])->count();
        }

        View::share('sharedData' , [
            'isFollowed' => $isFollowed ,
            'username' => $user->username , 
            'avatar' => $user->avatar ,
            'postCount' => $user->posts()->count() ,
            'followersCount' => $user->followers->count() ,
            'followingCount' => $user->followingTheseUsers->count() ,
            ] );
    }
    
    public function profilePosts(User $user) {

        $this->getSharedData($user);

        return view('profile-posts', [
            'posts' => $user->posts()->latest()->get()
            ]);

    }

    public function profilePostsRaw(User $user) {

        return response()->json(['theHTML'=>view('profile-posts-only' , ['posts' => $user->posts()->latest()->get()])->render(), 
        'docTitle' => $user->username."'s Posts"]);
        
    }

    public function profileFollowers(User $user) {
        
        
        $this->getSharedData($user);

        return view('profile-followers', [
            'followers' => $user->followers()->latest()->get()
            ]);

    }

    public function profileFollowersRaw(User $user) {

        return response()->json(['theHTML'=>view('profile-followers-only' , ['followers' => $user->followers()->latest()->get()])->render(), 
        'docTitle' => $user->username."'s Followers" ]);
        
    }

    public function profileFollowing(User $user) {
        
        
        $this->getSharedData($user);

        return view('profile-following', [
            'followings' => $user->followingTheseUsers()->latest()->get()
            ]);

    }

    public function profileFollowingRaw(User $user) {
        
        return response()->json(['theHTML'=>view('profile-following-only' , ['followings' => $user->followingTheseUsers()->latest()->get()])
        ->render(), 'docTitle' => "Who ".$user->username." Follows" ]);

    }


    public function logout() {
        //event(new OurEvent(['username'=>auth()->user()->username , 'action'=>'logout']));
        auth()->logout();
        return redirect('/')->with('success', 'You are not logged out!');
        
    }
    
    public function showCorrectHomePage() {
        if (auth()->check()) {
            return view('homepage-feed' , 
            ['posts' => auth()->user()->feedPosts()->latest()->paginate(4)]
            );
        } else {
            $postCount = Cache::remember('postCount' , 20 , function() {
                sleep(5);
                return Post::count();
            });
            return view('homepage' , ['postCount' => $postCount]);
        }
    }

    public function loginApi(Request $request) {
        $incomingFields = $request->validate([
            'username' => 'required' , 
            'password' => 'required'
        ]);

        if (auth()->attempt($incomingFields)) {
            $user = User::where('username' , 
            $incomingFields['username'])->first();
            $token = $user->createToken('ourapptoken')->plainTextToken;
            return $token;
        }
        return "Wrong";
    }
    
    //
    public function login(Request $request) {
        $incomingFields= $request->validate([
            'loginusername' => ['required'] , 
            'loginpassword' => ['required']
        ]);

        if (auth()->attempt ([
            'username' => $incomingFields['loginusername'] ,
            'password' => $incomingFields['loginpassword'] ,
            ])) {
            $request->session()->regenerate();
            //event(new OurEvent(['username'=>auth()->user()->username , 'action'=>'login']));
            return redirect('/')->with(
                'success' , 'You have successfully logged in');
        } else {
            return redirect('/')->with(
                'failure' , 'Invalid login!');

        }
    }

    public function register(Request $request) {
        $incomingFields= $request->validate([
            'username' => ['required' , 'min:3' , 'max:30' , 
                Rule::unique('users' , 'username')] , 
            'email' => ['required' , 'email' , 
                Rule::unique('users' , 'email')] ,
            'password' => ['required' , 'min:8' , 'confirmed']
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);

        $user = User::create($incomingFields);
        Auth::login($user);
        return redirect('/')->with('success', 'Thanks for Register!');
    }


}
