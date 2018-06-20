<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Location;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('status', 1)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
        $items = array();
        foreach($posts as $post){
            $user = User::find($post->user_id);
            $photos = json_decode($post->photos, true);
            $location = false;
            $post_location = Post::find( $post->id )->location;

            if($post_location){
                $location_model = Location::find( $post_location->id );
                $location_data = json_decode($location_model->data);
                $location['name'] = $location_data->name;
                $location['country'] = $location_model->country;
                $location['city'] = $location_model->country;
            }

            $items[] = array(
                'post_id'   =>  $post->id,
                'photos'    =>  $post->photos,
                'user_id'   =>  $user->id,
                'username'  =>  $user->username,
                'caption'   =>  preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="/tag/$1">#$1</a>', $post->caption),
                'date'      =>  $post->created_at,
                'location'  =>  collect($location)
            );
        }
        return view('home',['items'=>$items]);
    }
}
