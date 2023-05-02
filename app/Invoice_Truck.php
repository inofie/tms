<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice_Truck extends Model
{
	use SoftDeletes;
    protected $table = "invoice_truck";

}
