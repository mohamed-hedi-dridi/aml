<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternVmProd extends Model
{
    use HasFactory;
    protected $fillable = ['Ref', 'NOM_PRENOM', 'CIN'];
    protected $table = 'vm';
    public $timestamps = false;
    protected $connection = 'DB_Prod';

}
