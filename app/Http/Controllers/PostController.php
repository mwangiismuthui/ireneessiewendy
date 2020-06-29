<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Jobs\ConvertVideoForStreaming;
use App\Post;
use DB;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\FollowsResource;
use App\Http\Resources\TrendingUsersResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $merged = [];
        if ($this->isNew($user)) {
            $trendingPost = $this->trending(3, 70);
            $latest = $this->latestPosts(30);
        $merged = $trendingPost->merge($latest);
        // dd('new');
        } else {
            // dd('me');
           $hashTags =$this->postUserTags($user,40);
           $followingsPost = $this->getUserFollowingsPosts($user,30);
           $latest2 = $this->latestPosts(30);

            $merged = $hashTags->merge($followingsPost)->merge($latest2);
        }

        $result = $merged->all();
        // return $result;

        $posts_results = PostResource::collection($result);

        return response([
            'error' => False,
            'message' => 'Success',
            'post' => $posts_results
        ], Response::HTTP_OK);
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

    public function getUserFollowingsPosts($user, $limit)
    {
        $wenyeUnafuata =  $user->followings;

        $counter = 0;
        $query = Post::with('user')->select(); // Initializes the query
        foreach ($wenyeUnafuata as $following) {
            if ($counter == 0) {
                $query->where('user_id', $following->id);
            } else {
                $query->orWhere('user_id', $following->id);
            }
            $counter++;
        }
        return $query->limit($limit)->get();
    }
    public function postUserTags($user, $limit)
    {
        $tags = $this->hashTags($user);
        $counter = 0;
        $query = Post::with('user')->select(); // Initializes the query
        foreach ($tags as $value) {
            if ($counter == 0) {
                $query->where(DB::raw("CONCAT_WS(' ', tags)"), 'LIKE', '%' . $value . '%');
            } else {
                $query->orWhere(DB::raw("CONCAT_WS(' ', tags)"), 'LIKE', '%' . $value . '%');
            }
            $counter++;
        }
        return $query->limit($limit)->get();
    }

    public function hashTags($user)
    {
        $tags = [];
        $likes = $user->likes()->with('likeable')->get();
        foreach ($likes as $like) {
            $tags[] =    $like->likeable->tags; // App\Post instance
        }
        return $tags;
    }
    public function isNew($user)
    {
        $user_likes = $user->likes()->count();
        // dd($user_likes);
        if ($user_likes <= 3) {
            return true;
        } else {
            return false;
        }
    }
    public function latestPosts($limit)
    {
        $latest = Post::with('user')->orderBy('posts.created_at', 'Desc')
            ->limit($limit)
            ->get();
        return $latest;
    }
    public function trending($duration, $limit)
    {

        $number_of_days = \Carbon\Carbon::today()->subDays($duration);
        $trendingPost = Post::with('user')->where('posts.created_at', '>=', $number_of_days)
            ->orderBy('views', 'Desc')
            ->limit($limit)
            ->get();
        return $trendingPost;
    }
    public function getTrending()
    {
        $trending = $this->trending(8, 100);

        $posts_trending = PostResource::collection($trending);

        return response([
            'error' => False,
            'message' => 'Success',
            'post' => $posts_trending
        ], Response::HTTP_OK);
    }

    public function trendingUsers(){
        $trending_posts = $this->trending(8, 100);
        $trending_users = collect(new User);
        foreach($trending_posts as $post){
            $trending_users->add( $post->user);
        }
        $users = TrendingUsersResource::collection($trending_users->unique()); 

        if(!is_null($trending_users)){
            return response([
                'error' => False,
                'message' => 'Success',
                'users' => $users
            ], Response::HTTP_OK);
        }else{
            return response([
                'error' => true,
                'message' => 'No records found',
            ], Response::HTTP_OK);
        }
        
    }

    public function userPosts($user_id)
    {
        $user = User::find($user_id);
        $posts = PostResource::collection($user->posts);
        if ($user) {
            if(!is_null($posts)){
                return response([
                    'error' => False,
                    'message' => 'Success',
                    'post' => $posts
                ], Response::HTTP_OK);
            }else{
                return response([
                    'error' => true,
                    'message' => 'No records found',
                ], Response::HTTP_OK);
            }
        } else {
            return response([
                'error' => true,
                'message' => 'User not found!',
            ], Response::HTTP_OK);
        }
        
        
    }

    public function trendingHashtags()
    {
        $tags = [];
        $posts = $this->trending(7,100);
        foreach($posts as $post){
            $seperatedtags = array_diff(explode(",",$post->tags),array(""));
            foreach($seperatedtags as $tag){
                $tags[]=$tag;
            }
        }

        $ourHashtags = array_values(array_unique($tags));
        $data= [];
        foreach($ourHashtags as $hasgtag){
            $data[] = [
                'tag'=> $hasgtag,
                'post_count'=> Post::where('tags', 'like', '%' . $hasgtag . '%')->count()
            ];
        }

        if(!is_null($data)){
            return response([
                'error' => False,
                'message' => 'Success',
                'tag' => $data
            ], Response::HTTP_OK);
        }else{
            return response([
                'error' => true,
                'message' => 'No tags found',
            ], Response::HTTP_OK);
        }

    }

    public function hashtagPosts($hashtag_string)
    {
        $posts = Post::where('tags', 'like', '%' . $hashtag_string . '%')->get();
        $data = PostResource::collection($posts);
        if(!is_null($data)){
            return response([
                'error' => False,
                'message' => 'Success',
                'post' => $data
            ], Response::HTTP_OK);
        }else{
            return response([
                'error' => true,
                'message' => 'No posts found',
            ], Response::HTTP_OK);
        }
    }

    public function normalSearch($query_text)
    {
        $posts = Post::where('tags', 'like', '%' . $query_text . '%')
                        ->orWhere('text', 'like', '%' . $query_text . '%')
                        ->get();
        $data = PostResource::collection($posts);
        if(!is_null($data)){
            return response([
                'error' => False,
                'message' => 'Success',
                'post' => $data
            ], Response::HTTP_OK);
        }else{
            return response([
                'error' => true,
                'message' => 'No posts found',
            ], Response::HTTP_OK);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {


        $post = new Post();
        $post->user_id = Auth::user()->id;
        $post->type = $request->type;
        $post->text = $request->text;
        $post->tags= $request->tags;
        // $post->image_url=       
        // $post->location=
        // $post->views=
        // $post->dummy=
        // $post->has_link=
        // $post->background_color=
        // $post->backlink=
        // $post->thumbnails=
        // $post->videopreview=
        // $post->stream_path=
        // $post->processed=
        // $post = Post::create([
        //     'disk'          => 'public',
        //     'original_name' => $request->post->getClientOriginalName(),
        //     'path'          => $path,
        //     'title'         => $request->title,
        // ]); 
        $filePath = $request->file('post');
        if ($request->type == 'image') {
            $imagefolder = '/Postimages';
            $post->file_path = $this->generateUniqueFileName($filePath, $imagefolder);
        } else if ($request->type == 'video') {
            $imagefolder = '/Postvideos';
            $post->file_path = $this->generateUniqueFileName($filePath, $imagefolder);
        }


        if ($post->save()) {
            // ConvertVideoForStreaming::dispatch($post);
            return response([
                'error' => False,
                'message' => 'Post upload success',
                'post' => new PostResource($post)
            ], Response::HTTP_OK);
        } else {
            return response([
                'error' => true,
                'message' => 'failed uploading post',
            ], Response::HTTP_OK);
        }
    }

    public function LikePost($id)
    {

        $user = Auth::user();
        $post = Post::find($id);
        //  dd($user->like($post));
        if ($post) {
            if ($user->hasLiked($post)) {
                $response = $user->toggleLike($post);
                return response([
                    'error' => False,
                    'message' => 'Post unliked',
                ], Response::HTTP_OK);
            } else {
                $response = $user->toggleLike($post);
                return response([
                    'error' => False,
                    'message' => 'Post Liked',
                ], Response::HTTP_OK);
            }
        } else {
            return response([
                'error' => true,
                'message' => 'Post not found',
            ], Response::HTTP_OK);
        }
    }
    public function follow($id)
    {

        $user = User::find($id);
        $me = Auth::user();
        if ($user) {
            if ($me->isFollowing($user)) {
                $me->toggleFollow($user);
                return response([
                    'error' => False,
                    'message' => $user->username . ' ' . 'UnFollowed',
                ], Response::HTTP_OK);
            } else {

                $me->toggleFollow($user);
                return response([
                    'error' => False,
                    'message' => $user->username . ' ' . 'Followed',
                ], Response::HTTP_OK);
            }
        } else {
            return response([
                'error' => true,
                'message' => 'User not found',
            ], Response::HTTP_OK);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

   

    public function generateUniqueFileName($file, $destinationPath)
    {
        $initial = "kenyasihami_";
        $name = $initial . bin2hex(random_bytes(8)) . time() . '.' . $file->getClientOriginalExtension();
        if ($file->move(public_path() . $destinationPath, $name)) {
            return $name;
        } else {
            return null;
        }
    }

    public function moveUploadedFile($param, $folder)
    {
        $image = str_replace('data:image/png;base64,', '', $param);
        $image = str_replace(' ', '+', $image);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $imageName = sprintf('%s.%0.8s', $basename, "png");

        $filePath = $folder . "/" . $imageName;
        // return Storage::disk('local')->put($filePath, $uploadedFile_base64) ? $filePath : false;
        //check if the directory exists
        if (!File::isDirectory($folder)) {
            //make the directory because it doesn't exists
            File::makeDirectory($folder);
        }
        if (\File::put(public_path() . '/' . $filePath, base64_decode($image))) {
            return $imageName;
        } else {
            return null;
        }
    }


    public function validateString($s)
    {
        if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s) && base64_decode($s, true)) {
            return true;
        } else {
            return false;
        }
    }

    public function followers($user_id)
    {
        $user = User::find($user_id);
        $followers = $user->followers()->withCount('followers')->orderByDesc('followers_count')->get();
       $followers_resource = FollowsResource::collection($followers);
        return response([
            'error' => False,
            'followers' => $followers_resource,
            ], Response::HTTP_OK);
    }
    public function followings($user_id)
    {
        $user = User::find($user_id);
        $following = $user->followings()->withCount('followings')->orderByDesc('followings_count')->get();
        $followings_resource = FollowsResource::collection($following);
        return response([
            'error' => False,
            'followings' => $followings_resource,
            ], Response::HTTP_OK);
    }

    public function profile($user_id)
    {
      $posts = Post::where('user_id',$user_id)->withCount('likers')->orderBy('id', 'DESC')->get();
      $usersdetails = User::where('id', $user_id)->withCount('followers')->withCount('followings')->get();
      $userposts_resource = PostResource::collection($posts);
      $usersdetails_resource = FollowsResource::collection($usersdetails);
      return response([
          'error' => False,
          'profile' => $usersdetails_resource,
          'posts' => $userposts_resource,
          ], Response::HTTP_OK);
   }
}
