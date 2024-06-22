<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(){
        if (auth()->check()) {
            if (auth()->user()->can('role')) {
                $Roles=Role::all();
                return view('RolesPermission.Role.index',compact('Roles'));
            } else {
                return redirect('/')->with('error', 'You do not have permission to view role page.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
        
    }

    public function create(){
        
        if (auth()->check()) {
            if (auth()->user()->can('add-role')) {
                return view('RolesPermission.Role.create');
            } else {
                return redirect('/')->with('error', 'You do not have permission to create role page.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
        
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|unique:permissions'
        ]);
        // $permission = Permission::create(['name' => 'edit articles']);
        Role::create(['name'=>$request->name]);
        return redirect()->route('role.index')->with('success','Successfully added');
    }


    public function edit($id){


        if (auth()->check()) {
            if (auth()->user()->can('edit-role')) {
                $findData=Role::find($id);
                return view('RolesPermission.Role.edit',compact('findData'));
            } else {
                return redirect()->back()->with('error', 'You do not have permission to edit role page.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
       
    }

    public function update(Request $request,$id){
        $find=Role::find($id);
        $find->update($request->all());
        return redirect()->route('role.index');
    }

    public function distroy($id){

        if (auth()->check()) {
            if (auth()->user()->can('delete-role')) {
                $find=Role::find($id);
                $find->delete();
                return redirect()->back()->with('success','Successfully deleted');
            } else {
                return redirect()->back()->with('error', 'You do not have permission to delete role page.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }

        
    }

    public function addPermissionToRole($id){
        
        if (auth()->check()) {
            if (auth()->user()->can('assign-permission-to-role')) {
                $findRoal=Role::find($id);
                $permissions=Permission::all();
                $rolesPermissions=DB::table('role_has_permissions')->where('role_has_permissions.role_id',$id)
                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                ->all();
                return view('RolesPermission.Role.addPermission',compact('findRoal','permissions','rolesPermissions'));
            } else {
                return redirect()->back()->with('error', 'You do not have permission add permission to assign permission to role.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }

        
       
    }


    public function givePermissionToRole(Request $request,$id){
        // dd($request->permission);
        
        $request->validate([
            'permission'=> 'nullable'
        ]);
        // dd($request->permission);
        $findRole=Role::find($id);
        $findRole->syncPermissions($request->permission);
        return redirect()->route('role.index')->with('success','successfully added');
    }
}
