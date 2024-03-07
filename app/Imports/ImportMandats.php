<?php

namespace App\Imports;

use App\Models\IdentityCheck;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportMandats implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new IdentityCheck ([
            "date"=>date('Y-m-d', strtotime($row[0])),
            "code"=>$row[1],
            "input_identity"=>$row[2],
            "output_identity"=>$row[3],
            "identity_type"=>$row[4],
            "birthday"=>$row[5],
            "status"=>$row[6],
            "blacklisted"=>$row[7],
            "interne"=>$row[8],
            "CTAF"=>$row[9],
            "email_agent"=>$row[10],
            "type_mandat"=>$row[11],
            "iteration"=>$row[12],
            "image_cin"=>$row[13],
        ]);
    }
}
