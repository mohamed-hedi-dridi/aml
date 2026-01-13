<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kyc;
use App\Models\Zepz;
use App\Models\Risque;
use App\Models\Setting;
use App\Models\Western;
use App\Models\RiaMoney;
use App\Models\MoneyGram;
use App\Models\TypeMandat;
use App\Models\MenuSideBar;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EasyTransfert;
use App\Models\IdentityCheck;
use App\Models\SuspectMandat;
use App\Imports\ImportMandats;
use App\Models\Customer;
use App\Models\MandatsCentral;
use App\Models\MoneyGramDetails;
use App\Models\DetailsLookupResp;
use Rinvex\Country\CountryLoader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Intl\Countries;
use App\Models\MoneyGramValidationReq;
use League\Flysystem\UnableToReadFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

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
                return view('admin.mandats.layoutAjaxMN',compact('mandats'));
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





    public function getImage(Request $request){
        try {
            if($request->type == "Web"){
                $array = $this->getImageWeb($request->date);
                $filename = Str::after($request->filename, 'imagesKYC/');
                $filename = Str::after($request->filename, 'imagesKYCtest/');
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
                Log::channel('info')->info("Demande Image ".$filename." au ".$array, ['user' => Auth::user()->name] );
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

    public function gerenewtable(){
        $this->generateMG();
        //$this->generateWestern();
        //$this->generateZepz();
        //$this->generateRia();
        //$this->generateMET();
    }


    public function generateWestern(){
        try {
            DB::connection('mysql')->beginTransaction();
            $id =  Setting::where('key', 'id_western')->first()->value;
            $westerns = Western::where('id', '>', $id)->get();
            Log::channel('info')->info("count WU  ".count($westerns));
            foreach($westerns as $mandat){
                $array = $mandat->toArray();
                $kyc = Kyc::where([
                    'code'=>$array["MTCN"],
                    'type_mandat'=>"WU"])->orderBy('id','asc')->first();
                $code = $this->fixCountryName($array["Pays"]) ;
                $MandatsCentral = new MandatsCentral();
                $MandatsCentral->code_mandat = $array["MTCN"];
                $MandatsCentral->type_mandat = "WU";
                $MandatsCentral->montant = $array["Montant TND"];
                $MandatsCentral->sender_country = @$code["iso_3166_1_alpha2"] ;
                $MandatsCentral->commission_agent = $array["commission_agent"] ;
                $MandatsCentral->statut_kyc = $kyc!= null ? "true" : "false";
                if($kyc!= null){
                    $MandatsCentral->identite_beneficiaire = $kyc->output_identity ;
                }else {
                    $MandatsCentral->identite_beneficiaire = $array["Receiver_identity"] ;
                }
                $MandatsCentral->date_mandat = $array["Journee_WU"] ;
                $MandatsCentral->statut_remboursement = $array["rembourse"];
                $MandatsCentral->type_remboursement = $array["type_remboursement"];
                $MandatsCentral->Partner_ID = "";
                $MandatsCentral->email_agent = $array["email_agent"]  ;
                $MandatsCentral->agent = $array["Agent"];
                $MandatsCentral->save();

                $exist = Customer::where('identity', $array["Receiver_identity"])->exists();
                if (!$exist) {
                    Customer::create([
                        'identity' => $array["Receiver_identity"],
                        'name' =>  $array["Receiver"],
                        'nationalite' => @$code["iso_3166_1_alpha2"] ,//$western->bene_country,
                    ]);
                }
            }
            Setting::where('key', 'id_western')->update(['value' => $array["id"]]);
            Log::channel('info')->info(__FUNCTION__ ." OLD Id = ".$id . " New ID = ".$mandat->id);
            DB::connection('mysql')->commit();
        } catch (\Throwable $e) {
            Log::channel('error')->error($e->getMessage(), ['line' => $e->getLine()]);
        }

    }

    public function generateZepz(){
        try {
            DB::connection('mysql')->beginTransaction();
            $id =  Setting::where('key', 'id_zepz')->first()->value;
            $zepes = Zepz::where('id','>', $id)->get();
            if(count($zepes)>0){
                foreach($zepes as $mandat){
                    $kyc = Kyc::where('code',$mandat->code)->orderBy('id','asc')->first();
                    $identite_beneficiaire = $kyc?->input_identity ?? null;  
                    $MandatsCentral = new MandatsCentral();
                    $MandatsCentral->code_mandat = $mandat->code;
                    $MandatsCentral->type_mandat = $mandat->type_zepz;
                    $MandatsCentral->montant = $mandat->Montant;
                    $MandatsCentral->commission_agent = $mandat->commission_agent ;

                    $MandatsCentral->statut_kyc = $kyc!= null ? "true" : "false";
                    $MandatsCentral->identite_beneficiaire = $identite_beneficiaire ;

                    $MandatsCentral->date_mandat = $mandat->Date ;
                    $MandatsCentral->statut_remboursement = $mandat->rembourse;
                    $MandatsCentral->type_remboursement = $mandat->type_remboursement;
                    $MandatsCentral->Partner_ID = $mandat->Partner_ID;
                    $MandatsCentral->email_agent = $mandat->email_agent ;
                    $MandatsCentral->agent = $mandat->Agent;
                    $MandatsCentral->sender_identity = $mandat->Sender_ID ;
                    $MandatsCentral->sender_country = $mandat->Sender_Country ;
                    $MandatsCentral->bene_country = $mandat->Recipient_Country ;
                    $MandatsCentral->save();
                    if($identite_beneficiaire != null){
                        $exist = Customer::where('identity', $identite_beneficiaire)->exists();
                        if (!$exist) {
                            Customer::create([
                                'identity' => $identite_beneficiaire,
                                'name' =>  $mandat->Recipient_Name,
                                'nationalite' => $mandat->Recipient_Country ,//$western->bene_country,
                            ]);
                        }
                    }  
                }
                Setting::where('key', 'id_zepz')->update(['value' => $mandat->id]);
                Log::channel('info')->info(__FUNCTION__ ." OLD Id = ".$id . " New ID = ".$mandat->id);
                DB::connection('mysql')->commit();
            }
        } catch (\Throwable $e) {
            Log::channel('error')->error(__FUNCTION__." ".$e->getMessage(), ['line' => $e->getLine()]);
            DB::connection('mysql')->rollBack();
        }

    }

    public function generateRia(){
        try {
            DB::connection('mysql')->beginTransaction();
            $id =  Setting::where('key', 'id_ria')->first()->value;
            $RiaMoney = RiaMoney::where('id','>', $id)->where("status" , "Paid")->get();
            if(count($RiaMoney)>0){
                foreach($RiaMoney as $mandat){
                    $id = $mandat->id;
                    $MandatsCentral = new MandatsCentral();
                    $MandatsCentral->code_mandat = $mandat->pin;
                    $MandatsCentral->type_mandat = "RIA";
                    $MandatsCentral->montant = $mandat->bene_amount;
                    $MandatsCentral->identite_beneficiaire = $mandat->beneidnumber ;
                    //$MandatsCentral->commission_agent = $mandat->beneidnumber*0.005;
                    $MandatsCentral->statut_kyc = "true";
                    $MandatsCentral->date_mandat = $mandat->date_time_local ;
                    $MandatsCentral->statut_remboursement = 1;
                    $MandatsCentral->Partner_ID = "";
                    $MandatsCentral->email_agent = $mandat->agent ;
                    $MandatsCentral->agent = $mandat->agent;
                    $MandatsCentral->sender_identity = $mandat->custid1no ;
                    $MandatsCentral->sender_country = $mandat->cust_country ;
                    $MandatsCentral->bene_country = $mandat->bene_country ;
                    $MandatsCentral->save();

                    $exist = Customer::where('identity', $mandat->beneidnumber)->exists();
                if (!$exist) {
                    Customer::create([
                        'identity' => $mandat->beneidnumber,
                        'name' =>  $mandat->bene_name_first." ".$mandat->bene_name_middle." ".$mandat->bene_name_last1." ".$mandat->bene_name_last2, 
                        'nationalite' => $mandat->bene_country ,
                    ]);
                }
                }
                Setting::where('key', 'id_ria')->update(['value' => $mandat->id]);
                Log::channel('info')->info(__FUNCTION__ ." OLD Id = ".$id . " New ID = ".$mandat->id);
                DB::connection('mysql')->commit();
            }
        } catch (\Throwable $e) {
            Log::channel('error')->error(__FUNCTION__." ".$e->getMessage(), ['line' => $e->getLine()]);
            DB::connection('mysql')->rollBack();
        }

    }

    public function generateMG(){
        try {
            DB::connection('mysql')->beginTransaction();
            $id =  Setting::where('key', 'id_MG')->first()->value;
            $MoneyGram = MoneyGram::whereHas('details', function ($query) {
                            $query->where('status', 'Paid');
                        })->where('id','>', $id)->get();
            if(count($MoneyGram)>0){
                foreach($MoneyGram as $mandat){
                    $kyc = Kyc::where('code',$mandat->reference_number)->orderBy('id','asc')->first();
                    //$kyc = $mandat->getStatusKYC($mandat->reference_number) ;
                    $MandatsCentral = new MandatsCentral();
                    $MandatsCentral->code_mandat = $mandat->reference_number;
                    $MandatsCentral->type_mandat = "MG";
                    $MandatsCentral->montant = $mandat->agent_check_amount;
                    $MandatsCentral-> $kyc!= null ? $kyc->input_identity : null ;
                    $MandatsCentral->identite_beneficiaire = $mandat->details->reciveValidation->receiver_photo_id_number ;
                    //$MandatsCentral->commission_agent = $mandat->beneidnumber*0.005;
                    $MandatsCentral->statut_kyc = "true";
                    $MandatsCentral->date_mandat = $mandat->expected_date_of_delivery ;
                    $MandatsCentral->statut_remboursement = 1;
                    $MandatsCentral->Partner_ID = "";
                    $MandatsCentral->email_agent = $mandat->agent ;
                    $MandatsCentral->agent = $mandat->agent;

                    $MoneyGramDetails = MoneyGramDetails::where(["ref_numb"=>$mandat->reference_number , "status" =>"Paid"])->first();
                    $receive_validation_req = MoneyGramValidationReq::find($MoneyGramDetails->receive_validation_req_id);
                    $details_lookup_resp = DetailsLookupResp::find($MoneyGramDetails->details_lookup_resp_id);
                    $bene_country= $this->fixCountryalfa3By2($receive_validation_req->receiver_birth_country);
                    $sender_country= $this->fixCountryalfa3By2($details_lookup_resp->sender_country);

                    $MandatsCentral->sender_identity = $details_lookup_resp->sender_photo_id_number ;
                    $MandatsCentral->sender_country = @$sender_country["iso_3166_1_alpha2"] ;
                    $MandatsCentral->bene_country =@$bene_country["iso_3166_1_alpha2"];

                    $MandatsCentral->save();
                    $exist = Customer::where('identity', $mandat->details->reciveValidation->receiver_photo_id_number)->exists();
                    if (!$exist) {
                        Customer::create([
                            'identity' => $mandat->details->reciveValidation->receiver_photo_id_number,
                            'name' =>  $details_lookup_resp->receiver_first_name." ".$details_lookup_resp->receiver_middle_name." ".$details_lookup_resp->receiver_last_name,
                            'nationalite' => @$bene_country["iso_3166_1_alpha2"] ,
                        ]);
                    }
                    Setting::where('key', 'id_MG')->update(['value' => $mandat->id]);
                }
                Setting::where('key', 'id_MG')->update(['value' => $mandat->id]);
                Log::channel('info')->info(__FUNCTION__ ." OLD Id = ".$id . " New ID = ".$mandat->id);
                DB::connection('mysql')->commit();
            }
        }  catch (\Throwable $e) {
            Log::channel('error')->error(__FUNCTION__." "." ". __CLASS__ ." ".$e->getMessage(), ['line' => $e->getLine()]);
            DB::connection('mysql')->rollBack();
        }
    }

    public function generateMET(){
        try {
            DB::connection('mysql')->beginTransaction();
            $id =  Setting::where('key', 'id_MET')->first()->value;
            $EasyTransfert = EasyTransfert::where('id','>', $id)->where("status" , "Paid")->get();
            if(count($EasyTransfert)>0){
                foreach($EasyTransfert as $mandat){
                    $kyc = $mandat->getStatusKYC($mandat->code) ;
                    $identite_beneficiaire = $kyc?->input_identity ?? null;
                    $id = $mandat->id;
                    $MandatsCentral = new MandatsCentral();
                    $MandatsCentral->code_mandat = $mandat->code;
                    $MandatsCentral->type_mandat = "MET";
                    $MandatsCentral->montant = $mandat->amount;
                    $MandatsCentral->identite_beneficiaire = $identite_beneficiaire ;
                    //$MandatsCentral->commission_agent = $mandat->beneidnumber*0.005;
                    $MandatsCentral->statut_kyc = "true";
                    $MandatsCentral->date_mandat = $mandat->transaction_date ;
                    $MandatsCentral->statut_remboursement = 1;
                    $MandatsCentral->Partner_ID = "";
                    $MandatsCentral->email_agent = $mandat->agent ;
                    $MandatsCentral->agent = $mandat->agent;
                    $MandatsCentral->sender_identity = $mandat->Sender_ID ;
                    $MandatsCentral->sender_country = $mandat->Sender_Country ;
                    $MandatsCentral->bene_country = $mandat->Recipient_Country ;
                    $code= $this->fixCountryalfa3By2($mandat->sender_country);
                    $MandatsCentral->sender_country = @$code["iso_3166_1_alpha2"] ;
                    $MandatsCentral->save();

                    $exist = Customer::where('identity', $identite_beneficiaire)->exists();
                    if (!$exist) {
                        Customer::create([
                            'identity' => $identite_beneficiaire,
                            'name' =>  $mandat->beneficiary_first_name." ".$mandat->beneficiary_last_name,
                            'nationalite' => @$code["iso_3166_1_alpha2"] ,
                        ]);
                    }


                }
                Setting::where('key', 'id_MET')->update(['value' => $mandat->id]);
                Log::channel('info')->info(__FUNCTION__ ." OLD Id = ".$id . " New ID = ".$mandat->id);
                DB::connection('mysql')->commit();
            }
        } catch (\Throwable $e) {
            Log::channel('error')->error(__FUNCTION__." ".$e->getMessage(), ['line' => $e->getLine()]);
            DB::connection('mysql')->rollBack();
        }

    }

    public function verifidentity(){
        $MandatsCentral = MandatsCentral::where('id', '>' ,28861)->where("statut_kyc",'false')->where('identite_beneficiaire',null)->where('date_mandat', '>','2024-12-31')->limit(5000)->get();
        foreach ($MandatsCentral as $key => $mandat) {
            $latestSuspect = Kyc::where('code',$mandat->code_mandat)->orderBy('id','asc')->first();
            $mandat->identite_beneficiaire = @$latestSuspect->output_identity ;
            $mandat->save();
        }
    }


    /*
        public function getListeImage(){
            $i = 0 ;
            $array = [
                    'Status'=>'Success',
                    'sftp' => "sftp_old_web" ,
                    'folder' => "2025-05/listeimage/" ,
                ];
            $images = KYC::where('email_agent', 'shilihichem1@gmail.com')
                    ->where('date', '>=', '2025-05-01')
                    ->where('date', '<', '2025-06-01')
                    ->pluck('image_cin');
            foreach($images as $image){
                $filename = Str::after($image, 'imagesKYC/');
                if (!Storage::disk(@$array['sftp'])->exists(@$array['folder'].$filename)) {
                    $i++;
                    dump($filename);
                }
            }
            dd($i);
        }
    */

    /*
        -- la function calcul risque 
        request : [identity_bene , montant , pays_bene pays_sender]
    */
    public function calculRisque(Request $request){
        $id = $request->identity_bene;//'14224581'; // entré
        $montant  = $request->montant ; // entré
        $pays_bene = $request->pays_bene; // entré
        $pays_sender = $request->pays_sender; // entré
        $taux = 0 ;

        $gaffiCountries = [
            'AO', 'CI', 'DZ', 'KE', 'LA', 'LB', 'MC', 'NA', 'NP', 'VE',
            'BF', 'BG', 'CM', 'CD', 'HR', 'HT', 'IR', 'ML', 'MM', 'MZ',
            'NG', 'PH', 'KP', 'SD', 'SY', 'TZ', 'VN', 'YE', 'ZA',
        ];

        $africanCountries = [
            'DZ', 'AO', 'BJ', 'BW', 'BF', 'BI', 'CV', 'CM', 'KM', 'CG',
            'CD', 'CI', 'DJ', 'EG', 'ER', 'SZ', 'ET', 'GA', 'GM', 'GH',
            'GN', 'GW', 'GQ', 'KE', 'LS', 'LR', 'LY', 'MG', 'MW', 'ML',
            'MA', 'MU', 'MR', 'MZ', 'NA', 'NE', 'NG', 'UG', 'CF', 'RW',
            'ST', 'SN', 'SC', 'SL', 'SO', 'SD', 'SS', 'TZ', 'TD', 'TG',
            'TN', 'ZM', 'ZW'
        ];
        //$start = Carbon::now()->subMonth()->startOfMonth();
        //$end = Carbon::now()->subMonth()->endOfMonth();
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $risques = Risque::where('statut',true)->get();
        foreach ($risques as $key => $value) {

            $lastMounth = MandatsCentral::whereBetween('date_mandat', [$start, $end])
                            ->where('identite_beneficiaire',$id)
                            ->selectRaw('COUNT(*) as count, SUM(montant) as total_montant')
                            ->first();

            $arrayCount = MandatsCentral::where('identite_beneficiaire', $id)
                    ->selectRaw("COUNT(DISTINCT CASE WHEN sender_country IS NOT NULL THEN 1 END) as sender_country_count,
                        COUNT(DISTINCT CASE WHEN sender_identity IS NOT NULL THEN 1 END) as sender_identity_count")
                    ->first();
            $volumeLast3Months = MandatsCentral::whereBetween('date_mandat', [
                now()->subMonths(3)->startOfMonth() ,
                now()->subMonths(1)->endOfMonth()
            ])
            ->where('identite_beneficiaire',$id)
            ->sum('montant');

            if($value->variable == "R_Type"){
                $count = MandatsCentral::where('identite_beneficiaire',$id)->count();
                if($count < 2 ){
                    $taux = $taux+ $value->ponderation ;
                }
            }

            if($value->variable == "R_volume"){
                if($lastMounth->total_montant > 20000 ){
                    $taux = $taux+ $value->ponderation ;
                }
            }

            if($value->variable == "R_freqence"){
                if($lastMounth->count > 5 ){
                    $taux = $taux+ $value->ponderation ;
                }
            }

            if($value->variable == "R_Pays"){
                if (in_array($pays_bene, $africanCountries)) {
                    $taux += $value->ponderation;
                }
            }

            if($value->variable == "R_montant"){
                if($lastMounth->total_montant > 5000 ){
                    $taux = $taux+ $value->ponderation ;
                }
            }

            if($value->variable == "R_Pays_origine"){
                if (in_array($pays_sender, $gaffiCountries)) {
                    $taux += $value->ponderation;
                }
            }

            if($value->variable == "R_Structuration"){
                $count = MandatsCentral::where('identite_beneficiaire',$id)->where("montant",$montant)->count();
                if(@$count->total_montant > 5 ){
                    $taux = $taux+ $value->ponderation ;
                }
            }

            if($value->variable == "Variation"){
                if(($volumeLast3Months > 0) && (($volumeLast3Months/3) >= ($lastMounth->total_montant * 3)) ){
                    $taux = $taux+ $value->ponderation ;
                }
            }
            if($value->variable == "Emetteurs"){
                if(@$arrayCount->sender_identity_count >3 ){
                    $taux += $value->ponderation;
                }
            }

            if($value->variable == "Pays_diff"){

                if(@$arrayCount->sender_country_count > 2 ){
                    $taux += $value->ponderation;
                }

            }
            if($value->variable == "Succ"){
                if($lastMounth->count > 5 || $lastMounth->total_montant > 20000 ){
                    $taux += $value->ponderation;
                }
            }
        }
        return return response()->json(["taux" =>$taux], 200);

    }


    public function updateMandatCentrals(){


            /*$MandatsRIA = MandatsCentral::where([
                'type_mandat'=>"RIA" ])->get();
                Log::channel('info')->info("count RIA ");
                $i = 0 ;
            foreach ($MandatsRIA as $ria) {
                $RiaMoney = RiaMoney::where('pin', $ria->code_mandat)->where("status" , "Paid")->first();
                /*$ria->sender_identity = $RiaMoney->custid1no ;
                $ria->sender_country = $RiaMoney->cust_country ;
                $ria->bene_country = $RiaMoney->bene_country ;
                $ria->save();
                if($RiaMoney->beneidnumber != $ria->identite_beneficiaire ){
                    $i++ ;
                   dump(["central"=> $ria->identite_beneficiaire , "ria"=>$RiaMoney->beneidnumber , "code"=>$ria->code_mandat ] );
                }
            }
            dd($ria , $i) ;*/


            /*$MandatsWU = MandatsCentral::where([
                'type_mandat'=>"WU" ,
                'identite_beneficiaire' =>null])
            ->where('id' , '>',27089)
            ->limit(13000)->get();
            $countMandatsWU = count($MandatsWU);
                Log::channel('info')->info("count WU ".$countMandatsWU."last Id" . $MandatsWU[$countMandatsWU-1]->id);
                //$countries = countries();
                echo "<table><tr><td>MTCN</td><td>ID Western Kyc </td> <td>ID western </td></tr>" ;
            foreach ($MandatsWU as $wu) {
                $western = Western::where('MTCN', $wu->code_mandat)->first();
                if($wu->identite_beneficiaire != $western->Receiver_identity){
                    $wu->identite_beneficiaire = $western->Receiver_identity ;
                    $wu->save();
                    //echo "<tr><td>".$wu->code_mandat."</td><td>".$wu->identite_beneficiaire." </td><td> ".$western->Receiver_identity ."</td></tr>";
                }
            }*/
            /*
            $MandatsMET = MandatsCentral::where([
                    'type_mandat'=>"MET",
                    'sender_country' => null])
                ->limit(10000)->get();
            foreach ($MandatsMET as $MET) {
                $myEasy = EasyTransfert::where('code', $MET->code_mandat)->first();
                $code= $this->fixCountryalfa3By2($myEasy->sender_country);
                //$MET->sender_identity = $myEasy->Sender_ID ;
                $MET->sender_country = @$code["iso_3166_1_alpha2"] ;
                //$MET->bene_country = $myEasy->bene_country ;
                $MET->save();
            }


            $MandatsMET = MandatsCentral::where([
                    'type_mandat'=>"WR",
                    'sender_country' => null])
                ->limit(10000)->get();
            foreach ($MandatsMET as $MET) {
                $myEasy = Zepz::where('code', $MET->code_mandat)->first();
                $MET->sender_identity = $myEasy->Sender_ID ;
                $MET->sender_country = $myEasy->Sender_Country ;
                $MET->bene_country = $myEasy->Recipient_Country ;
                $MET->save();
            }


            $MandatsMG = MandatsCentral::where([
                    'type_mandat'=>"MG"])->get();
            foreach ($MandatsMG as $MG) {
                $MoneyGram = MoneyGramDetails::where(["ref_numb"=>$MG->code_mandat , "status" =>"Paid"])->first();
                $receive_validation_req = MoneyGramValidationReq::find($MoneyGram->receive_validation_req_id);
                //$details_lookup_resp = DetailsLookupResp::find($MoneyGram->details_lookup_resp_id);
                if($receive_validation_req->receiver_photo_id_number != $MG->identite_beneficiaire ){
                   dump(["central"=> $MG->identite_beneficiaire , "mg"=>$receive_validation_req->receiver_photo_id_number , "code"=>$MG->code_mandat ] );
                }
                $bene_country= $this->fixCountryalfa3By2($receive_validation_req->receiver_birth_country);
                $sender_country= $this->fixCountryalfa3By2($details_lookup_resp->sender_country);
                $MG->sender_identity = $details_lookup_resp->sender_photo_id_number ;
                $MG->sender_country = @$sender_country["iso_3166_1_alpha2"] ;
                $MG->bene_country =@$bene_country["iso_3166_1_alpha2"];
                $MG->save();
            }
        */

            $MandatsMET = MandatsCentral::where([
                    'type_mandat'=>"SW",
                    'identite_beneficiaire' => null])
                //->where('id','>',51949)
                ->limit(10000)->get();
            foreach ($MandatsMET as $MET) {
                $kyc = Kyc::where([
                    'code'=>$MET->code_mandat,
                    'type_mandat'=>"SW"])->orderBy('id','asc')->first();
                $myEasy = Zepz::where('code', $MET->code_mandat)->first();
                //dd($MET->identite_beneficiaire ,  $myEasy->Sender_ID );
                if($kyc!= null){
                    $MET->identite_beneficiaire = $kyc->output_identity ;
                }else {
                    if(strlen($myEasy->Sender_ID)  < 25){
                        $MET->identite_beneficiaire = $myEasy->Sender_ID ;
                    }

                }
                //$MET->identite_beneficiaire = $myEasy->Sender_ID ;
                $MET->save();
            }

    }

    public function generateCustomers(){
        $i = 0 ;
        $listeidentity = MandatsCentral::where('type_mandat', "MET")
            ->whereNotNull('identite_beneficiaire')
            ->distinct('identite_beneficiaire')
            ->get(['identite_beneficiaire','code_mandat']);
        //dd(count($listeidentity));
        foreach($listeidentity as $identity){
            //$western = RiaMoney::where("bene_identity_code", $identity->identite_beneficiaire)->first();
            $western = EasyTransfert::where("code", $identity->code_mandat)->first();
             if (!$western) {
                //continue; // passe à l'identité suivante si aucun Western trouvé
                $i++ ;
                echo ($identity->identite_beneficiaire."<br>");
            }else{
                $code =  null ; //$this->fixCountryName($western->Pays);
                $exist = Customer::where('identity', $identity->identite_beneficiaire)->exists();
                if (!$exist) {
                    Customer::create([
                        'identity' => $identity->identite_beneficiaire,
                        'name' =>  $western->beneficiary_first_name." ".$western->beneficiary_last_name, //$western->bene_name_first." ".$western->bene_name_middle." ".$western->bene_name_last1,
                        'nationalite' => null ,//$western->bene_country,
                    ]);
                }
            }
        }

        dd($i);
    }


    public function fixCountryName($raw)
    {
        $countries = countries();
        if (!$raw) return null;

        // Normalisation propre
        $name = ucwords(strtolower(trim($raw)));

        // Table des corrections
        $map = [
            "United States Of America"           => "United States",
            "Czech Republic Ii"                  => "Czech Republic",
            "Dem Rep Congo"                      => "DR Congo",
            "Korea"                              => "South Korea",
            "Bosnia & Herzegovina"               => "Bosnia and Herzegovina",
            "Malaysia"                           => "Malaysia",
            "Central Africa"                     => "Central African Republic",
            "Congo"                              => "Republic of the Congo",
            "Guinea Bissau"                      => "Guinea-Bissau",
            "Cyprus Northern"                    => "Cyprus",
        ];
        // Retourne correction si existe
        $code = collect($countries)->where("name",$map[$name] ?? $name)->first();
        return $code ;
    }

    public function fixCountryalfa3By2($name)
    {
        $countries = countries();
        if (!$name) return null;

        // Normalisation propre
        //$name = ucwords(strtolower(trim($raw)));

        // Table des corrections
        $map = [
            "United States Of America"           => "United States",
            "Czech Republic Ii"                  => "Czech Republic",
            "Dem Rep Congo"                      => "DR Congo",
            "Korea"                              => "South Korea",
            "Bosnia & Herzegovina"               => "Bosnia and Herzegovina",
            "Malaysia"                           => "Malaysia",
            "Central Africa"                     => "Central African Republic",
            "Congo"                              => "Republic of the Congo",
            "Guinea Bissau"                      => "Guinea-Bissau",
            "Cyprus Northern"                    => "Cyprus",
        ];
        // Retourne correction si existe
        $code = collect($countries)->where("iso_3166_1_alpha3",$map[$name] ?? $name)->first();
        return $code ;
    }



}


