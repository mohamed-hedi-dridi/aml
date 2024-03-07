<?php

namespace App\Http\Controllers;

use App\Models\Western;
use App\Models\TypeMandat;
use App\Models\MenuSideBar;
use Illuminate\Http\Request;
use App\Models\IdentityCheck;
use App\Models\SuspectMandat;
use App\Imports\ImportMandats;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class MandatController extends Controller
{
    public function index($type){
        try {
            $titre = "Liste des mandats International ";
            if($type == "All"){
                $westerns = Western::orderBy('date','desc')->get()->unique('code')->take(100);
                $typemandat = TypeMandat::where("international",1)->pluck("nom")->toarray();
                $IdentityCheck = IdentityCheck::whereIn('type_mandat',$typemandat)->orderBy('date','desc')->get()->unique('code')->take(100);
                $mandats = $westerns->merge($IdentityCheck)->sortByDesc('date')->all();
                $type = "International";
            }elseif($type=="Western Union"){
                $mandats = Western::orderBy('date','desc')->get()->unique('code')->take(100);
                $titre = $titre.$type;
            }elseif($type != "All"){
                $mandats = IdentityCheck::where('type_mandat',$type)->orderBy('date','desc')->get()->unique('code')->take(100);
                $titre = $titre.$type;
            }
            Log::channel('info')->info("Acces Liste des mandats ".$type, ['user' => Auth::user()->name] );
            return view('admin.mandats.index',compact('titre', 'mandats' , 'type'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }


    public function mandat_nationale(){
        try {
            $titre = "Liste des mandats National ";
            //if($type != "All"){
                $mandats = IdentityCheck::where('type_mandat','Mandat nationale')->orderBy('date','desc')->get();
            // $titre = $titre.$type;
            //}else{
            //  $mandats = IdentityCheck::all();
            //}
            Log::channel('info')->info("Acces Liste des mandats National ", ['user' => Auth::user()->name] );
            return view('admin.mandats.index',compact('titre', 'mandats'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function import(){
        try {
            $validator = $request->validate([
                'file' => ['required',"mimes:xlsx,xls"],
            ]);
            $file = $request->file;
            Excel::import(new ImportMandats, $file);
            return redirect()->back()->with('success', 'Fichier importer avec success!');
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function type(){
        $titre = "Liste Type Mandats";
        $mandats = TypeMandat::all();
        return view('admin.mandats.typeMandat', compact('titre','mandats'));
    }

    public function store(Request $request){
        try {
            $validator = $request->validate(
                ['international'=>['required','min:1'],
                'code'=>['required','min:1'],
                'nom'=>['required','min:3']
                ]
            );
            $type = TypeMandat::where('nom',$request->nom)->orWhere('code',$request->code)->first();
            if($type){
                return redirect()->back()->with( ['status' => 'error' , 'message' => 'Type Mandat déjà existe'] );
            }
            $type = new TypeMandat();
            $type->nom = $request->nom ;
            $type->code =$request->code;
            $type->international =$request->international;
            if($type->save()){
                $MenuSideBar = new MenuSideBar();
                $MenuSideBar->name = 'Mandats '.$request->nom;
                $MenuSideBar->route = '/admin/mandats/'.$request->nom;
                $MenuSideBar->module_id = 6;
                if($request->international == 1){
                    $MenuSideBar->parent = 11;
                    $MenuSideBar->permission_id = 23;
                }else{
                    $MenuSideBar->parent = 16;
                    $MenuSideBar->permission_id = 24;
                }
                $MenuSideBar->save();
                Log::channel('info')->info("Add mandat National ".$type->nom, ['user' => Auth::user()->name] );
                return redirect()->back()->with( ['status' => 'success' , 'message' => 'Type Mandat créé avec succès'] );
            }

        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );

        }
    }

    public function view($id){

        if(in_array($id[0],["T","W","E","M"])){
            $mandat = IdentityCheck::where('code', $id)->first();
        }else{
            $mandat = Western::where('code', $id)->first();
        }
        $latestSuspect = $mandat->getStatus();
        $titre = "Mandat : ".$id;
        return view ('admin.mandats.show', compact('titre','mandat', 'latestSuspect'));
    }


    public function updateStatus(Request $request){
        try {
            $Suspect = new SuspectMandat();
            $Suspect->code = $request->code ;
            $Suspect->nb_iteration = 0 ;
            $Suspect->date_modif = date('Y-m-d');
            $Suspect->statut = $request->statut ;
            $Suspect->updated_BO = 1 ;
            $Suspect->user_id = Auth::user()->id ;
            $Suspect->save();
            Log::channel('info')->info("Update Statut du mandat ".$request->code, ['user' => Auth::user()->id] );
            return response()->json(['statut'=>true, 'message' => 'Item updated successfully']);
        } catch (\Throwable $th) {
            Log::channel('error')->error($th->getMessage(), ['user' => Auth::user()->name,'line' => $th->getLine()]);
            return response()->json(['statut'=>false, 'message' => $th->getMessage()]);
        }
    }


    public function createSuspectMandat(Request $request){

        $rules  = [
            'code' => 'required|string',
            'input_identity' => 'required|string',
            'output_identity' => 'required|string',
        ];
        $messages = [
            'code.required' => 'code is required.',
            'input_identity.required' => 'input_identity is required.',
            'output_identity.required' => 'output_identity is required.',
            // Add more custom messages for other rules
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Unprocessable Entity
        }
        //return $request->code;
        try {
            $latestSuspect = SuspectMandat::where('code',$request->code)->orderBy('id','desc')->first();
            if($latestSuspect == null){
                $nb_iteration = 1 ;
                $statut  = 0 ;
            }else{
                $nb_iteration = $latestSuspect->nb_iteration + 1 ;
                $statut  = $latestSuspect->statut ;
            }
            if($nb_iteration >= 6 && $statut == 0){
                $statut = 1;
            }

            $Suspect = new SuspectMandat();
            $Suspect->code = $request->code ;
            $Suspect->nb_iteration = $nb_iteration ;
            $Suspect->date_modif = date('Y-m-d') ;
            $Suspect->statut = $statut ;
            $Suspect->input_identity = $request->input_identity ;
            $Suspect->output_identity = $request->output_identity ;
            $Suspect->save();
            return response()->json(['success' => 'Created successfully'],200);
        } catch (\Throwable $th) {
            Log::channel('error')->error($th->getMessage(), ['line' => $e->getLine()]);
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    public function ajaxListeMandat(Request $request){
        try {
            if($request->type  == "All"){
                $westerns = new Western ();
                $typemandat = TypeMandat::where("international",1)->pluck("nom")->toarray();
                $IdentityCheck = IdentityCheck::whereIn('type_mandat',$typemandat);
                if ($request->code) {
                    $westerns =  $westerns->where("code",'like','%'.$request->code.'%');
                    $IdentityCheck = $IdentityCheck->where("code",'like','%'.$request->code.'%');
                }
                if($request->input_identity){
                    $westerns =  $westerns->where("input_identity",'like','%'.$request->input_identity.'%');
                    $IdentityCheck = $IdentityCheck->where("input_identity",'like','%'.$request->input_identity.'%');
                }
                $westerns = $westerns->orderBy('date','desc')->get()->unique('code');
                $IdentityCheck = $IdentityCheck->orderBy('date','desc')->get()->unique('code');
                $mandats = $westerns->merge($IdentityCheck)->sortByDesc('date')->all();
            }elseif($request->type=="Western Union"){
                $mandats = new Western ();
                if ($request->code) {
                    $mandats =  $mandats->where("code",'like','%'.$request->code.'%');
                }
                if($request->input_identity){
                    $mandats =  $mandats->where("input_identity",'like','%'.$request->input_identity.'%');
                }
                $mandats = $mandats->orderBy('date','desc')->get()->unique('code');
            }elseif($request->type != "All"){
                $mandats = IdentityCheck::where('type_mandat',$request->type);
                if ($request->code) {
                    $mandats = $mandats->where("code",'like','%'.$request->code.'%');
                }
                if($request->input_identity){
                    $mandats = $mandats->where("input_identity",'like','%'.$request->input_identity.'%');
                }
                $mandats = $mandats->orderBy('date','desc')->get()->unique('code');
            }
            return view('admin.mandats.layoutAjax',compact('mandats'));
        } catch (\Throwable $th) {
            Log::channel('error')->error($th->getMessage(), ['user' => Auth::user()->name,'line' => $th->getLine()]);
            return response()->json($th->getMessage(), 400);
        }

    }
}


