<?php

namespace App\Models;

use App\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;


class MenuSideBar extends Model
{
    use HasFactory;

    public function module()
    {
        return $this->belongsTo(Module::class);
    }


    public function fils()
    {
        return $this->hasMany(MenuSideBar::class, 'parent', 'id');
    }

    public function permission()
    {
        //return $this->hasOne(Permission::class,'id','permission_id');
        $permission = Permission::find($this->permission_id);
        return $permission;
    }

    static public function GetModelByUri(){
        $uri = MenuSideBar::where("route",'/'.Route::getCurrentRoute()->uri())->first();
        if ($uri) {
            return $uri->module->name ;
        }
        return null ;
    }

}
