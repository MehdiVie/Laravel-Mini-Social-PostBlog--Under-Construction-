<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    //
    public function createFollow(User $user) {
        //can not follow yourself
        if ($user->id === auth()->user()->id) {
            return back()->with('failure','can not follow yourself');
        }

        //can not follow someone you are already following
        $followExist = Follow::where([['user_id','=',auth()->user()->id],['followed_user','=',$user->id]])->count();

        if ($followExist) {
            return back()->with('failure', 'Your are already following that user!');
        }

        $newFollow = new Follow();
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followed_user = $user->id;
        $newFollow->save();

        return back()->with('success', 'User successfully followed!');
    }

    public function removeFollow(User $user) {
        $followExist = Follow::where([['user_id','=',auth()->user()->id],['followed_user','=',$user->id]])->count();

        if ($followExist) {

            $newFollow = new Follow();
            $newFollow->user_id = auth()->user()->id;
            $newFollow->followed_user = $user->id;
            Follow::where([['user_id','=',auth()->user()->id],['followed_user','=',$user->id]])->delete();
    
            return back()->with('success', 'User successfully Unfollowed!');

        } else {

            return back()->with('failure', 'You must followe user before Unfollow!');
        }

    }
}
