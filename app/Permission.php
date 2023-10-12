<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';

    public function checkpermission(){
        return $this->hasOne('App\PermissionRole','permission_id','id');
    }
}
