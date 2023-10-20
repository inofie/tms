<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
	use SoftDeletes;
    protected $table = "expense";

    public function companyData(){
        return $this->hasOne('App\Company','id','company_id')->withDefault(function () {
            return (object) [];
        });
    }
    public function transporterData(){
        return $this->hasOne('App\Transporter','id','transporter_id')->withDefault(function () {
            return (object) [];
        });
    }
    public function forwarderData(){
        return $this->hasOne('App\Forwarder','id','forwarder_id')->withDefault(function () {
            return (object) [];
        });
    }

}
