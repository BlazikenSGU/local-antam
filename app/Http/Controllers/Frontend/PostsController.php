<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostsController extends Controller
{
    public function webviewDetail($id)
    {
        $post = Post::findOrFail($id);
        return view('frontend.posts.webviewDetail', ['post' => $post]);
    }
}
