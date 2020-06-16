<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Jobs\ConvertVideoForStreaming;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
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
    public function store(PostRequest $request)
    {
        
        
        $post = new Post();
        $post->user_id= Auth::user()->id;
        $post->type=$request->type;
        $post->text=$request->text;
        // $post->tags=
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
        if($request->type == 'image'){
            $imagefolder = '/Postimages';
            $post->file_path = $this->generateUniqueFileName($filePath, $imagefolder);
        }else if($request->type == 'video'){
            $imagefolder = '/Postvideos';
            $post->file_path = $this->generateUniqueFileName($filePath, $imagefolder);
        }
        

        if($post->save()) {
            // ConvertVideoForStreaming::dispatch($post);
            return response([
                'error' => False,
                'message' => 'Post upload success, your video will be available shortly after we process it',
                'post' => new PostResource($post)
            ], Response::HTTP_OK);
        } else {
            return response([
                'error' => true,
                'message' => 'failed uploading post',
            ], Response::HTTP_OK);
        }
        
 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
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
}
