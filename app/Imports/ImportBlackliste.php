<?php

namespace App\Imports;

use App\Models\Blacklist;
use App\Models\BlacklistProd;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportBlackliste implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        DB::connection('mysql')->beginTransaction();
        DB::connection('DB_Prod')->beginTransaction();
        try {
            if(strtolower($rows[0][0])=="prenom" && strtolower($rows[0][1])== "nom" && strtolower($rows[0][2])== "date" && strtolower($rows[0][3])== "cin" && strtolower($rows[0][4])== "dead"){
                $Blacklist = Blacklist::query()->update(['old' => 1]);
                foreach ($rows as $key => $row) {
                    if($key>0){
                        if($this->Verif($row) == true){
                            $dateObj = \DateTime::createFromFormat('d/m/Y', $row[2]);
                            if($dateObj != false){
                                $nouvelleDate = $dateObj->format('d-m-Y');
                            }else{
                                $nouvelleDate = $row[2];
                            }
                            Blacklist::create ([
                                "Prenom"=>$row[0],
                                "Nom"=>$row[1],
                                "date"=>$nouvelleDate,
                                "CIN"=>$row[3],
                                "Dead"=>$row[4]
                            ]);
                            BlacklistProd::create ([
                                "Prenom"=>$row[0],
                                "Nom"=>$row[1],
                                "date"=>$nouvelleDate,
                                "CIN"=>$row[3],
                                "Dead"=>$row[4]
                            ]);
                        }else{
                            DB::connection('mysql')->rollBack();
                            DB::connection('DB_Prod')->rollBack();
                            return 0 ;
                        }
                    }

                }
                DB::connection('mysql')->commit();
                DB::connection('DB_Prod')->commit();
            }
        } catch (\Throwable $th) {
            Log::channel('error')->error($th->getMessage(), ['user' => Auth::user()->name , 'line' => $th->getLine()]);
            DB::connection('mysql')->rollBack();
            DB::connection('DB_Prod')->rollBack();
        }

    }

    private function Verif($row){
        if($row[0] == ""){
            return false;
        }
        if($row[1] == ""){
            return false;
        }
        if($row[2] == ""){
            return false;
        }
        if($this->isDate($row[2] == false)){
            return false;
        }
        if(strlen($row[3])!=8){
            return false;
        }
        $x= strpos($row[3],"*****");
        if($x === false){
            $x= strpos($row[3],"***");
            if($x != 5){
                return false ;
            }
        }elseif($x!=0){
            return false ;
        }
        if(!in_array($row[4], ['true','false'])){
            return false ;
        }
        return true;
    }

    private function isDate($dateString) {

        $dateTime = \DateTime::createFromFormat('d/m/Y', $dateString);

        if ($dateTime === false) {
            // Try with d-m-Y format
            $dateTime = \DateTime::createFromFormat('d-m-Y', $dateString);

            if ($dateTime === false) {

                // Both formats failed, handle the invalid date here
                return false ;
            } else {
                // Valid date with d-m-Y format
                return true ;
            }
        } else {
            // Valid date with d/m/Y format
            return true ;
        }
    }
}
