<?php

namespace App\Http\Controllers\Api\Renewal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(User::with(['renewals'])->get(), 200);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json(User::with(['renewals','roles'])->find($user->id), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user = User::find($user->id);

        $renewals = $user->renewals ?? [];
        $reminders = $user->reminders ?? [];

        foreach ($renewals as $renewal) {
            $renewal->delete();
        }

        foreach ($reminders as $reminder) {
            $teams = json_decode($reminder->teams);
            if(count($teams) <= 0){
                $reminder->delete();
            }
        }

        $user->delete();

        return response()->json("User deleted successfully");
    }

    public function setRole(Request $request, $id){
        $user = User::find($id);

        $role_id = Role::where('name', $request->role)->first()->id;
        $user->roles()->sync($role_id);
        $user->save();

        return response()->json("User role updated successfully");
    }
}
