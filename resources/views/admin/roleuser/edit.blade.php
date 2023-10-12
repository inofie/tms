@extends('layout.master')

@section('title') {{$action}} Role User | @endsection
@section('css2')
      <link href="{{ asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />
      <link href="{{ asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />
      <link href="{{ asset('assets/data-tables/DT_bootstrap.css')}}" rel="stylesheet"  />
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-fileupload/bootstrap-fileupload.css')}}" />
@endsection

@section('content')
<section id="main-content">
          <section class="wrapper site-min-height">


                      @if ($message = Session::get('success'))
                      <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                              <strong>{{ $message }}</strong>
                      </div>
                      @endif


                      @if ($message = Session::get('error'))
                      <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                              <strong>{{ $message }}</strong>
                      </div>
                      @endif


                      @if ($message = Session::get('warning'))
                      <div class="alert alert-warning alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                      </div>
                      @endif


                      @if ($message = Session::get('info'))
                      <div class="alert alert-info alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                      </div>
                      @endif


                      @if ($errors->any())
                      <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        Please check the form below for errors
                      </div>
                      @endif
                      <section class="panel">
                            <header class="panel-heading ">
                               Role
                                <div class="btn-group pull-right">
                                 <a href="{{ route('roleuserslist') }}"> <button style="padding: 7%;" class="btn btn-info"><!-- id="editable-sample_new" -->
                                      Back <i class="fa fa-reply"></i>
                                  </button></a>
                                </div>
                            </header>

                            <div class="panel-body">
                                <div class="form">
      
                    <!-- /.box-header -->
                    <!-- form start -->
                     @if(isset($roleuser))
                        {{ Form::model($roleuser, ['route' => ['roleusersupdate', $roleuser->id], 'method' => 'post','class' => 'form-horizontal','enctype'=>'multipart/form-data','id'=>'roleForm']) }}
                    @else
                        {{ Form::open(['route' => 'roleuserssave','class' => 'form-horizontal','enctype'=>'multipart/form-data','id'=>'roleForm']) }}
                    @endif
                    @if(isset($roleuser))
                    <input type="hidden" name="id" value="{{ $roleuser->id }}">
                    @endif
                    <div class="col-sm-12">
                            <div class="box-body">

                                <div class="form-group">
                                  <label  class=" control-label" for="geo_hub_name">Select Role <span class="colorRed"> *</span></label>
                                  <div class=""> 
                                    {{-- !!Form::select('names', $roles->pluck('name','id'),null, ['class' => 'form-control','id'=>'names'])!! --}}
                                    <select name="names" id="names" class="form-control">
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{(old('names')==$role->name)?"selected":""}}
                                        @if(isset($role_id) && $role->id == $role_id) selected @endif >{{ $role->name }}</option>
                                    @endforeach 
                                    </select>
                                    @if ($errors->has('names'))
                                    <span class="help-block alert alert-danger">
                                      <strong>{{ $errors->first('names') }}</strong>
                                    </span>
                                    @endif
                                  </div>
                                </div>
                                <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                               <label  class=" control-label" for="name">User Name<span class="colorRed"> *</span></label>
                               <div class="row">
                                <div class="col-sm-6 jointbox">
                                    {{ Form::text('username', Request::old('username'),['class'=>'form-control','placeholder'=>"User Name",'autoComplete'=>"off"]) }}
                                    @if ($errors->has('username'))
                                        <span class="help-block alert alert-danger">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-sm-6 ">
                                        {{ Form::text('name', Request::old('name'),['class'=>'form-control','placeholder'=>"Name",'autoComplete'=>"off"]) }}
                                        @if ($errors->has('name'))
                                            <span class="help-block alert alert-danger">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                </div>
                                <!-- <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label  class=" control-label" for="email">Email <span class="colorRed"> *</span></label>
                                    <div class="">
                                       {{ Form::text('email', Request::old('email'),['class'=>'form-control','placeholder'=>"Email"]) }}
                                        @if ($errors->has('email'))
                                        <span class="help-block alert alert-danger">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div> -->
                                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label  class=" control-label" for="password">Password <span class="colorRed"> *</span></label>
                                    <div class="row">
                                        <div class="col-sm-6 jointbox">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="{{old('password')}}"/>
                                            @if ($errors->has('password'))
                                            <span class="help-block alert alert-danger">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 ">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="{{old('confirm_password')}}"/>
                                            @if ($errors->has('confirm_password'))
                                            <span class="help-block alert alert-danger">
                                                <strong>{{ $errors->first('confirm_password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group save_cancle">
                                            <div class="col-lg-12 center text-center">
                                                <button class="btn btn-success" type="submit">Save</button>
                                                <a class="btn btn-default" href="{{ route('roleuserslist') }}">Cancel</a>
                                            </div>
                                        </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    {{ Form::close() }}
                    <div class="clearfix"></div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    </section>
    </section>
@endsection
@section('js1')

<script type="text/javascript" language="javascript" src="{{ asset('js/jquery.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('assets/advanced-datatable/media/js/jquery.js')}}"></script>
@endsection

@section('js2')

<script type="text/javascript" language="javascript" src="{{ asset('assets/advanced-datatable/media/js/jquery.dataTables.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/data-tables/DT_bootstrap.js')}}"></script>
@endsection
@section('js3')

<script type="text/javascript" src="{{ asset('assets/bootstrap-fileupload/bootstrap-fileupload.js')}}"></script>
 <script type="text/javascript" src="{{ asset('assets/jquery-multi-select/js/jquery.multi-select.js')}}"></script>

@endsection

@section('js4')
   
<script>
 var SITE_URL = "<?php echo URL::to('/'); ?>";

@if(!isset($roleuser))
                var rules = {"username":{required:true,},
                             "confirm_password":{required:true,equalTo:'#password',},
                              "password":{required:true,minlength: 6,maxlength: 20},
                              };
                var messages = {
                      "username":{
                          required:"Please enter username.",
                      },
                      
                     
                      "confirm_password":{
                          required:"Please enter confirm password.",
                          equalTo: "Please enter same as password.",
                      },
                      "password":{
                          required:"Please enter password.",
                      },
                    };
@else
var rules = {"username":{required:true,},
                             "confirm_password":{equalTo:'#password',},
                              "password":{minlength: 6,maxlength: 20},
                              };
                var messages = {
                      "username":{
                          required:"Please enter username.",
                      },
                      
                     
                      "confirm_password":{
                          equalTo: "Please enter same as password.",
                      },
                    };
@endif;

    $(document.body).on('click', "#FormBtn", function(){
        if ($("#roleForm").length){
            $("#roleForm").validate({
            errorElement: 'span',
                    errorClass: 'text-red',
                    ignore: [],
                    rules: rules,
                  messages: messages,
                    errorPlacement: function(error, element) {
                        if(element.is('select')){
                            error.appendTo(element.closest("div"));
                        }else{
                            error.insertAfter(element.closest(".form-control"));
                        }
                    },
            });
        }
    });

</script>
@endsection
