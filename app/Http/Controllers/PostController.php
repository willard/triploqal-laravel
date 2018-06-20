<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Image;
use App\User;
use App\Post;
use App\Location;
use App\PostLocation;
use App\Tag;
use App\PostTag;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!empty($_GET['user']) && $user = $_GET['user']){
            $posts = Post::where('user_id', $user)
            ->latest()
            ->paginate(3);
        }else{
            $posts = Post::where('status', 1)
            ->latest()
            ->paginate(3);
        }


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
                'photos'    =>  json_decode($post->photos),
                'user_id'   =>  $user->id,
                'username'  =>  $user->username,
                'caption'   =>  preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="/tag/$1">#$1</a>', $post->caption),
                'date'      =>  $post->created_at,
                'location'  =>  collect($location)
            );
        }
        $response = [
            'pagination' => [
                'total' => $posts->total(),
                'per_page' => $posts->perPage(),
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'from' => $posts->firstItem(),
                'to' => $posts->lastItem()
            ],
            'data' => $items
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
        return view('user.post.create');
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

        $photos = $request->photos;

        $photo_json = array();
        foreach ($photos as $photo) {
            $path = $photo->hashName();
            $image = Image::make($photo);
            $image->resize(null, 1000, function ($constraint) {
                $constraint->aspectRatio();
            });
            if(count($photos) > 1){
                $image->crop(800,800);
            }

            Storage::put('public/'.$path, (string) $image->encode());

            $photo_json[] = array(
                'filename'=>$path,
            );
        }
        $user = Auth::user();
        if ($user)
        {
            $hashtags = array();
            if($request->caption){
                //$caption = preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="/tag/$1">#$1</a>', $request->caption);
                $hashtags = $this->caption_hashtags($request->caption);
            }
            $uniqueString =  unique_random('posts', 'url', 20);
            $post = new Post;
            $post->user_id = $user->id;
            $post->caption = $request->caption;
            $post->photos = json_encode($photo_json);
            $post->status = 1; //published
            $post->url = $uniqueString; //url
            if($post->save() && !empty($request->location)){
                $lat = $request->latitude;
                $lon = $request->longitude;
                $city = 'null';
                $country = 'null';


                $array_location = array(
                    'name'=>$request->location,
                    'latitude'=>$lat,
                    'longitude'=>$lon
                );

                $location = new Location;

                $geolocation = array(
                    'latitude'=>$lat,
                    'longitude'=>$lon
                );
                $location->geolocation = json_encode($geolocation);
                $location->data = json_encode( $array_location );
                if(!empty($request->city)){
                    $city = $request->city;
                }
                if(!empty($request->country)){
                    $country = $request->country;
                }
                $location->city = $city;
                $location->country = $country;

                if($location->save()){
                    $post_location = new PostLocation;
                    $post_location->location_id = $location->id;
                    $post_location->post_id = $post->id; //post id from $post->save()
                    $post_location->save();
                }

                // tag
                if(count($hashtags) > 0){
                    foreach($hashtags as $hashtag){
                        $tag = Tag::firstOrNew(array('name' => $hashtag));
                        if($tag->save()){
                            $post_tag = new PostTag;
                            $post_tag->post_id = $post->id;
                            $post_tag->tag_id = $tag->id;
                            $post_tag->save();
                        }
                    }
                }

            }
        }
        return Redirect::route('home');
    }

    function caption_hashtags($string) {

        /* Match hashtags */
        preg_match_all('/#(\w+)/', $string, $matches);

        /* Add all matches to array */
        $keywords = array();
        foreach ($matches[1] as $match) {
            $keywords[] = $match;
        }

        return (array) $keywords;
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
        $post = Post::findOrFail($id);
        $post->caption = $request->caption;
        $post->save();

        //return response(null, Response::HTTP_OK);
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
        Post::destroy($id);

       // return response(null, Response::HTTP_OK);
    }


}
