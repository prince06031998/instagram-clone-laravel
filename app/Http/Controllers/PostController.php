<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Session;
use DB;
use Carbon\Carbon;
use App\Models\User;
use File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::all();
        return view('posts.index', compact('post'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //create post controller
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'status' => 'required',
            'images' => 'required'
        ]);
        $images = array();
        if ($files = $request->file('images')) {
            foreach ($files as $file) {
                //$name = time() . '.' . $file->extension();
                $name = $file->getClientOriginalName();
                $file->move('images', $name);
                $images[] = $name;
            }
        }

        $post = new Post;
        $post->status = $request->status;
        $post->images = $images;
        $post->userId = Session::get('id');
        $post->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $post->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $post->save();

        return redirect()->route('posts.index')->with('mssg', 'created post success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)

    {
        $post = Post::where('id', $id)->first();
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $post = Post::where('id', $id)->first();

        if ($post->userId == Session::get('id')) {
            return view('posts.edit', compact('post'));
        } else {
            return redirect('/')->with('mssg', 'this place is not for you');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required',
            'images' => 'required'
        ]);
        $images = array();
        if ($files = $request->file('images')) {
            foreach ($files as $file) {


                //$name = time() . '.' . $file->extension();
                $name = $file->getClientOriginalName();
                
                $path = public_path('images/'.$name);
                if (File::exists($path)) {
                    unlink($path);
                }
                $file->move('images', $name);
                $images[] = $name;
            }
        }

        Post::where('id', $id)->update([
            'status' => $request->status,
            'images' => $images,
            'userId' => Session::get('id'),
            'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
        ]);

        return redirect()->route('posts.myPost')->with('mssg', 'updated post success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function myPost()
    {
        $posts = Post::where('userId', Session::get('id'))->get();
        return view('posts.myPost', compact('posts'));
    }
}
