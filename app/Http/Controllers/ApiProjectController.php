<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $projects = \App\Project::with('stages.cards.subtasks', 'stages.cards.comments', 'stages.cards.tags', 'stages.cards.users')
            ->whereTeamId(\Auth::user()->team_id)
            ->get();

        return response()->json([
            'projects' => $projects
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
        $stages = \Input::get('stages');

        $project = \App\Project::create([
            'user_id' => \Auth::user()->id,
            'team_id' => \Auth::user()->team_id,
            'name' => \Input::get('name'),
            'priority' => count(\Auth::user()->team->projects)
        ]);

        foreach ($stages as $key => $stage)
        {
            \App\Stage::create([
                'user_id' => \Auth::user()->id,
                'team_id' => \Auth::user()->team_id,
                'project_id' => $project->id,
                'name' => $stage['name'],
                'priority' => $key
            ]);
        }

        $projects = \App\Project::with('stages.cards')
            ->whereTeamId(\Auth::user()->team_id)
            ->get();

        return response()->json([
            'projects' => $projects
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
        $success = \App\Project::whereId($id)
            ->whereTeamId(\Auth::user()->team_id)
            ->update([
                'name' => \Input::get('name')
            ]);

        $project = \App\Project::with('stages.cards')
            ->whereId($id)
            ->whereTeamId(\Auth::user()->team_id)
            ->first();

        return response()->json([
            'project' => $project
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $success = \App\Project::whereId($id)
            ->whereTeamId(\Auth::user()->team_id)
            ->delete();

        $projects = \App\Project::with('stages.cards')
            ->whereTeamId(\Auth::user()->team_id)
            ->get();

        return response()->json([
            'success' => $success,
            'projects' => $projects
        ]);
    }
}
