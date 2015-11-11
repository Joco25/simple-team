<?php

namespace App\Http\Controllers;

use Auth;
use Input;
use App\Topic;
use App\TopicView;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiTopicViewController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = Input::all([
            'topic_id' => null
        ]);

        $topic = Topic::find($inputs['topic_id'])
            ->whereTeamId(Auth::user()->team_id)
            ->first();

        if (! $topic)
        {
            abort('Could not find topic.');
        }

        $success = TopicView::create([
            'topic_id' => $inputs['topic_id'],
            'team_id' => Auth::user()->team_id,
            'user_id' => Auth::user()->id
        ]);

        $topic->updateViewCount();

        return response()->json([
            'success' => (bool) $success
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
