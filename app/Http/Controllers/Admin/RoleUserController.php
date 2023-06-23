<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Role;
use App\User;
use Validator;
use Session;
use App\Http\Controllers\Controller;

class RoleUserController extends Controller
{
    function __construct()
    {
      

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $Request)
    {
        
      // DB::enableQueryLog(); dd(DB::getQueryLog());// Enable query log
      
       
            $all_users_with_all_their_roles  = User::whereHas('roles', function ($query){
                $query->where('auth_id', '=', '1');
            })->get();
            
        return view('admin.roleuser.list',compact('all_users_with_all_their_roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $action = 'Create';
        $roles = Role::where('auth_id','1')->get();
        return view('admin.roleuser.edit', compact(['action','roles']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        
            $this->validate($request, [
                 
                'password' => 'required',
                'confirm_password' => 'required|same:password',
                'username'=> 'required|unique:users,username',
                'name'=> 'required',      
                 ],[
                 'name.required' => "Please Enter Name",
                 'username.required' => "Please Enter Username",
                 'password.required' => "Please Enter Password",
                 ]);
    
            $adminUser = new User();
            $adminUser->username = $request['username'];
            $adminUser->name = $request['name'];
            // $adminUser->email = $request['email'];
            $adminUser->password = bcrypt($request['password']);
    
            $adminUser->role = $request['names'];
           

            if ($adminUser->save()) {
                $adminUser->assignRole($request['names']);
                return redirect()->route('roleuserslist')->with('success','Role User Added successfully.');
            } else {
                return redirect('admin/roleuser');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $action = 'Update';
        $roleuser = User::find($id);

        if(!empty($roleuser)){
            $role_id=$roleuser->roles->first()->id;
            $roles = Role::where('auth_id','1')->get();
            $user = User::find($id);
            $userRole = $user->roles->pluck('name','name')->all();
            return view('admin.roleuser.edit', compact(['action','roles','roleuser','role_id','user','userRole']));
        }
        else{
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $Request)
    {
        $rules = array(
            
            'password' => 'sometimes',
            'confirm_password' => 'sometimes',
        );
        $messages = [
        ];

        $validator = Validator::make($Request->all(), $rules, $messages);
       // dd($validator->fails());
        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $adminUser = User::where('id',$Request->id)->first();
           // dd($adminUser);
            $adminUser->username = $Request['username'];
            $adminUser->name = $Request['name'];
            // $adminUser->email = $Request['email'];
            if($Request['password']!= null || !empty($Request['password'])){
                $adminUser->password = bcrypt($Request['password']);
            }
            
            $adminUser->updated_at = date("Y-m-d H:i:s");
            $role = $adminUser->roles->toArray();
            if($role[0]['id']!=$Request['names']){
                    $adminUser->removeRole($role[0]['id']);
                    $adminUser->assignRole($Request['names']);
                }
          //  $role = Role::find($adminUser->roles);      
           // $role->role_id = $request['name'];
            if ($adminUser->save()) {
                Session::flash('message', 'Role User Updated Succesfully !');
                Session::flash('alert-class', 'success');

                return redirect('admin/roleuser');
            } else {
                Session::flash('message', 'Oops !! Something went wrong!');
                Session::flash('alert-class', 'error');
                return redirect('admin/roleuser');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $Request)
    {
      
            $user = User::with('roles')->where('id',$Request->id)->first();;
            $role = $user->roles[0]->id;
            $user->delete();
            $user->removeRole($role);
            return redirect()->route('roleuserslist')->with('success','Role User deleted successfully.');
         
    }
    
}

