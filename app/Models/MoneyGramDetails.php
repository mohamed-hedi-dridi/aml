<?php

namespace App\Models;

use App\Models\MoneyGram;
use App\Models\MoneyGramValidationReq;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoneyGramDetails extends Model
{
    use HasFactory;
    protected $connection = 'transferInternationalPostFending';
    public $timestamps = false;
    protected $table = 'money_gram_details';

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent', 'email');
    }

    public function details()
    {
        return $this->belongsTo(MoneyGram::class, 'id' , 'reference_number_id');
    }

    public function reciveValidation()
    {
        return $this->belongsTo(MoneyGramValidationReq::class, 'receive_validation_req_id' , 'id');
    }
}
