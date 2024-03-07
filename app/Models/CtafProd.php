<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtafProd extends Model
{
    use HasFactory;
    protected $connection = 'DB_Prod';
    protected $fillable = ['NUM_PASSPORT', 'NOM_PRENOM'];
    protected $table = 'ctaf';
    public $timestamps = false;

}
