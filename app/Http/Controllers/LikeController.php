<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostLike;
use App\User;
use App\Post;
use App\Notifications\PostLiked;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($postId)
    {
        $likes = PostLike::where('post_id',$postId)
        ->get();
        return count($likes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        if($request->post_id && $request->user_id){
            $post_like = new PostLike;
            $post_like->post_id = $request->post_id;
            $post_like->user_id = $request->user_id;
            if($post_like->save()){
                $post = Post::find($request->post_id);
                $user = User::find($post->user_id);
                $user->notify(new PostLiked($post_like));
                return Response()->json(['message' => 'ok', 'like_id' => $post_like->id]);
            }
        }

        return Response()->json(['message' => 'error']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function isLiked($postId, $userId)
    {
        //
        $like = PostLike::where('post_id', $postId)
         ->where('user_id', $userId)
         ->get();

         return count($like);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
         $deletedLike = PostLike::where('post_id', $request->post_id)
         ->where('user_id', $request->user_id)
         ->delete();
    }
}
