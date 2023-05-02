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
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExportFormulas implements FromCollection, WithMapping
{
    public function collection()
    {
        return Current_Lession::all();
    }

    /**
     * @var Customer $customer
     * @return array
     */
    public function map($Current_Lession): array
    {
        return [
            $Current_Lession->uset_id,
            '=A2+1',
            $Current_Lession->uset_id,
            $Current_Lession->uset_id,
            $Current_Lession->uset_id,
        ];
    }
}