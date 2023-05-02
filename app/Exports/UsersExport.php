<?php

 
namespace App\Exports;
 

use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Job;
use App\Machine;
use App\Product;
use App\Lesson;
use App\Experience;
use App\User;
use Excel;
use DB;
use App\Completed_Lession;
use App\Current_Lession;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;


class UsersExport implements FromCollection
{
   public function actions(Request $request)
	{
	    return [
	        new DownloadExcel(),
	    ];
	}
}



