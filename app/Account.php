<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
	use SoftDeletes;
    protected $table = "account";

    public function invoiceData(){
        return $this->hasOne('App\Invoice','id','invoice_list');
    }
}
