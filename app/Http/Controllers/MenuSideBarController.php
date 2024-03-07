<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\MenuSideBar;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class MenuSideBarController extends Controller
{
    public function index(){
        try {
            $titre = "Menu SideBar";
            //$permissions = Permission::groupBy('module_id')->get();
            $modules = Module::where('actif',1)->get();
            Log::channel('info')->info("Acces Liste des MenuSideBar ", ['user' => Auth::user()->name] );
            return view('admin.menuSideBar.create', compact('titre','modules'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function create(){

    }

    public function store(Request $request){
        try {
            $validator = $request->validate(
                ['name'=>['required','min:3'],
                 'module_id'=>['required','min:1'],
                 'permission_id'=>['required','min:1']
                 ]
            );
            $menu = MenuSideBar::where('name',strtolower($request->name))->where('module_id',$request->module)->first();
            if($menu){
                return redirect()->back()->with( ['status' => 'error' , 'message' => 'Menu déjà existe'] );
            }
            $menu = new MenuSideBar();
            $menu->name = $request->name;
            $menu->route = $request->route;
            $menu->module_id = $request->module_id;
            $menu->icon = $request->icon;
            $menu->permission_id = $request->permission_id;
            if (isset($request->parent)) {
                $menu->parent = $request->parent;
            }
            if ($menu->save()) {
                Log::channel('info')->info("Add new MenuSideBar ", ['user' => Auth::user()->name] );
                return redirect()->back()->with( ['status' => 'success' , 'message' => 'Menu créé avec succès'] );
            }
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
           return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }


    public function ajaxParentPermission($id){
        $result = "" ;
        $permission = "";
        $menus = MenuSideBar::where('module_id',$id)->get();
        if(count($menus)){
            $result = '<div class="form-group row"><div class="mb-10"><label class="form-control-label col-sm-12 col-form-label">Parent Menu:</h5></div>';
            $result = $result.'<select id="module" class="custom-select2 form-control" data-size="5" name="parent">';

            foreach ($menus as $key => $menu) {
                $result = $result.'<option value="'.$menu->id.'">'.$menu->name.'</option>';
            }
            $result = $result.'</select></div>';
        }
        $permissions = Permission::where('module_id',$id)->get();
        if(count($permissions)){
            $permission = '<div class="form-group row "><div class="mb-10"><label class="form-control-label col-sm-12 col-form-label">Permission:</h5></div>';
            $permission = $permission.'<select class="custom-select2 form-control" data-size="5" required name="permission_id">';

            foreach ($permissions as $key => $p) {
                $permission = $permission.'<option value="'.$p->id.'">'.$p->name.'</option>';
            }
            $permission = $permission.'</select></div>';
        }
        $data["parent"] = $result ;
        $data["permission"] = $permission;
        return response()->json($data) ;
    }

}
