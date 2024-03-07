<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index(){
        try {
            $titre = "Permissions Par Module";
            //$permissions = Permission::groupBy('id_module')->get();
            $modules = Module::where('actif',1)->get();
            Log::channel('info')->info("Acces liste des Permissions ", ['user' => Auth::user()->name] );
            return view('admin.permissions.create', compact('titre','modules'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }

    public function create(){

    }

    public function store(Request $request){
        try {
            $validator = $request->validate(
                ['name'=>['required','min:3'],
                 'module_id'=>'required','min:1']
            );
            $permission = Permission::where('name',strtolower($request->name))->where('module_id',$request->module)->first();
            if($permission){
                return redirect()->back()->with( ['status' => 'error' , 'message' => 'Permission déjà existe'] );
            }
            $permission = Permission::create($validator);
            $role = Role::where('name','admin')->first();
            $permission->assignRole($role);
            Log::channel('info')->info("Add new Permission ", ['user' => Auth::user()->name] );
            return redirect()->back()->with( ['status' => 'success' , 'message' => 'Permission créé avec succès'] );
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }


    }
}
