<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaderBoardRequest;
use App\Http\Resources\LeaderBoardResource;
use App\LeaderBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\New_;
use Symfony\Component\HttpFoundation\Response;

class LeaderBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaderBoards = LeaderBoard::count();
        if ($leaderBoards > 0) {
            $leaderBoards = LeaderBoard::all();
            return response([
                'error' => False,
                'message' => 'Success',
                'leaderBoards' =>  LeaderBoardResource::collection($leaderBoards)
            ], Response::HTTP_OK);
        } else {
            return response([
                'error' => True,
                'message' => 'Failed, no leaderBoards found',
            ], Response::HTTP_OK);
        }
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
    public function store(LeaderBoardRequest $request)
    {
        //enroll to leaderboard

        $leaderBoard = new LeaderBoard();
        $leaderBoard->user_id = Auth::user()->id;
        $leaderBoard->lati = $request->lati;
        $leaderBoard->longi = $request->longi;

        if ($leaderBoard->save()) {
            return response([
                'error' => False,
                'message' => 'Success, You are now on leaderboard.',
                'user' => new LeaderBoardResource($leaderBoard)
            ], Response::HTTP_OK);
        } else {
            return response([
                'error' => true,
                'message' => 'failed, try again later!',
            ], Response::HTTP_OK);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LeaderBoard  $leaderBoard
     * @return \Illuminate\Http\Response
     */
    public function show(LeaderBoard $leaderBoard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LeaderBoard  $leaderBoard
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaderBoard $leaderBoard)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LeaderBoard  $leaderBoard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaderBoard $leaderBoard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LeaderBoard  $leaderBoard
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaderBoard $leaderBoard)
    {
        //
    }
}
