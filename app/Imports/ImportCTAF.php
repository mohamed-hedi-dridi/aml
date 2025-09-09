<?php

namespace App\Imports;

use App\Models\Ctaf;
use App\Models\CtafProd;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportCTAF implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        DB::connection('mysql')->beginTransaction();
        DB::connection('DB_Prod')->beginTransaction();
        if(strtoupper($rows[0][0])=="NUM_PASSPORT" && strtoupper($rows[0][1])== "NOM_PRENOM"){
            try {
                $ctaf = Ctaf::query()->update(['old' => 1]);
                foreach ($rows as $key => $row) {
                    if($key>0){
                        if(true == true){
                            Ctaf::create ([
                                "NUM_PASSPORT"=>$row[0],
                                "NOM_PRENOM"=>$row[1]
                            ]);
                            CtafProd::create ([
                                "NUM_PASSPORT"=>$row[0],
                                "NOM_PRENOM"=>$row[1]
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
            } catch (\Throwable $e) {
                Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
                DB::connection('mysql')->rollBack();
                DB::connection('DB_Prod')->rollBack();
            }
        }

    }

    private function Verif($row){
        if(strlen($row[0]) < 8){
            return false;
        }
        if(strlen($row[1]) < 3){
            return false;
        }
        return true ;
    }
}
