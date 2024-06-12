<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('sanctum')->user();

        $teams = Team::with('users')->get();
        $roles = $user->getRoleNames();
        $arr = [];
        if ($roles[0] == 'client-admin') {
            $companies = $user->companies;
            foreach ($companies as $company) {
                $teams = $company->teams->load('users');
                foreach ($teams as $team) {
                    $arr[] = $team;
                }
            }

            return $teams;
        }

        return Team::with('users')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'team' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->team);
        if (Team::where('slug', $slug)->first()) {
            return response()->json([
                'message' => 'Team already exists'
            ], 422);
        }

        $team = new Team();
        $team->name = $request->team;
        $team->slug = $slug;
        $team->save();
        $team['users'] = [];
        // return team with users
        return response()->json([
            'message' => 'Team created successfully',
            'team' => $team
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        $team = $team->load('users');
        $team['userLength'] = $team->users->count();
        return $team;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        if ($team->users()->where('user_id', $request->user_id)->first()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User already in team'
            ]);
        }
        $team->users()->attach($request->user_id);
        return response()->json([
            'status' => 'success',
            'message' => 'User added to team successfully',
            'team' => $team->load('users')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        if ($team->users()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Team has users, cannot delete'
            ]);
        }
        $team->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Team deleted successfully'
        ]);
    }

    public function deleteTeamMember(Team $team, $user_id)
    {
        $team->users()->detach($user_id);
        return response()->json([
            'status' => 'success',
            'message' => 'User removed from team successfully',
            'team' => $team->load('users')
        ]);
    }
}
