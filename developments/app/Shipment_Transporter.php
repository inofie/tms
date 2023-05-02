<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment_Transporter extends Model
{
	use SoftDeletes;
    protected $table = "shipment_transporter";

}
