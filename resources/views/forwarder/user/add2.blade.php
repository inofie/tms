@extends('layout.master')

@section('title')
Add User | TMS
@endsection

@section('css2')
    <link href="{{ asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/data-tables/DT_bootstrap.css')}}" rel="stylesheet"  />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-fileupload/bootstrap-fileupload.css')}}" />
@endsection


@section('content')
      <!--main content start-->
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
              <!-- page start-->
            
                      <section class="panel">
                          <header class="panel-heading ">
                             Add User
                              <div class="btn-group pull-right">
                               <a href="{{ route('userlist2') }}"> <button style="padding: 7%;" class="btn btn-info"><!-- id="editable-sample_new" -->
                                    Back <i class="fa fa-reply"></i>
                                </button></a>
                              </div>
                          </header>
                          <div class="panel-body">
                              <div class="form">
                                  <form class="cmxform form-horizontal tasi-form" id="signupForm" method="post" action="{{ route('usersave2') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group ">
                                            <label for="name" class="control-label col-lg-2">Level<span style="color: red"> *</span> :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="role" id="role" required="required"> 
                                                   <option value=""> -- Please Select Level -- </option>
                                                      @foreach($level as $value)
                                                      @if(old('role') == $value->id)
                                                      <option selected="selected" value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @else
                                                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                      @endif
                                                      @endforeach

                                                </select>

                                                @error('role')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                        <label for="depend" id="selectlevel" class="control-label col-lg-2">Select User
                                                <span style="color: red"> *</span> :</label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="depend" id="depend" required="required" > 
                                                   <option value=""> -- Please Select User -- </option>
                                                      
                                                </select>

                                                @error('depend')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                   
                                      <div class="form-group ">
                                          <label for="name" class="control-label col-lg-2">User Name<span style="color: red" > *</span> :</label>
                                          <div class="col-lg-10">
                                              <input class=" form-control" id="username" name="username" required="required" type="text" value="{{ old('username') }}" placeholder=" Enter User Name" />

                                              @if ($errors->has('username'))
                                              <span class="help-block alert alert-danger">
                                                <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                              @endif
                                          </div>
                                      </div>
                                      
                                      

                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Password<span style="color: red"> *</span> :</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="password" autocomplete="off"  name="password" value="{{ old('password') }}" placeholder="Enter Password"/>
                                                @error('password')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                    
                                      <div class="form-group save_cancle">
                                          <div class="col-lg-12 center">
                                              <button class="btn btn-success" type="submit">Save</button>
                                              <button class="btn btn-default" type="button">Cancel</button>
                                          </div>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </section>
               
              <!-- page end-->
          </section>
      </section>
      <!--main content end-->

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

<script src="{{ asset('js/advanced-form-components.js')}}"></script>

<script type="text/javascript">
  
  $('#role').on('change', function(){
            var id = $('#role').val();
            $.ajax({
                type: 'GET',
                url: '{{route('dependlist')}}',
                data: { id :id },
                success: function(data) {
                    getname(id);
                    $('#depend').html(data);
                }
            });
        });
        function getname(id){
            $.ajax({
                type: 'GET',
                url: '{{route('levelname')}}',
                data: { id :id },
                success: function(data) {
    
                    $("#selectlevel").text(data);
                
                }
            });
        }

</script>
@endsection