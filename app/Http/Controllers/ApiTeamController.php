<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $teams = \Auth::user()->teams;
        return response()->json([
            'teams' => $teams
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
        $team = \App\Team::create([
            'user_id' => \Auth::user()->id,
            'name' => \Input::get('name')
        ]);

        $team->users()->attach(\Auth::user()->id);
        $teams = \Auth::user()->teams;

        return response()->json([
            'teams' => $teams
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $success = \App\Team::whereId($id)
            ->whereUserId(\Auth::user()->id)
            ->delete();

        $teams = \Auth::user()->teams;

        return response()->json([
            'success' => $success,
            'teams' => $teams
        ]);
    }
}
