<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ctaf extends Model
{
    use HasFactory;
    protected $fillable = ['NUM_PASSPORT', 'NOM_PRENOM'];

    protected $table = 'ctaf';

}
