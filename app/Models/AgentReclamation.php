<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentReclamation extends Model
{
    protected $connection = 'DB_reclamation';
    //protected $fillable = ['NUM_PASSPORT', 'NOM_PRENOM'];
    protected $table = 'agents';
    public $timestamps = false;
}
