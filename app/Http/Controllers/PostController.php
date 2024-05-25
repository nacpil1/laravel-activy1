<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\is_null;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $this->authorize('viewAny', Post::class);
        $posts = Post::where('user_id', Auth::user()->id)->get();
        return view('resources.post.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('resources.post.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {                
        Post::create([
            'user_id' => Auth::user()->id,
            'subject' => $request->subject,
            'post' => $request->post,
            'status' => ($request->status == "on" ? 1 : 0)
        ]);

        return redirect()->route('post.index')->with('message', 'Post Succesfully Saved!');        
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return view('resources.post.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('view', $post);
        return view('resources.post.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {                
        $this->authorize('view', $post);
        $post->update([
            'user_id' => Auth::user()->id,
            'subject' => $request->subject,
            'post' => $request->post,
            'status' => ($request->status == "on" ? 1 : 0)
        ]);

        return redirect()->route('post.index')->with('message', 'Post Succesfully Saved!');        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('view', $post);
        $post->delete();
        return redirect()->route('post.index')->with('message', 'Post Succesfully Deleted!');        
    }

    public function postIndex() {
        $posts = Post::where('status', 1)->get();
        return view('pages.index', ['posts' => $posts]);
    }
}