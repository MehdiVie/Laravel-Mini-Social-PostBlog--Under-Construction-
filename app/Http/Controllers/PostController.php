<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Mail\NewPostEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendNewPostEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function search($term) {
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
    }
    
    public function updatePost(Post $post , Request $request) {
        
        $incomingFields = $request->validate([
            'title' => 'required' ,
            'body' => 'required' ,
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        
        $post->update($incomingFields);

        return back()->with('success', 'Post updated successfully!');
    }
    
    public function showEditForm(Post $post) {
        return view('edit-post', ['post' => $post]);
    }
    public function delete(Post $post) {
        /*
        if (auth()->user()->cannot('delete', $post)) {
            return 'you can not do that!';
        }*/
        $post->delete();

        return redirect('/profile/'.auth()->user()->username)->with('success' , 'The post deleted successfully!');

    }
    public function deleteApi(Post $post) {
        /*
        if (auth()->user()->cannot('delete', $post)) {
            return 'you can not do that!';
        }*/
        $post->delete();

        return true;

    }
    //
    public function viewSinglePost(Post $post) {
        $post['body'] = strip_tags(Str::markdown($post->body),'<ul><li><ol><p><b><br><strong><em><h3>');
        return view('single-post', ['post' => $post]);
    }
    
    public function showCreateForm() {
        return view('create-post');
    }
    public function storeNewPost(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required' ,
            'body' => 'required'
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = Auth::id();

        $newPost = Post::create($incomingFields);

        dispatch(new SendNewPostEmail(['sendTo'=>auth()->user()->email,'name'=>auth()->user()->username , 'title'=>$newPost->title]));
        

        return redirect("/post/{$newPost->id}")
                ->with('success', 'New Post successfully created!');
    }

    public function storeNewPostApi(Request $request) {
        
        $incomingFields = $request->validate([
            'title' => 'required' ,
            'body' => 'required'
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = Auth::id();

        $newPost = Post::create($incomingFields);

        dispatch(new SendNewPostEmail(['sendTo'=>auth()->user()->email,'name'=>auth()->user()->username , 'title'=>$newPost->title]));

        return $newPost->id;
    }
}
