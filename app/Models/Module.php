<?php

namespace App\Models;

use App\Models\MenuSideBar;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;

    public function menu()
    {
        return $this->hasMany(MenuSideBar::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class); 
    }
}
