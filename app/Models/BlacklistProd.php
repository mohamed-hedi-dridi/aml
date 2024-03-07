<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlacklistProd extends Model
{
    use HasFactory;
    protected $connection = 'DB_Prod';
    protected $fillable = ['Prenom', 'Nom' , 'date', 'CIN','Dead'];
    public $timestamps = false;
    protected $table = 'blacklist';

}
