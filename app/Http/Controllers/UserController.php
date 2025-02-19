<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attriburtes = $request->validate([
            'username' => 'required',
            'password' => ['required', Password::min(5)],
            'name' => 'required',
        ]);

        $user = User::create($attriburtes);
        Auth::login($user);
        return response('logged in');;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        if ((Auth::user()->role == 'admin') or (Auth::user()->id == $id)) {
            dd(User::destroy($id));
        }
    }
    public function assignRole(Request $request){
        $request->validate([
            'user_id' => 'required',
            'role_id' => 'required',
        ]);
        try {
            $user = User::findOrFail($request->user_id);
            $role = Role::findOrFail($request->role_id);
        }
        catch (ModelNotFoundException $e){
            return response($e->getMessage(), 404);
        }

        if(DB::table('role_user')
            ->where('user_id', $user->id)
            ->exists()) {
            DB::table('role_user')
                ->where('user_id', $user->id)
                ->update(['role_id' => $role->id]);
            return response()->json('role updated');
        }
        else {
            $role->users()->attach($user);
        }

        return response()->json('role assigned');
    }
    public function dischargeRole(Request $request){

        $request->validate([
            'user_id' => 'required',
            'role_id' => 'required',
        ]);
        try {
            $user = User::findOrFail($request->user_id);
            $role = Role::findOrFail($request->role_id);
        }
        catch (ModelNotFoundException $e){
            return response($e->getMessage(), 404);
        }

        if(DB::table('role_user')
            ->where('user_id', $user->id)
            ->exists()) {
            DB::table('role_user')
                ->where('user_id', $user->id)
                ->delete();

            return response()->json('role discharged');
        }

        return response()->json('no role assigned to this user', 404);
    }
    public function dischargePermission(Request $request){
        $request->validate([
            'user_id' => 'required',
            'permissions' => 'required|array',
        ]);
        $resultList = [];
        foreach ($request->permissions as $per) {
            try {
                $user = User::findOrFail($request->user_id);
                $permission = Permission::findOrFail($per);
            } catch (ModelNotFoundException $e) {
                return response([$e->getMessage(), $resultList], 404);
            }

            $success = DB::table('permission_user')
                ->where('user_id', $user->id)
                ->where('permission_id', $permission->id)
                ->delete();

            if ($success) {
                $resultList[] = $permission->name . ' permission discharged';
                continue;
            }

            $resultList[] = $permission->name . ' permission wasn\'t assigned to this user';
        }
        return response()->json($resultList);
    }
    public function assignPermission(Request $request){
        $request->validate([
            'user_id' => 'required',
            'permissions' => 'required|array',
        ]);
        $resultList = [];
        foreach ($request->permissions as $per) {
            try {
                $user = User::findOrFail($request->user_id);
                $permission = Permission::findOrFail($per);
            }
            catch (ModelNotFoundException $e){
                return response([$e->getMessage(), $resultList], 404);
            }

            if(DB::table('permission_user')
                ->where('user_id', $user->id)
                ->where('permission_id', $permission->id)
                ->exists()) {
                $permission->users()->attach($user);
                $resultList[] = $permission->name . 'permission updated';
                continue;
            }
            $resultList[] = $permission->name . 'permission assigned';
        }
        return response()->json($resultList);
    }

}
