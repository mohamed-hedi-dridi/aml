<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuspectMandat extends Model
{
    use HasFactory;

    protected $table = 'suspect_mandats';


    public function user(){
        return $this->hasOne(User::class , 'id' , 'user_id');
    }

}
