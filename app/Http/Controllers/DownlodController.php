<?php

namespace App\Http\Controllers;

use App\Downlod;
use App\Http\Resources\PostResource;
use App\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DownlodController extends Controller
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
        $status = Downlod::where('post_id',$post_id)->exists();
        $post = Post::find($post_id);
        if ($post) {
            if ($status) {
                $download = Downlod::where('post_id',$post_id)->first(); 
                $download->post_id= $post_id;
                $download->increment('downloads');
                $result = $download->update();
            } else {
                $download = new Downlod();
                $download->post_id= $post_id;
                $download->increment('downloads'); 
                $result = $download->save();
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
                    'message' => 'Post download not recorded',
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
     * @param  \App\Downlod  $downlod
     * @return \Illuminate\Http\Response
     */
    public function show(Downlod $downlod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Downlod  $downlod
     * @return \Illuminate\Http\Response
     */
    public function edit(Downlod $downlod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Downlod  $downlod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Downlod $downlod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Downlod  $downlod
     * @return \Illuminate\Http\Response
     */
    public function destroy(Downlod $downlod)
    {
        //
    }
}
