<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationUnisProd extends Model
{
    use HasFactory;
    protected $connection = 'DB_Prod';
    protected $fillable = ['NAME','NAME_ORIGINAL_SCRIPT','NATIONALITY','TYPE_OF_DOCUMENT', 'NUMBER' , 'COUNTRY_OF_ISSUE', 'TYPE_OF_DATE','DATE_BIRTH'];
    public $timestamps = false;
    protected $table = 'nation_unis';
}
