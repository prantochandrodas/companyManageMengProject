<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;


class PermisionController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->can('permission')) {
                $permissions = Permission::all();
                return view('RolesPermission.Permission.index', compact('permissions'));
            } else {
                return redirect()->back()->with('error', 'You do not have permission to view permission page.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function create()
    {
        if (auth()->check()) {
            if (auth()->user()->can('permission')) {
                return view('RolesPermission.Permission.create');
            } else {
                return redirect()->back()->with('error', 'You do not have permission to add permission.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions'
        ]);
        // $permission = Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => $request->name]);
        return redirect()->route('permissions.index')->with('success', 'Successfully added');
    }
    public function edit($id)
    {
        if (auth()->check()) {
            if (auth()->user()->can('edit-permission')) {
                $findData = Permission::find($id);
                return view('RolesPermission.Permission.edit', compact('findData'));
            } else {
                return redirect()->back()->with('error', 'You do not have permission to delete permission.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }  
        
    }

    public function update(Request $request, $id)
    {

        $find = Permission::find($id);
        $find->update($request->all());
        return redirect()->route('permissions.index');
    }

    public function distroy($id)
    {

        // if (auth()->check()) {
        //     if (auth()->user()->can('delete-permission')) {
               
        //     } else {
        //         return redirect()->back()->with('error', 'You do not have permission to delete permission.');
        //     }
        // } else {
        //     return redirect()->route('login')->with('error', 'You need to login first.');
        // }  
        $find = Permission::find($id);
        $find->delete();
        return redirect()->back()->with('success', 'Successfully deleted');
    }
}
