<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function indexPage()
    {
        if (auth()->check()) {
            if (auth()->user()->can('user')) {
                $users = User::all();
                return view('RolesPermission.User.index', compact('users'));
            } else {
                return redirect('/')->with('error', 'You do not have permission to view user.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function create()
    {
        if (auth()->check()) {
            if (auth()->user()->can('user')) {
                $roles = Role::all();
                return view('RolesPermission.User.create', compact('roles'));
            } else {
                return redirect('/')->with('error', 'You do not have permission to view user.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|max:20',
            'roles' => 'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->roles);
        return redirect('/user')->with('success', 'user added successful');
    }


    public function edit($id)
    {

        if (auth()->check()) {
            if (auth()->user()->can('edit-user')) {
                $findData = User::find($id);
                $roles = Role::all();
                $userRoles = $findData->roles->pluck('name', 'name')->all();
                return view('RolesPermission.User.edit', compact('findData', 'roles', 'userRoles'));
            } else {
                return redirect()->back()->with('error', 'You do not have permission to edit user.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function update(Request $request, $id)
    {
        $find = User::find($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:8|max:20',
            'roles' => 'required'
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if (!empty($request->password)) {
            $data += [
                'password' => $request->password,
            ];
        }
        $find->update($data);
        $find->syncRoles($request->roles);
        return redirect('/user')->with('success', 'successful updated');
    }

    public function distroy($id)
    {
        if (auth()->check()) {
            if (auth()->user()->can('delete-user')) {
                $find = User::find($id);
                $find->delete();
                return redirect("/user")->with('success', 'Successfully deleted');
            } else {
                return redirect()->back()->with('error', 'You do not have permission to delete user.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function addPermissionToRole($id)
    {
        $findRoal = Role::find($id);
        // $permissions=Permission::all();
        $rolesPermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        return view('RolesPermission.Role.addPermission', compact('findRoal', 'permissions', 'rolesPermissions'));
    }


    public function givePermissionToRole(Request $request, $id)
    {


        $request->validate([
            'permission' => 'required'
        ]);
        // dd($request->permission);
        $findRole = Role::find($id);
        $findRole->syncPermissions($request->permission);
        return redirect()->route('role.index')->with('success', 'successfully added');
    }
}
