<?php

namespace App\Models;

use App\Models\Agent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zepz extends Model
{
    use HasFactory;
    protected $connection = 'Post_Funding_DB';
    public $timestamps = false;
    protected $table = 'zepz';

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'email_agent', 'email');
    }
}
