<?php

namespace App\Models;

use App\Models\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Agent extends Model
{
    use HasFactory, HasApiTokens;

    public function state(){
        return $this->hasOne(State::class , 'id' , 'state_id');
    }
}
