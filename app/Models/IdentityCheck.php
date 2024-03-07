<?php

namespace App\Models;

use App\Models\SuspectMandat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IdentityCheck extends Model
{
    use HasFactory;

    protected $table = 'identity_check';
    protected $connection = 'DB_Mandat';

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
        return $this::where('code',$this->code)->orderBy('id','desc')->get()->toArray();
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
            Log::channel('error')->error($e->getMessage(), ['model'=>'IdentityCheck' ,'Ligne'=> $e->getLine()]);
        }
    }

    public function getStatus(){
        $latestSuspect = SuspectMandat::where('code',$this->code)->orderBy('id','desc')->first();
        return $latestSuspect;
    }

    public function getStatusListe(){
        $latestSuspect = SuspectMandat::where('code',$this->code)->orderBy('id','desc')->get();
        return $latestSuspect;
    }

}
