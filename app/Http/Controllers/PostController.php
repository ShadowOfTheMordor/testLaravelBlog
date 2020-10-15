<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function view;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //        $posts=App\Models\Post::all();
//        $posts=Post::all();
//        $posts=\App\Models\Post::all();
//        return view("posts.index",compact("posts"));
//        $posts=\App\Models\Post::all();
        if ($request->search)
        {
            $posts=Post::join("users","author_id","=","users.id")
                        ->where("title","like","%".$request->search."%")
                        ->orWhere("descr","like","%".$request->search."%")
                        ->orWhere("name","like","%".$request->search."%")
                        ->orderBy("posts.created_at","desc")
                        ->get();
            return view("posts.index",compact("posts"));
            
        }
        $posts=Post::join("users", "author_id","=","users.id")
                ->orderBy("posts.created_at","desc")
                ->paginate(4);
        return view("posts.index",compact("posts"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view("posts.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
