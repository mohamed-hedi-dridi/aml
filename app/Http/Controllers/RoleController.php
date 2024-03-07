<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class RoleController extends Controller
{
    public function index(){
        try {
            $titre = "Rôles et permissions";
            $roles = Role::orderBy('id','desc')->get();
            $noteAdmin = Role::whereNotIn('name',['admin'])->orderBy('id','desc')->get();
            Log::channel('info')->info("Access liste des Rôles ", ['user' => Auth::user()->name] );
            return view("admin.roles.create",compact('titre','roles'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }

    public function store(Request $request){
        try {
            $validator = $request->validate(['name'=>['required','min:3']]);
            $role = Role::where('name',strtolower($request->name))->first();
            if($role){
                return redirect()->back()->with( ['status' => 'error' , 'message' => 'Rôle déjà existe'] );
            }
            $role = new Role();
            $role->name = strtolower($request->name);
            $role->guard_name = "web";
            if($role->save()){
                Log::channel('info')->info("Add new Rôle ", ['user' => Auth::user()->name] );
                return redirect()->back()->with( ['status' => 'success' , 'message' => 'Rôle créé avec succès'] );
            }
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function edit (Role $role){
        try {
            $titre = "Attribuer Permissions";
            $modules = Module::all();
            Log::channel('info')->info("Acces attribuer permission ".$role->name, ['user' => Auth::user()->name] );
            return view("admin.roles.attribuer_permissions",compact('role','titre','modules'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }


    public function attribuer(Request $request , Role $role){
        try {
            $permissions = Permission::all();
            $role->revokePermissionTo($permissions);
            foreach ($request->permission as $key => $value) {
                $permission = Permission::find($value);
                if(!$role->hasPermissionTo($permission)){
                    $permission->assignRole($role);
                }
            }
            Log::channel('info')->info("Attribuer permission pour ".$role->name, ['user' => Auth::user()->name] );
            return redirect()->route('admin.roles.index')->with( ['status' => 'success' , 'message' => 'Rôle Update avec succès'] );
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }


    }
}
