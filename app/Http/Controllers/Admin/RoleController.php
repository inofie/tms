<?php
    
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use App\DataTables\RoleDataTable;
use App\Helper\GlobalHelper;
use Validator;
use App\PermissionRole;
use App\User;
use Illuminate\Support\Facades\Auth;
    
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        
        
            $result = Role::where('auth_id','1')->get();
            
        
        return view('admin.roles.list',compact('result'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ADD()
    {
        $permission = Permission::select("category")->where('status','1')->get();
        return view('admin.roles.create',compact('permission'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $rules = array(
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        );
        $messages = [
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $role = Role::create(['name' => strtolower(str_replace(' ', '_', $request->input('name'))),'auth_id'=>'1']);
            $role->syncPermissions($request->input('permission'));
            return redirect()->route('roleslist') 
                            ->with('success','Role created successfully');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     $role = Role::find($id);
    //     $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
    //         ->where("role_has_permissions.role_id",$id)
    //         ->get();
    //     $permission = Permission::select("category")->groupBy("category")->where('status','1')->get();
    //     return view('admin.roles.view',compact('role','rolePermissions','permission'));
    // }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::select("category")->where('status','1')->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('admin.roles.edit',compact('role','permission','rolePermissions'));
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
            'name' => 'required',
            'permission' => 'required',
        );
        $messages = [
        ];
        $validator = Validator::make($Request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $role = Role::find($Request->id);
           
            $role->name = strtolower(str_replace(' ', '_', $Request->input('name')));
            $role->save();
            $role->syncPermissions($Request->input('permission'));
            return redirect()->route('roleslist')
                        ->with('success','Role updated successfully');
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
    
        $record = Role::where('id',$Request->id)->first();
        $record->deleted_at =Auth::user()->id;
        $record->save();
        $record->delete();
        return redirect()->route('roleslist')->with('success','Role deleted successfully.');
    }

    public function getPermissions(Request $request)
  {
      $getPermissions = PermissionRole::where("role_id",$request->id)->get()->pluck('permission_id');
      if($getPermissions){
          echo $getPermissions;
      }else{
          echo "0";
      }
  }
}