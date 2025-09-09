<?php

namespace App\Imports;

use App\Models\InternVm;
use App\Models\InternVmProd;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportInterne implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        DB::connection('mysql')->beginTransaction();
        DB::connection('DB_Prod')->beginTransaction();
        if(strtoupper($rows[0][0])=="REF" && strtoupper($rows[0][1])== "NOM_PRENOM" && strtoupper($rows[0][2])== "CIN"){
            try {
                $InternVm = InternVm::query()->update(['old' => 1]);
                foreach ($rows as $key => $row) {
                    if($key>0){
                        if($this->Verif($row)){
                            InternVm::create ([
                                "Ref"=>$row[0],
                                "NOM_PRENOM"=>$row[1],
                                "CIN"=>$row[2]
                            ]);
                            InternVmProd::create ([
                                "Ref"=>$row[0],
                                "NOM & PRENOM"=>$row[1],
                                "CIN"=>$row[2]
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
            } catch (\Throwable $th) {
                Log::channel('error')->error($th->getMessage(), ['user' => Auth::user()->name ,'line' => $th->getLine()]);
                DB::connection('mysql')->rollBack();
                DB::connection('DB_Prod')->rollBack();
            }
        }
    }

    private function Verif($row){
        if(strlen($row[0]) < 4){
            return false;
        }
        if(strlen($row[1]) < 3){
            return false;
        }
        if(strlen($row[2]) != 8){
            return false;
        }
        return true;
    }
}
