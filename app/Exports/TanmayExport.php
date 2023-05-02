<?php

namespace App\Exports;

use App\Current_Lession;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use DB;
use App\User;


class TanmayExport implements FromCollection
{

	use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

    	$userdata = array();



    	$aa =Current_Lession::select(DB::raw('DISTINCT(user_id)'))->orderby('user_id','asc')->get();

    	//$aa = Current_Lession::distinct('user_id')->orderby('user_id','asc')->get();
    	//dd($aa);

    	foreach ($aa as $key => $value) {

    		$sdata = Current_Lession::where('user_id',$value->user_id)->orderby('created_at','asc')->first();
    		$ldata = Current_Lession::where('user_id',$value->user_id)->orderby('updated_at','desc')->first();
            $mycount = User::where('id',$value->user_id)->count();

            if($mycount == 0){

                $mmdata = Current_Lession::where('user_id',$value->user_id)->delete();

            } else {
                
                $udata = User::where('id',$value->user_id)->first();
                
                if($udata->share_data == 1){

                $Xprixe_Share_Consent = 'true';

                    } else {

                $Xprixe_Share_Consent = 'false';

                }


    		$userdata[$key]= array(
    			'user_id'=>$udata->id,
                'name'=>$udata->first_name." ".$udata->last_name,
                'email'=>$udata->email,
                'Xprixe_Share_Consent'=>$Xprixe_Share_Consent,
    			'Training_Start_Date'=> $sdata->created_at,
    			'Training_End_Date'=> $ldata->updated_at,
                'Team_Assignment'=>'Team RE',
    		);

    		
    	}

    }

    	$titles[0] = array(
    			'user_id'=>'user_id',
                'name'=>'name',
                'email'=>'email',
                'Xprixe_Share_Consent'=>'Xprixe_Share_Consent',
        		'Training_Start_Date'=> 'Training_Start_Date',
    			'Training_End_Date'=> 'Training_End_Date',
                'Team_Assignment '=>'Team_Assignment',
    		);

    	$data = array_merge($titles,$userdata);

    	//dd($data);

    	return new Collection($data);


    	
    }
}
