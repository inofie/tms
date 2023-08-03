<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Shipment extends Model
{
	use SoftDeletes;
    protected $table = "shipment";

    public function statusData()
  	{
        return $this->hasOne('App\Shipment_Driver','shipment_no','shipment_no')->orderBy('id','desc')
            ->withDefault(function () {
                return (object) [];
            });
  	}
}
