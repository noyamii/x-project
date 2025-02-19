<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index () {
        return response()->json(Role::all());
    }

    public function store (Request $request) {
        $request->validate([
            'role_name' => 'required',
            'permissions' => 'required|array'
        ]);
        $role = Role::create(['name' => $request->role_name]);

        $resultList = [];
        foreach ($request->get('permissions') as $permission) {
            try {
                $per = Permission::findOrFail($permission);
            }
            catch (ModelNotFoundException $e) {
                return response([$e->getMessage(), $resultList], 404);
            }
            if (DB::table('permission_role')
                ->where('role_id', $role->id)
                ->where('permission_id', $per->id)
                ->doesntExist()) {

                $role->permission()->attach($per);
                $resultList[] = $per->name . 'permission assigned';
                continue;
            }
            $resultList[] = $per->name . ' permission already exist';
        }

        return response($resultList);
    }
    public function destroy (int $id) {
        return Role::destroy($id);
    }
}
