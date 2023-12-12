<?php



namespace App\Http\Controllers\Forwarder;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Config;
use Yajra\DataTables\Html\Builder;

use App\DataTables\LevelDataTable;

use App\level;

use DB;

use Session;

use Validator;



class LevelController extends Controller

{

    public function index(Builder $builder, LevelDataTable $dataTable)

    {

       $html = $builder->columns([

            ['data' => 'level_name', 'name' => 'level_name','title' => 'Level'],
            ['data' => 'name', 'name' => 'name','title' => 'Level Name'],
            //['data' => 'status', 'name' => 'status','title' => 'Status'],

           // ['data' => 'created_at', 'name' => 'created_at','title' => 'Scaned At'],

            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false,'title' => 'Action'],

        ])  

        ->parameters([

            "processing" => true,
            
            "order" =>["0","ASC"],
            

        ]);


        
        if(request()->ajax()) {

            $CarCategory = Level::where('forwarder_id',Auth::id())->get();//where('status','1');

            return $dataTable->dataTable($CarCategory)->toJson();

        }



        return view('forwarder.level.list', compact('html'));

    }
    public function levelAdd(Request $request)
    {
        //$transporter = Transporter::all();
        return view('forwarder.level.leveladd');
    }
    public function store(Request $request)

    {

        $rules = [

            'name' => 'required'

        ];



        $messages = [];



        $validator = Validator::make($request->all(), $rules, $messages);



        if($validator->fails()) {

            return redirect()->back()

            ->withErrors($validator)

            ->withInput();

        } else {

            $category = new Level();

            $category->name = $request->name;

            if($category->save()) {

                Session::flash('message', 'Level added succesfully!');

                Session::flash('alert-class', 'success');

                return redirect('forwarder/level');

            } else {

                Session::flash('message', 'Oops !! Something went wrong!');

                Session::flash('alert-class', 'error');

                return redirect()->back();

            }

        }

    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        

        $user = Level::find($id);

        if(!empty($user)){

            return view('admin.admincharge.view')->with(compact('user'));

        }

       

        else{

            Session::flash('message', 'Level not found!');

            Session::flash('alert-class', 'error');

            return redirect('admin/Level');

        }

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        $data =  Level::findOrfail($id);

       

        return view('forwarder.level.edit', compact('data'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request)

    {

        $rules = [

            'name' => 'required'

        ];



        $messages = [];



        $validator = Validator::make($request->all(), $rules, $messages);



        if($validator->fails()) {

            return redirect()->back()

            ->withErrors($validator)

            ->withInput();

        } else {

            $category = Level::find($request->id);

            $category->name = $request->name;

            if($category->save()) {

                Session::flash('message', ' Level updated succesfully!');

                Session::flash('alert-class', 'success');

                return redirect('forwarder/level');

            } else {

                Session::flash('message', 'Oops !! Something went wrong!');

                Session::flash('alert-class', 'error');

                return redirect()->back();

            }

        }

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        $category = Level::destroy($id);

        

        if($category)

            return true;

        else

            return 'Something went to wrong!';

    }


}

