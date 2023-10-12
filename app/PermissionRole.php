<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    protected $table = 'role_has_permissions';
    protected $primaryKey = 'role_id';

    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = array('permission_id', 'role_id');
}
