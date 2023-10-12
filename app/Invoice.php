<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
	use SoftDeletes;
    protected $table = "invoice";

    public function companyData(){
        return $this->hasOne('App\Company','id','company_id')->select('id','name');
    }
    public function forwarderData(){
        return $this->hasOne('App\Forwarder','id','forwarder_id')->select('id','name');
    }
    public function voucherData(){
        return $this->hasOne('App\Account','invoice_list','id')->select('id');
    }
}
