<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment_Summary extends Model
{
	use SoftDeletes;
    protected $table = "shipment_summary";

}
