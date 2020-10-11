<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;

class PostController extends Controller
{
    public function index()
    {
//        $posts=App\Models\Post::all();
//        $posts=Post::all();
        $posts=\App\Models\Post::all();
        return view("posts.index",compact("posts"));
    }
}
