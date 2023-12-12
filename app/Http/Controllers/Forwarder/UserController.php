<?php



namespace App\Http\Controllers\Forwarder;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DataTables;

use Yajra\DataTables\Html\Builder;

use App\DataTables\UserDataTable;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Level;
use DB;
use Hash;
use Validator;
use Session;




class UserController extends Controller

{

    public function index(Builder $builder, UserDataTable $dataTable)

    {

       $html = $builder->columns([

            ['data' => 'id', 'name' => 'id','title' => 'ID'],
            ['data' => 'level', 'name' => 'level','title' => 'Level Name'],
            ['data' => 'username', 'name' => 'username','title' => 'User Name'],
            ['data' => 'depend_id', 'name' => 'depend_id','title' => 'Depend Name'],
            ['data' => 'manager_id', 'name' => 'manager_id','title' => 'Added By'],
            ['data' => 'role', 'name' => 'role','title' => 'Role'],
            ['data' => 'status', 'name' => 'status','title' => 'Status'],
           // ['data' => 'created_at', 'name' => 'created_at','title' => 'Scaned At'],
            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false,'title' => 'Action'],

        ])  

        ->parameters([
            "order" =>["0","DESC"]
        ]);

        if(request()->ajax()) {

        $CarCategory = User::where('forwarder_id',Auth::id())->get();
        return $dataTable->dataTable($CarCategory)->toJson();

        }
        return view('forwarder.user.list', compact('html'));

    }
    public function index2(Builder $builder, UserDataTable $dataTable)

    {

       $html = $builder->columns([

            ['data' => 'id', 'name' => 'id','title' => 'ID'],
            ['data' => 'level', 'name' => 'level','title' => 'Level Name'],
            ['data' => 'username', 'name' => 'username','title' => 'User Name'],
            ['data' => 'depend_id', 'name' => 'depend_id','title' => 'Depend Name'],
            ['data' => 'role', 'name' => 'role','title' => 'Role'],
            ['data' => 'status', 'name' => 'status','title' => 'Status'],
            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false,'title' => 'Action'],

        ])  

        ->parameters([
            "order" =>["0","DESC"]
        ]);

        if(request()->ajax()) {

        $CarCategory = User::where('manager_id',Auth::id())->get();
        return $dataTable->dataTable($CarCategory)->toJson();

        }
        return view('forwarder.user.list2', compact('html'));

    }
    public function Add(Request $request)
    {
        $level= Level::where('forwarder_id',Auth::id())->get();
        return view('forwarder.user.add',compact('level'));
    }
    public function Add2(Request $request)
    {
        $Category = User::where('id',Auth::id())->first();
       // dd($Category);
        $user = $Category['level'];
        for($i=1; $i <= ($user); $i++){
            $level2  = Level::where("level_name",[$i])->pluck("level_name")->toArray();
            if(!empty($level2)){
                $checkboxVal[]  = $level2[0];
            }
        }
       // dd($checkboxVal);
        $level= Level::whereNotin('level_name',$checkboxVal)->where('forwarder_id',$Category['forwarder_id'])->get();
        return view('forwarder.user.add2',compact('level'));
    }
    public function list(Request $request){
        $levelname = Level::where('id',$request->id)->first();
        if($levelname->level_name == '1'){
            $cities = User::where('id', Auth::id())->get()->toArray();  
        }else{
            $ids = $levelname->level_name - 1;
            $user = User::where('id', Auth::id())->where('role','!=','forwarder')->first();  
            if($user){
            $for_level = User::where('id', Auth::id())->first(); 
            $ids2 = $levelname->level_name - 1;
            if($ids2 == $for_level->level){
                $cities = User::where('level', $ids)->where('id', Auth::id())
                ->where('status',0)->get()->toArray();  
            }else{
                $cities = User::where('level', $ids)->where('manager_id', Auth::id())
                ->where('forwarder_id',$user['forwarder_id'])->where('status',0)->get()->toArray();
            }
          
            }
            else{
                $cities = User::where('level', $ids)->where('forwarder_id',Auth::id())->where('status',0)->get()->toArray();
            }
            
        }
       
        $option = '<option disabled selected>Please Select User</option>';
        if(isset($request->depend) && !is_null($request->depend)) {
            foreach($cities as $city) {
                $option .= '<option value="'.$city['id'].'"'.(($request->depend == $city['id']) ? 'selected': '').'>'.$city['username'].'</option>';
            }
        } else {
            foreach($cities as $city) {
                $option .= '<option value="'.$city['id'].'">'.$city['username'].'</option>';
            }
        }
  
        echo $option;
    }
    public function levelname(Request $request){
        
       
        $ids2 = $request->id - 1;
        $levelname = Level::where('id',$request->id)->first();
        if($levelname->level_name == '1' || $ids2 == 0){
            $name = 'company';
        }else{
            $levelname = Level::where('id',$ids2)->first();
            $name = $levelname->name;
        }
        
        
        return 'Select'.' '.$name;
    }
    public function store(Request $request)

    {

        $this->validate($request, [
                 
            'password' => 'required',
            'depend' => 'required',
            'username'=> 'required|unique:users,username',
            
             ],[
             'username.required' => "Please Enter Username",
             'depend.required' => "Please Select User",
             'password.required' => "Please Enter Password",
             'username.unique' => "Please Enter Unique Users",
             ]);

            $category = new User();

            $category->username = $request->username;
            if($request->password != null ){

                $category->password=Hash::make($request->password);  
            }

            $category->forwarder_id = Auth::id();
            $category->manager_id = Auth::id();
            $category->depend_id = $request->depend;
           

            $levelname = Level::where('id',$request->role)->first();
            $category->level = $levelname->level_name;
            $category->role = 'Forwarder_level'.''.$levelname->level_name;
            
            if($category->save()) {
                $category->assignRole($category->role);
                Session::flash('message', 'User added succesfully!');

                Session::flash('alert-class', 'success');

                return redirect('forwarder/user');

            } else {

                Session::flash('message', 'Oops !! Something went wrong!');

                Session::flash('alert-class', 'error');

                return redirect()->back();

            }
    }

    public function store2(Request $request)

    {

        $this->validate($request, [
                 
            'password' => 'required',
            'depend' => 'required',
            'username'=> 'required|unique:users,username',
            
             ],[
             'username.required' => "Please Enter Username",
             'depend.required' => "Please Select User",
             'password.required' => "Please Enter Password",
             'username.unique' => "Please Enter Unique Users",
             ]);

            $category = new User();

            $category->username = $request->username;
            if($request->password != null ){

                $category->password=Hash::make($request->password);  
            }
            $user = User::where('id',Auth::id())->first();
            $category->forwarder_id = $user->forwarder_id;
            $category->manager_id = Auth::id();
            $category->depend_id = $request->depend;
            $levelname = Level::where('id',$request->role)->first();
            $category->level = $levelname->level_name;
            $category->role = 'Forwarder_level'.''.$levelname->level_name;
            
            if($category->save()) {
                $category->assignRole($category->role);
                Session::flash('message', 'User added succesfully!');

                Session::flash('alert-class', 'success');

                return redirect('forwarder/user2');

            } else {

                Session::flash('message', 'Oops !! Something went wrong!');

                Session::flash('alert-class', 'error');

                return redirect()->back();

            }
    }


    public function edit($id)

    {

        $data =  User::findOrfail($id);
        //dd($data);
        // if($data->level == 1){
        //     $cities = User::where('id', Auth::id())->get();  
        // }else{
        //     $level = $data->level - 1;
        //     $cities = User::where('level', $level)->where('status',0)->get();
        // }
            $level = $data->level - 1;
            //dd($level);
            if($level == 0){
                $cities = User::where('id',Auth::id())->where('status',0)->get();
                $level= Level::where('forwarder_id',Auth::id())->get();
            }
            else{
            $user = User::where('id', Auth::id())->where('role','!=','forwarder')->first();  
            if($user){
            $cities = User::where('level', $level)
            ->where('forwarder_id',$user['forwarder_id'])->where('status',0)->get();
            $level= Level::where('forwarder_id',$user['forwarder_id'])->get();
            }
            else{
                $cities = User::where('level', $level)->where('forwarder_id',Auth::id())->where('status',0)->get();
                $level= Level::where('forwarder_id',Auth::id())->get();
            }
        }

        
       

        return view('forwarder.user.edit', compact('data','level','cities'));

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

            'username' => 'required'

        ];



        $messages = [];



        $validator = Validator::make($request->all(), $rules, $messages);



        if($validator->fails()) {

            return redirect()->back()

            ->withErrors($validator)

            ->withInput();

        } else {

            
            $category = User::find($request->id);
            // if($request->role == 1){
            //     $category->forwarder_id = Auth::id(); 
            // }else{
            //     $category->forwarder_id = $category->forwarder_id;
            // }
            
            $category->manager_id = Auth::id();
            $category->depend_id = $request->depend;
            $levelname = Level::where('id',$request->role)->first();
            $category->level = $levelname->level_name;
            $category->role = 'Forwarder_level'.''.$levelname->level_name;

            $category->username = $request->username;
            if($request->password != null){

                $category->password=Hash::make($request->password);  
            }
            $category->status= $request->status;
            if($request->status == 1 ){
                $data = User::where('username',$request->username)->update(['device_token' => null]);
            }

            if($category->save()) {

                Session::flash('message', ' User updated succesfully!');

                Session::flash('alert-class', 'success');

                if($levelname->level_name == 1){
                    return redirect('forwarder/user'); 
                }else{
                    return redirect('forwarder/user2');
                }
               
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

        $category = User::destroy($id);

        

        if($category)

            return true;

        else

            return 'Something went to wrong!';

    }


}

