<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function mb_substr;
use function redirect;
use function view;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use \Auth;


class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth")->except("show","index");
    }
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
//        Log::info(var_dump($request->session()->all()));
        if ($request->search)
        {
            $posts=Post::join("users","author_id","=","users.id")
                        ->where("title","like","%".$request->search."%")
                        ->orWhere("description","like","%".$request->search."%")
                        ->orWhere("name","like","%".$request->search."%")
                        ->orderBy("posts.created_at","desc")
                        ->get();
            return view("posts.index",compact("posts"));
            
        }
 /*       if ($request->ajax()) {
            $posts=Post::join("users", "author_id","=","users.id")
                    ->orderBy("posts.created_at","desc")
                    ->paginate(4);
//            return Response::json(view('posts', compact(posts))->render());
            Log::info(" testing response".response()->json(View::make('posts.index', compact("posts"))->render()));
            return response()->json(view('posts.index', compact("posts"))->render());
        }
  * 
  */
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
//        return redirect()->route("post.index")->withErrors(__("messages.post.index.delete_forbidden"));

        return view("posts.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PostRequest $request)
    {
        $post=new Post();
//        $post->author_id=rand(1,4);//заглушка
        $post->author_id=Auth::user()->id;
        $post->title=$request->title;
        $post->short_title=Str::length($request->title)>30 ? mb_substr($request->title,0,30)."..." : $request->title;
        $post->description=$request->description;
        if($request->file("img"))
        {
            $path=Storage::putFile ("public", $request->file("img"));
            $url=Storage::url($path);//ссылка на картинку
            $post->img=$url;
        }
        $post->save();
        return redirect()->route("post.index")->with("success",__("messages.post.create.create_success"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //достаем пост из бд
/*        $post=Post::join("users","author_id","=","users.id")
                ->where("post_id","=",$id);
 * 
 */
//        $post=Post::find($id);
        $post=Post::join("users","author_id","=","users.id")
                ->find($id);
        if (empty($post))
            return redirect()->route("post.index")->withErrors (__("messages.post.show.post_nonexistant"));
        Session::flash("backUrl",url()->current());
        return view("posts.show",compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $post=Post::join("users","author_id","=","users.id")
                ->find($id);
        if (empty($post))
            return redirect()->route("post.index")->withErrors (__("messages.post.edit.post_nonexistant"));
        if ($post->author_id!=Auth::user()->id&&!Auth::user()->is_admin)
        {
            return redirect()->route("post.index")->withErrors(__("messages.post.edit.edit_forbidden"));
        }
        //можно и без join
        return view("posts.edit",compact("post"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PostRequest $request, $id)
    {
        //зачем-то ищем эту запись
        $post=Post::find($id);
        if (empty($post))
            return redirect()->route("post.index")->withErrors (__("messages.post.update.post_nonexistant"));
        if ($post->author_id!=Auth::user()->id&&!Auth::user()->is_admin)
        {
            return redirect()->route("post.index")->withErrors(__("messages.post.edit.edit_forbidden"));
        }
        $post->title=$request->title;
        $post->short_title=Str::length($request->title)>30 ? Str::substr($request->title,0,30)."..." : $request->title;
        $post->description=$request->description;
        if($request->file("img"))
        {
            $path=Storage::putFile ("public", $request->file("img"));
            $url=Storage::url($path);//ссылка на картинку
            $post->img=$url;
        }
        $post->update();
        return redirect()->route("post.show",["id" => $id])->with("success",__("messages.post.edit.edit_success"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //удаление
        $post=Post::find($id);
        if (empty($post))
            return redirect()->route("post.index")->withErrors (__("messages.post.delete.post_nonexistant"));
        if ($post->author_id!=Auth::user()->id&&!Auth::user()->is_admin)
        {
            return redirect()->route("post.index")->withErrors(__("messages.post.index.delete_forbidden"));
        }
        $post->delete();
        return redirect()->route("post.index")->with("success",__("messages.post.index.delete_success"));
    }
}
