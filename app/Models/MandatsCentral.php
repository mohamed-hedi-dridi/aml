<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MandatsCentral extends Model
{
    use HasFactory;
    protected $connection = 'localhostdb';
    protected $table = 'Mandats_central';
    
}
