<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
	use SoftDeletes;
    protected $table = "driver";

    public function getUserIdAttribute($value)
    {
        if ($value != '' && $value != null) {
            if (isset($value)) {
                    $final = $value;
                return $final;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    public function transporterData(){
        return $this->hasOne('App\Transporter','id','transporter_id');
    }
}
