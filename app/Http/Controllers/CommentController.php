<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Comment;
use App\User;
class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postId = $_GET['post'];
        $comments = Comment::where('post_id',$postId)
        ->latest()
        ->paginate(3);
        //$comments = $comments->reverse();
        $commentsData = [];
        foreach($comments as $comment){
            $user = User::find($comment->user_id);
            $username = $user->username;
            $avatar = $user->avatar;
            array_push($commentsData,[
                "username" => $username,
                "avatar" => $avatar,
                "comment_id" => $comment->id,
                "message" => $comment->message,
                "date" => $comment->created_at->toDateTimeString()
            ]);
        }
        //$collection = collect($commentsData);
        // return $collection->sortBy('date');

        $response = [
            'pagination' => [
                'total' => $comments->total(),
                'per_page' => $comments->perPage(),
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'from' => $comments->firstItem(),
                'to' => $comments->lastItem()
            ],
            'data' => $commentsData
        ];
        return response()->json($response);
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
        if($request->post_id && $request->user_id){
            $comment = new Comment;
            $comment->message = $request->message;
            $comment->post_id = $request->post_id;
            $comment->user_id = $request->user_id;
            $comment->status = 1;
            if($comment->save()){
                $user = User::find($request->user_id);
                $username = $user->username;
                $avatar = $user->avatar;
                $data = array(
                    "username" => $username,
                    "avatar" => $avatar,
                    "comment_id" => $comment->id,
                    "message" => $comment->message,
                    "date" => $comment->created_at->toDateTimeString()
                );
                return Response()->json(['message' => 'ok', 'data' => $data]);
            }else{
                return Response()->json(['message' => 'error']);
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
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
