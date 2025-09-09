<?php

namespace App\Models;

use App\Models\Zepz;
use App\Models\Western;
use App\Models\RiaMoney;
use App\Models\MoneyGram;
use App\Models\EasyTransfert;
use App\Models\SuspectMandat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kyc extends Model
{
    use HasFactory;
    protected $connection = 'DB_Western';
    protected $fillable = ['Prenom', 'Nom' , 'date', 'CIN','Dead'];
    public $timestamps = false;
    protected $table = 'identity_check';

    public function getCSS($statut){
        if($statut == 0){
            return ["class"=>"warning" , "text"=>"En cours"];
        }
        if($statut == 1){
            return ["class"=>"secondary" , "text"=>"Bloqué"];
        }
        if($statut == 2){
            return ["class"=>"danger" , "text"=>"Bloqué Définitivement"];
        }
        return ["class"=>"success" , "text"=>"Remboursé"];
    }

    public function suspect(){
        if (strtoupper($this->blacklisted) == "TRUE"){
            return true ;
        }
        if (strtoupper($this->CTAF) == "TRUE"){
            return true ;
        }
        if (strtoupper($this->interne) == "TRUE"){
            return true ;
        }
        return false;
    }

    public function iteration(){
        return $this::where('code',$this->code)->get('code')->count();
    }

    public function listeIteration(){
        $listeIteration =  $this::where('code',$this->code)->orderBy('id','desc')->get()->toArray();
        return $listeIteration;
    }

    public function latestSuspect(){
        $latestSuspect = SuspectMandat::where('code',$this->code)->orderBy('id','desc')->first();
        return $latestSuspect;
    }

    public function storeSuspect(){
        try {
            $latestSuspect = SuspectMandat::where('code',$this->code)->orderBy('id','desc')->first();
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
            $Suspect->code = $this->code ;
            $Suspect->nb_iteration = $nb_iteration ;
            $Suspect->date_modif = date('Y-m-d') ;
            $Suspect->statut = $statut ;
            $Suspect->input_identity = $this->input_identity ;
            $Suspect->output_identity = $this->output_identity ;
            $Suspect->save();
        } catch (\Throwable $e) {
            Log::channel('error')->error($e->getMessage(), ['model'=>'Western' ,'Ligne'=> $e->getLine()]);
        }
    }

    public function SuspectMandatStatus(){
        $latestSuspect = SuspectMandat::where('code',$this->code)->orderBy('id','desc')->first();
        return $latestSuspect;
    }

    public function getStatus(){
        return $this->hasOne(SuspectMandat::class, 'code', 'code')->latestOfMany(); // Laravel 8+
    }

    public function getStatusListe(){
        $latestSuspect = SuspectMandat::where('code',$this->code)->orderBy('id','desc')->get();
        return $latestSuspect;
    }

    public function getAllPictures(){
        $mandats = IdentityCheck::where('code', $this->code)->pluck("image_cin");
        return $mandats;
     }

    public function getRIAdetail(){
        $response = Http::asJson()->get('10.90.90.98:8089/api/v1/riaMoneyTransfer/detailsByPin/'.$this->code);
        if($response->successful()){
            $mandat = $response->json();
            if(count($mandat)>1){
                return $mandat[count($mandat)-1];
            }
            return $mandat;
        }else{
            return null ;
        }
    }

    public function getMGdetail(){
        $response = Http::asJson()->get('10.90.90.98:8089/api/v1/mG/getByRefNumb/'.$this->code);
        if($response->successful()){
            $mandat = $response->json();
            if(count($mandat)>1){
                return $mandat[count($mandat)-1];
            }
            return $mandat;
        }else{
            return null ;
        }
    }

    public function getAmount(){

        if($this->type_mandat == "WU"){
            $mandat = Western::where('MTCN',$this->code)->first();
            if($mandat){
                $mandat = $mandat->toArray();
                return number_format(($mandat["Montant TND"]),3,'.','') ." TND";
            }else{
                return "none";
            }
        }elseif($this->type_mandat == "RIA"){
            $mandat = RiaMoney::select(DB::raw('bene_amount'))->where('pin',$this->code)->first();
            if($mandat){
                return number_format(($mandat->bene_amount),3,'.','') ." TND";
            }else{
                return "none";
            }
        }elseif($this->type_mandat == "Easy"){
            $mandat = EasyTransfert::select(DB::raw('amount'))->where('code',$this->code)->first();
            if($mandat){
                return number_format(($mandat->amount),3,'.','') ." TND";
            }else{
                return "none";
            }
        }elseif($this->type_mandat == "MG"){
            $mandat = MoneyGram::select(DB::raw('agent_check_amount'))->where('reference_number',$this->code)->first();
            if($mandat){
                return number_format(($mandat->agent_check_amount),3,'.','') ." TND";
            }else{
                return "none";
            }
        }elseif($this->type_mandat == "WR"){
            $mandat = Zepz::select(DB::raw('Montant'))->where('code',$this->code)->first();
            if($mandat){
                return number_format(($mandat->Montant),3,'.','') ." TND";
            }else{
                return "none";
            }
        }elseif($this->type_mandat == "Zepz"){
            $mandat = Zepz::select(DB::raw('Montant'))->where('code',$this->code)->first();
            if($mandat){
                return number_format(($mandat->Montant),3,'.','') ." TND";
            }else{
                return "none";
            }
        }elseif($this->type_mandat == "SW"){
            $mandat = Zepz::select(DB::raw('Montant'))->where('code',$this->code)->first();
            $mandat = Zepz::where('code',$this->code)->first();
            if($mandat){
                return number_format(($mandat->Montant),3,'.','') ." TND";
            }else{
                return "none";
            }
        }elseif($this->type_mandat == "WR"){
            $mandat = Zepz::select(DB::raw('Montant'))->where('code',$this->code)->first();
            dump($mandat);
            if($mandat){
                return number_format(($mandat->Montant),3,'.','') ." TND";
            }else{
                return "none";
            }
        }

    }
}
