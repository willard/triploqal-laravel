<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Location;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        //
        // $posts = Post::where('user_id', $user->id)
        // ->orderBy('created_at', 'desc')
        // ->take(10)
        // ->get();
        // $items = array();
        // foreach($posts as $post){
        //     $user = User::find($post->user_id);
        //     $photos = json_decode($post->photos, true);
        //     $location = false;
        //     $post_location = Post::find( $post->id )->location;

        //     if($post_location){
        //         $location_model = Location::find( $post_location->id );
        //         $location_data = json_decode($location_model->data);
        //         $location['name'] = $location_data->name;
        //         $location['country'] = $location_model->country;
        //         $location['city'] = $location_model->country;
        //     }

        //     $items[] = array(
        //         'post_id'   =>  $post->id,
        //         'photos'    =>  $post->photos,
        //         'user_id'   =>  $user->id,
        //         'username'  =>  $user->username,
        //         'caption'   =>  preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="/tag/$1">#$1</a>', $post->caption),
        //         'date'      =>  $post->created_at,
        //         'location'  =>  collect($location)
        //     );
        // }
        return view('user.profile',['user'=>$user]);
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

    public function updateAvatar(Request $request, $id)
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
