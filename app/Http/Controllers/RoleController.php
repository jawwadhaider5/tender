<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
 
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
 
    public function index(Request $request)
    { 
        if (!auth()->user()->can('role-list')) {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();
        $userdetai = $user->userdetail; 

        $roles = Role::orderBy('id','DESC')->paginate(5);
        return view('roles.index',compact('roles','user','userdetai'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    } 
    public function create()
    { 
        if (!auth()->user()->can('role-create')) {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();
       $userdetai = $user->userdetail; 

        $permission = Permission::get();
        return view('roles.create',compact('permission','user','userdetai'));
    }
 
    public function store(Request $request)
    { 
        if (!auth()->user()->can('role-create')) {
            abort(403, 'Unauthorized action.');
        } 
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
    
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }
 
    public function show($id)
    { 
        if (!auth()->user()->can('role-view')) {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();
       $userdetai = $user->userdetail; 
        
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
    
        return view('roles.show',compact('role','rolePermissions','user','userdetai'));
    }
 
    public function edit($id)
    { 
        if (!auth()->user()->can('role-edit')) {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();
       $userdetai = $user->userdetail; 

        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return view('roles.edit',compact('role','permission','rolePermissions','user','userdetai'));

    } 
    public function update(Request $request, $id)
    { 
        if (!auth()->user()->can('role-edit')) {
            abort(403, 'Unauthorized action.');
        }

        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
    
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');

    }
 
    public function destroy($id)
    { 
        if (!auth()->user()->can('role-delete')) {
            abort(403, 'Unauthorized action.');
        }


        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}
