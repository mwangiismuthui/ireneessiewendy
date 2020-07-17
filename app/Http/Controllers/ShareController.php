<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Post;
use App\Share;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShareController extends Controller
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
    public function store($post_id)
    {
        
        $status = Share::where('post_id',$post_id)->exists();
        $post = Post::find($post_id);
        if ($post) {
            if ($status) {
                $share = Share::where('post_id',$post_id)->first(); 
                $share->post_id= $post_id;
                $share->increment('shares');
                $result = $share->update();
            } else {
                $share = new Share();
                $share->post_id= $post_id;
                $share->increment('shares'); 
                $result = $share->save();
            }
            
            if ($result) {
                return response([
                    'error' => False,
                    'message' => 'Success',
                    'post' => new PostResource($post)
                ], Response::HTTP_OK);
            } else {
                return response([
                    'error' => true,
                    'message' => 'Post share not recorded',
                ], Response::HTTP_OK);
            }
        } else {
            return response([
                'error' => true,
                'message' => 'Post not found',
            ], Response::HTTP_OK);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function show(Share $share)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function edit(Share $share)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Share $share)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function destroy(Share $share)
    {
        //
    }
}
