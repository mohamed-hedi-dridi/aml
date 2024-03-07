<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class ModuleController extends Controller
{
    public function index(){
        try {
            $titre = "Gestion des Modules";
            $modules = Module::all();
            Log::channel('info')->info("Acces Liste des Modules ", ['user' => Auth::user()->name] );
            return view('admin.modules.create', compact('titre','modules'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function store(Request $request){
        try {
            $validator = $request->validate(
                ['name'=>['required','min:3']]
            );
            $module = Module::where('name',strtolower($request->name))->first();
            if($module){
                return redirect()->back()->with( ['status' => 'error' , 'message' => 'Module déjà existe'] );
            }
            $module = new Module;
            $module->name = $request->name ;
            $module->actif = 1;
            if ($module->save()) {
                $permission = new Permission ();
                $permission->name = $module->name;
                $permission->guard_name = "web";
                $permission->module_id = $module->id;
                $permission->save();
                $role = Role::findByName('admin');
                $permission->assignRole($role);
                Log::channel('info')->info("Add new Module ", ['user' => Auth::user()->name] );
                return redirect()->back()->with( ['status' => 'success' , 'message' => 'Module créé avec succès'] );
            }
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }
}
