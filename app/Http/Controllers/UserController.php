<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\Direction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index(){
        try {
            $users = User::all();
            $titre = "Liste des Utilisateur";
            Log::channel('info')->info("Access liste des Users ", ['user' => Auth::user()->name] );
            return view('admin.users.index',compact('titre','users'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }

    public function create(){
        try {
            $titre = 'Créer un nouvel utilisateur';
            $directions = Direction::all();
            $roles = Role::all();
            Log::channel('info')->info("Access au cree new User ", ['user' => Auth::user()->name] );
            return view('admin.users.create',compact('titre','roles','directions'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }

    public function store(Request $request){
        try {
            $validator = $request->validate(
                ['name'=>['required','min:3'],
                'email'=>['required','min:10','email','unique:users,email'],
                'telephone'=>['required','numeric'],
                'role'=>['required','min:1'],
                'direction'=>['required','min:1']
                ]
            );
            $user = User::where('email', $request->email)->first();
            if($user == null){
                $role = Role::find($request->role);
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->email_verified_at = now();
                $user->password = Hash::make($request->telephone);
                $user->telephone=$request->telephone;
                $user->remember_token = Str::random(10);
                $user->direction_id =$request->direction;
                $user->assignRole($role);
                if ($user->save()) {
                    Log::channel('info')->info("Create new User ", ['user' => Auth::user()->name] );
                    return redirect()->route('admin.users.index')->with( ['status' => 'success' , 'message' => 'Utilisateur créé avec succès'] );
                }
            }else{
                return redirect()->back()->with( ['status' => 'error' , 'message' => 'Utilisateur déjà existe'] );
            }

        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function edit($id){
        try {
            $user = User::find($id);
            $titre = "Edit User";
            $roles = Role::all();
            Log::channel('info')->info("Access Edit User ", ['user' => Auth::user()->name] );
            return view('admin.users.edit',compact('user','titre','roles'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }

    public function update(Request $request, $id){
        try {
            $validator = $request->validate(
                ['name'=>['required','min:3'],
                'telephone'=>['required','numeric'],
                'role'=>['required','min:1'],
                'direction'=>['required','min:1']
                ]
            );
            $user = User::find($id);
            $role = Role::find($request->role);
            $user->name = $request->name;
            $user->telephone = $request->telephone;
            $user->direction_id = $request->direction;
            $user->statut = $request->statut;
            if($user->save()){
                $user->syncRoles($role);
                Log::channel('info')->info("Update User ".$user->name, ['user' => Auth::user()->name] );
                return redirect()->route('admin.users.index')->with( ['status' => 'success' , 'message' => 'Utilisateur Update avec succès'] );
            }
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function show(Request $request , User $user){
        //dd($user);
    }

    public function resetPassword($id){
        try {
            $user = User::find($id);
            $user->password = Hash::make($user->telephone);
            if ($user->save()) {
                Log::channel('info')->info("Reset Password User ".$user->name, ['user' => Auth::user()->name] );
                return redirect()->route('admin.users.index')->with( ['status' => 'success' , 'message' => 'Mote de passe reset avec succès'] );
            }
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->route('admin.users.index')->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $validator = $request->validate(
                ['Ancien'=>['required','min:8'],
                'Nouveau'=>['required','min:8'],
                'ConfirmNouveau'=>['required','min:8']
                ]
            );
            $Ancien = $request->Ancien;
            $Nouveau = $request->Nouveau;
            $ConfirmNouveau = $request->ConfirmNouveau;
            $user = User::find(Auth::user()->id);
            $test = $Ancien != $user->password;
            if($Nouveau != $ConfirmNouveau){
                return redirect()->back()->with( ['status' => 'error' , 'message' => 'Le Noveau Mot de passe et le Confirm mot de passe ne sont pas identiques'] );
            }elseif(!Hash::check($Ancien , Auth::user()->password)){
                return redirect()->back()->with( ['status' => 'error' , 'message' => 'Ancien Mot de passe incorrect'] );
            }else{
                $user->password = Hash::make($Nouveau);
                if ($user->save()) {
                    Log::channel('info')->info(Auth::user()->name." à modifier son mote de passe", ['user' => Auth::user()->name] );
                    return redirect()->back()->with( ['status' => 'success' , 'message' => '"Mot de passe Modifier Avec Succés"'] );
                }
            }
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function NewPassword(){
        try {
            $titre ="Paramètres du compte";
            Log::channel('info')->info("Access Update Password ", ['user' => Auth::user()->name] );
            return view('admin.users.changePassword',compact('titre'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }
}
