<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyGramValidationReq extends Model
{
    use HasFactory;
    protected $connection = 'transferInternationalPostFending';
    public $timestamps = false;
    protected $table = 'receive_validation_req';

    public function validationReq()
    {
        return $this->belongsTo(MoneyGram::class, 'reference_number' , 'reference_number');
    }
}
