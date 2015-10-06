<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiDailySummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dailySummaries = \App\DailySummary::with('user')
            ->whereTeamId(\Auth::user()->team_id)
            ->get();

        return response()->json([
            'dailySummaries' => $dailySummaries
        ]);
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
        $dailySummary = \App\DailySummary::create([
            'user_id' => \Auth::user()->id,
            'team_id' => \Auth::user()->team_id,
            'body' => \Input::get('body')
        ]);

        $dailySummaries = \App\DailySummary::with('user')
            ->whereTeamId(\Auth::user()->team_id)
            ->get();

        return response()->json([
            'dailySummaries' => $dailySummaries
        ]);
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
        $success = \App\DailySummary::whereId($id)
            ->whereUserId(\Auth::user()->id)
            ->update([
                'body' => \Input::get('body')
            ]);

        $dailySummaries = \App\DailySummary::with('user')
            ->whereTeamId(\Auth::user()->team_id)
            ->get();

        return response()->json([
            'dailySummaries' => $dailySummaries
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $success = \App\DailySummary::whereId($id)
            ->whereTeamId(\Auth::user()->team_id)
            ->delete();

        return response()->json([
            'success' => $success
        ]);
    }
}
