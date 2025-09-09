<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiaMoney extends Model
{
    use HasFactory;
    protected $connection = 'transferInternationalPostFending';
    public $timestamps = false;
    protected $table = 'ria_details';
}
