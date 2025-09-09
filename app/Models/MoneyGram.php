<?php

namespace App\Models;

use App\Models\Agent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoneyGram extends Model
{
    use HasFactory;
    protected $connection = 'transferInternationalPostFending';
    public $timestamps = false;
    protected $table = 'reference_number';

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent', 'email');
    }
}
