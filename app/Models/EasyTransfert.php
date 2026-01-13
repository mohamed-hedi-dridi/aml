<?php

namespace App\Models;

use App\Models\Agent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EasyTransfert extends Model
{
    use HasFactory;
    protected $connection = 'transferInternationalPostFending';
    public $timestamps = false;
    protected $table = 'met_cash_out_details';

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent', 'email');
    }

    public function getStatusKYC($code){
        $latestSuspect = KYC::where('code',$code)->orderBy('id','asc')->first();
        return $latestSuspect;
    }
}
