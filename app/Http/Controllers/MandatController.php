<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kyc;
use App\Models\Western;
use App\Models\TypeMandat;
use App\Models\MenuSideBar;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EasyTransfert;
use App\Models\IdentityCheck;
use App\Models\SuspectMandat;
use App\Imports\ImportMandats;
use App\Models\MandatsCentral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MandatController extends Controller
{
    public function index(Request $request , $type){
        try {
            $url = $type ;
            $type = $request->type_mandat;
            $startDate = $request->dateDebut ? $request->dateDebut : Carbon::now()->subMonths(0)->startOfMonth()->format("Y-m-d");
            $endDate = $request->dateFin ? $request->dateFin : Carbon::now()->format("Y-m-d");
            $titre = "Liste des mandats ";
            $type_mandat = "All" ;
            if($type == "All"){
                $type_mandat = "All" ;
                $westerns = Kyc::select(DB::raw('*'))->whereBetween('date', [$startDate, $endDate]);
                $IdentityCheck = IdentityCheck::where('type_mandat','!=','Mandat nationale')->whereBetween('date', [$startDate, $endDate]);

                $westerns->when($request->email, fn ($q) =>
                    $q->where('email_agent' , $request->email)
                );
                $westerns->when($request->code, fn ($q) =>
                    $q->where('code' , $request->code)
                );

                $westerns->when($request->email, fn ($q) =>
                    $q->where('email_agent' , $request->email)
                );
                $westerns->when($request->code, fn ($q) =>
                    $q->where('code' , $request->code)
                );

                $westerns = $westerns->orderByDesc('date')->get()->unique('code');
                $IdentityCheck = $IdentityCheck->orderBy('date','desc')->get()->unique('code')->take(100);
                $mandats = $westerns->merge($IdentityCheck)->sortByDesc('date')->all();
                $type = "International";
            }elseif($type=="Western Union"){
                $type_mandat = "WU" ;
                $mandats = Kyc::select(DB::raw('*'))->where('type_mandat','WU')->whereBetween('date', [$startDate, $endDate]);
                $mandats->when($request->email, fn ($q) =>
                    $q->where('email_agent' , $request->email)
                );
                $mandats->when($request->code, fn ($q) =>
                    $q->where('code' , $request->code)
                );
                $mandats = $mandats->orderBy('id','desc')->get()->unique('code');
                $titre = $titre.$type;
            }elseif($type=="Ria Money"){
                $type_mandat = "RIA" ;
                $mandats = Kyc::where('type_mandat','RIA')->whereBetween('date', [$startDate, $endDate]);
                $mandats->when($request->email, fn ($q) =>
                    $q->where('email_agent' , $request->email)
                );
                $mandats->when($request->code, fn ($q) =>
                    $q->where('code' , $request->code)
                );
                $mandats = $mandats->orderBy('date','desc')->get()->unique('code');
                $titre = $titre.$type;
            }elseif($type=="MoneyGram"){
                $type_mandat = "MG" ;
                $mandats = Kyc::where('type_mandat','MG')->whereBetween('date', [$startDate, $endDate]);
                $mandats->when($request->email, fn ($q) =>
                    $q->where('email_agent' , $request->email)
                );
                $mandats->when($request->code, fn ($q) =>
                    $q->where('code' , $request->code)
                );
                $mandats = $mandats->orderBy('date','desc')->get()->unique('code');
                $titre = $titre.$type;
            }elseif($type=="Zepz"){
                $type_mandat = "Zepz" ;
                $mandats = Kyc::where('type_mandat','Zepz')->whereBetween('date', [$startDate, $endDate]);
                $mandats->when($request->email, fn ($q) =>
                    $q->where('email_agent' , $request->email)
                );
                $mandats->when($request->code, fn ($q) =>
                    $q->where('code' , $request->code)
                );
                $mandats = $mandats->orderBy('date','desc')->get()->unique('code');
                $titre = $titre.$type;
            }elseif($type=="EasyTransfer"){
                $type_mandat = "Easy" ;
                $mandats = Kyc::where('type_mandat','Easy')->whereBetween('date', [$startDate, $endDate]);
                $mandats->when($request->email, fn ($q) =>
                    $q->where('email_agent' , $request->email)
                );
                $mandats->when($request->code, fn ($q) =>
                    $q->where('code' , $request->code)
                );
                $mandats = $mandats->orderBy('date','desc')->get()->unique('code');
                $titre = $titre.$type;
            }elseif($type=="EasyTransferMobile"){
                $type_mandat = "EasyTransfer" ;
                $mandats = IdentityCheck::where('type_mandat','EasyTransfer')->whereBetween('date', [$startDate, $endDate]);
                $mandats->when($request->email, fn ($q) =>
                    $q->where('email_agent' , $request->email)
                );
                $mandats->when($request->code, fn ($q) =>
                    $q->where('code' , $request->code)
                );
                $mandats = $mandats->orderBy('date','desc')->get()->unique('code');
                $titre = $titre.$type;
            }elseif($type=="Worldremit"){
                $type_mandat = "Worldremit" ;
                $mandats = IdentityCheck::where('type_mandat','Worldremit')->whereBetween('date', [$startDate, $endDate]);
                $mandats->when($request->email, fn ($q) =>
                    $q->where('email_agent' , $request->email)
                );
                $mandats->when($request->code, fn ($q) =>
                    $q->where('code' , $request->code)
                );
                $mandats = $mandats->orderBy('date','desc')->get()->unique('code');
                $titre = $titre.$type;
            }elseif($type=="TapTapSend"){
                $type_mandat = "TapTapSend" ;
                $mandats = IdentityCheck::where('type_mandat','TapTapSend')->whereBetween('date', [$startDate, $endDate]);
                $mandats->when($request->email, fn ($q) =>
                    $q->where('email_agent' , $request->email)
                );
                $mandats->when($request->code, fn ($q) =>
                    $q->where('code' , $request->code)
                );
                $mandats = $mandats->orderBy('date','desc')->get()->unique('code');
                $titre = $titre.$type;
            }else{
                return view('errors.404');
            }
            $type = $type_mandat;
            Log::channel('info')->info("Acces Liste des mandats ".$type, ['user' => Auth::user()->name] );
            return view('admin.mandats.index',compact('titre', 'mandats' , 'type', 'url','endDate','startDate'));
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
            $type = 'Mandat nationale';
            Log::channel('info')->info("Acces Liste des mandats National ", ['user' => Auth::user()->name] );
            return view('admin.mandats.index',compact('titre', 'mandats','type'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function import(Request $request){
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

        if(in_array($id[0],["T","W","E","M"]) && $id[1] != "E"){
            $mandat = IdentityCheck::where('code', $id)->first();
            $type = "mobile";
        }else{

            $mandat = Kyc::where('code', $id)->first();
            $type = "Web" ;
        }
        $latestSuspect = $mandat->SuspectMandatStatus();
        $titre = "Mandat : ".$id;
        return view ('admin.mandats.show', compact('titre','mandat', 'latestSuspect','type'));
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
            Log::channel('error')->error($th->getMessage(), ['line' => $th->getLine()]);
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    public function ajaxListeMandat(Request $request){
        try {
            if($request->type  == "All"){
                $westerns = Kyc::select(DB::raw('*'));
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
            }elseif(in_array($request->type,["WU","RIA","MG","Zepz","Easy"])){
                //$mandats = Western::with('getStatus')->select(DB::raw('*'))->where('type_mandat',$request->type);
                $mandats = Kyc::select(DB::raw('*'))->where('type_mandat',$request->type);

                if ($request->code) {
                    $mandats =  $mandats->where("code",'like','%'.$request->code.'%');
                }
                if($request->input_identity){
                    $mandats =  $mandats->where("input_identity",'like','%'.$request->input_identity.'%');
                }
                $mandats = $mandats->orderBy('date','desc')->get()->unique('code');
            }else{
                //$mandats = IdentityCheck::with('getStatus')->where('type_mandat',$request->type);
                $mandats = IdentityCheck::where('type_mandat',$request->type);

                if ($request->code) {
                    $mandats = $mandats->where("code",'like','%'.$request->code.'%');
                }
                if($request->input_identity){
                    $mandats = $mandats->where("input_identity",'like','%'.$request->input_identity.'%');
                }
                $mandats = $mandats->orderBy('date','desc')->get()->unique('code');
            }

            //return response()->json(["data" => $mandats]);

            return view('admin.mandats.layoutAjax',compact('mandats'));
        } catch (\Throwable $th) {
            Log::channel('error')->error($th->getMessage(), ['user' => Auth::user()->name,'line' => $th->getLine()]);
            return response()->json($th->getMessage(), 400);
        }

    }

    public function detailAgentMandats(){
        $type = "all";
        $titre = "all";
        $mandats = Kyc::where('email_agent', 'contact.technologies@gmail.com')->where('id','>','246190')->orderBy('date','desc')->get();
        return view('admin.mandats.indexAll',compact('titre', 'mandats' , 'type'));

    }


    public function getWestern(){
        try {
            $mandats = Kyc::whereNotIn('type_mandat',['MG','RIA','Easy','SW','Zepz'])->orderBy('date','desc')->get()->unique('code')->take(100);
            return response()->json($mandats, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 400);
        }
    }


    public function gerenewtable(){

        $westerns = EasyTransfert::where('id','>',1)->limit(3000)->get() ;
        try {
            dd($westerns[0]);
            foreach($westerns as $mandat){
                //$array = $mandat->toArray();
                $MandatsCentral = new MandatsCentral();
                $MandatsCentral->code_mandat = $mandat->code;
                $MandatsCentral->type_mandat = "MET";
                $MandatsCentral->montant = $mandat->amount;

                $MandatsCentral->commission_agent = $mandat->commission_agent ;
                $MandatsCentral->statut_kyc = $mandat->getStatusKYC($mandat->code) ? "true" : "false";
                //$MandatsCentral->identite_beneficiaire = "" ;
                $MandatsCentral->email_agent = $mandat->agent  ;
                $MandatsCentral->date_mandat = $mandat->Date ;
                $MandatsCentral->statut_remboursement = $mandat->rembourse;
                $MandatsCentral->type_remboursement = $mandat->type_remboursement;
                $MandatsCentral->Partner_ID = $mandat->Partner_ID;
                $MandatsCentral->agent = $mandat->Agent;
                $MandatsCentral->save();
            }
            dd($westerns);
        } catch (\Throwable $th) {
            dump($th->getMessage());
            dd($westerns);
        }
    }


     public function getImage(Request $request){
        try {

            if($request->type == "Web"){
                $array = $this->getImageWeb($request->date);
                $filename = Str::after($request->filename, 'imagesKYC/');
                if($array['Status'] == "Success" ){
                    if (!Storage::disk(@$array['sftp'])->exists(@$array['folder'].$filename)) {
                        return redirect()->back()->with( ['status' => 'error' , 'message' => "File not found"] );
                    }
                    // Récupère le contenu du fichier depuis le serveur SFTP
                    $fileContent = Storage::disk(@$array['sftp'])->get(@$array['folder'].$filename);

                    // Retourne le fichier en tant que réponse avec un téléchargement
                    return Response::make($fileContent, 200, [
                        'Content-Type' => 'image/jpeg', // Le type MIME de l'image (à ajuster selon le format de l'image)
                        'Content-Disposition' => 'attachment; filename="' . basename($filename) . '"',
                    ]);
                }
            }else{

                $array = $this->getImageMobile($request->date);
                $filename = Str::after($request->filename, 'images/');
                if($array['Status'] == "Success" ){
                    if (!Storage::disk(@$array['sftp'])->exists(@$array['folder'].$filename)) {
                        return redirect()->back()->with( ['status' => 'error' , 'message' => "File not found"] );
                    }
                    // Récupère le contenu du fichier depuis le serveur SFTP
                    $fileContent = Storage::disk(@$array['sftp'])->get(@$array['folder'].$filename);

                    // Retourne le fichier en tant que réponse avec un téléchargement
                    return Response::make($fileContent, 200, [
                        'Content-Type' => 'image/jpeg', // Le type MIME de l'image (à ajuster selon le format de l'image)
                        'Content-Disposition' => 'attachment; filename="' . basename($filename) . '"',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }


    private function getImageWeb($date){

        $date = Carbon::parse($date);

        $limit1 = Carbon::parse('2024-07-01');
        $limit2 = Carbon::parse('2025-03-01');
        $now = Carbon::now();

        if ($date->isSameMonth($now)) {
            $sftp = 'sftp_current_web';
            $folder = "";
        } elseif ($date->greaterThanOrEqualTo($limit2)) {
            // cas 1 : date >= 2025-03-01
            $sftp = 'sftp_old_web';
            $folder = $date->format('Y-m').'/';
        } elseif ($date->between($limit1, $limit2)) {
            // cas 2 : entre 2024-07-01 et 2025-02-28
            $sftp = 'sftp_old_web';
            $folder = "imagesKYC/";
        } elseif ($date->lessThan($limit1)) {
            // cas 3 : < 2024-07-01
            $sftp = 'sftp_old_web';
            $folder = "2024-mois01_06/imagesKYC/";
        } else {
            return [
                'Status'=>'error'
            ];
        }

        return [
                'Status'=>'Success',
                'sftp' => $sftp ,
                'folder' => $folder ,
            ];
    }


    private function getImageMobile($date){

        $date = Carbon::parse($date);
        $limit1 = Carbon::parse('2025-03-01');
        $now = Carbon::now();

        if ($date->isSameMonth($now)) {
            $sftp = 'sftp_current_mobile';
            $folder = "";
        } elseif ($date->greaterThanOrEqualTo($limit1)) {
            // cas 1 : date >= 2025-03-01
            $sftp = 'sftp_old_mobile';
            $folder = $date->format('Y-m').'/';
        } elseif ($date->lessThan($limit1)) {
            // cas 3 : < 2024-07-01
            $sftp = 'sftp_old_mobile';
            $folder = "images/";
        } else {
            return [
                'Status'=>'error'
            ];
        }

        return [
                'Status'=>'Success',
                'sftp' => $sftp ,
                'folder' => $folder ,
            ];
    }

    public function getImageListe($value){
            $request = Kyc::where('code', $value)->first();
            if("Web" == "Web"){
                $array = $this->getImageWeb($request->date);
                $filename = Str::after($request->image_cin, 'imagesKYC/');
                if($array['Status'] == "Success" ){
                    if (!Storage::disk(@$array['sftp'])->exists(@$array['folder'].$filename)) {
                        return redirect()->back()->with( ['status' => 'error' , 'message' => "File not found"] );
                    }
                    // Récupère le contenu du fichier depuis le serveur SFTP
                    $fileContent = Storage::disk(@$array['sftp'])->get(@$array['folder'].$filename);

                    // Retourne le fichier en tant que réponse avec un téléchargement
                    return Response::make($fileContent, 200, [
                        'Content-Type' => 'image/jpeg', // Le type MIME de l'image (à ajuster selon le format de l'image)
                        'Content-Disposition' => 'attachment; filename="' . basename($filename) . '"',
                    ]);
                }
            }


    }

}


