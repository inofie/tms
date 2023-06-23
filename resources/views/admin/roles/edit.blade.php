@extends('layout.master')
@section('title')
Role Edit |
@endsection

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
                               Edit Role
                                <div class="btn-group pull-right">
                                 <a href="{{ route('roleslist') }}"> <button style="padding: 7%;" class="btn btn-info"><!-- id="editable-sample_new" -->
                                      Back <i class="fa fa-reply"></i>
                                  </button></a>
                                </div>
                            </header>
                            <div class="panel-body">
                                <div class="form">
            <form class="" id="dataForm" role="form" action="{{route('rolesupdate')}}" method="post" enctype="multipart/form-data" >
               @csrf
               <input type="hidden" name="id" value="{{ $role->id }}">
                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label  class=" control-label" for="name">Role Name <span class="colorRed"> *</span></label>
                                <div class=" jointbox">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Role Name in lower case" value="@if($role->name) {{$role->name}} @else {{old('name')}} @endif"/>
                                    @if ($errors->has('name'))
                                    <span class="help-block alert alert-danger">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('permission') ? ' has-error' : '' }}">
                                <label  class="control-label" for="name">Permission <span class="colorRed"> *</span></label>
                                <div>
                                    @foreach($permission as $key=>$row1)
                                        <div class="col-sm-12">
                                            <div class="categoryDiv">
                                                <p class="categoryHeader">{{$row1->category}}<label class="selectOnlyCategory"><input data-val="{{$key}}" class="selectOnlyCategory" name="selectOnlyCategory" type="checkbox">select all {{$row1->category}}</label></p>
                                            </div>
                                            @php $permissions = Helper::getPermissionByCategory($row1->category); @endphp
                                            @foreach($permissions as $row)
                                                <div class="col-lg-4 col-md-3 col-sm-3">
                                                    <label class="selectOnlyCategories">{{ Form::checkbox('permission[]', $row->id, in_array($row->id, $rolePermissions) ? true : false, array('class' => 'name minimal mr-2 all '.$key.'','style'=>'margin-right: 5px;')) }}
                                                        {{ $row->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                    {{-- <br/>
                                    @foreach($permission as $value)
                                        <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                        {{ $value->name }}</label>
                                    <br/>
                                    @endforeach --}}
                                    @if ($errors->has('permission'))
                                        <span class="help-block alert alert-danger">
                                            <strong>{{ $errors->first('permission') }}</strong>
                                        </span>
                                    @endif
                                </div>
                           
                        <div class="form-group save_cancle">
                                            <div class="col-lg-12 center text-center">
                                                <button class="btn btn-success" type="submit">Save</button>
                                                <button class="btn btn-default" id="cancelbtn" type="button">Cancel</button>
                                            </div>
                                        </div>
                    </div>
                </div>
            </form>
            <!-- /.col -->
        </div>
        </div>
        <!-- /.row -->
    </section>
    </section>
    </section>

    <!-- /.content -->

@endsection



@section('js1')
<style>
    .permission_section label{
        font-weight:200;
    }
    .categoryDiv{
        margin-top: 30px;
        background: #f7f7f7;
    }
    .categoryHeader{
        font-size: 15px;
    }
    .selectOnlyCategory {
        margin: 0 20px;
        font-size: 16px;
        text-transform: lowercase;
    }
    .selectOnlyCategory input {
    margin-right: 5px;
}
.selectOnlyCategories {
    display: flex;
    margin-right: 5px;
    justify-content: left;
    align-items: center;
    font-size: 15px;
}
</style>
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

        $("#cancelBtn").click(function () {
            window.location.href = "{{route('roleslist')}}";
        });
    
        $(document.body).on('click', "#createBtn", function(){
            if ($("#dataForm").length){
                $("#dataForm").validate({
                  errorElement: 'span',
                          errorClass: 'text-red',
                          ignore: [],
                          rules: {
                            "name":{
                                required:true,
                            },
                        },
                        messages: {
                            "name":{
                                required:"Please enter role name.",
                            },
    
                          },
                          errorPlacement: function(error, element) {
                            error.insertAfter(element.closest(".form-control"));
                        },
                  });
            }
        });
    </script>
    <script>
        $(".selectOnlyCategory").change(function(){
            var value = $(this).data("val");
            if ($(this).is(':checked')) {
                $(".all."+value).prop('checked', true);
            }else{
                $(".all."+value+"").prop('checked', false);
            }
        });
        $("#select_all").change(function(){
            if ($(this).is(':checked')) {
                $(".all").prop('checked', true);
        
            }else{
                $(".all").prop('checked', false);
            }
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
            var SITE_URL = "<?php echo URL::to('/'); ?>";
        $('#roles').on('change', function(){
            var id = $(this).val();
            $.ajax({
                type: 'POST',
                url: SITE_URL + '/admin/permission/getPermissions',
                data: { id :id },
                success: function(data) {
                    $("input[type='checkbox']").prop('checked', false);
                    if(data!=0){
                        var parse = JSON.parse(data);
                        $("input[type='checkbox']").prop('checked', false);
                        $.each( parse, function( index, value ){
                            $("input[value='"+value+"']").prop('checked', true);
                        });
                    }
                }
            });
        });
    </script>
@endsection
