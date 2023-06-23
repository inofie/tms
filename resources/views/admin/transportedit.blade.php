  @extends('layout.master')

  @section('title')
  Edit Transporter | TMS
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
                               Edit Transporter
                            </header>
                            <div class="panel-body">
                                <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="signupForm" method="post" action="{{ route('transporterupdate') }}" enctype="multipart/form-data">
                                      @csrf
                                      <input type="hidden" name="id" value="{{ $data->id }}">
                                        <div class="form-group ">
                                            <label for="name" class="control-label col-lg-2">Fullname :</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="name" name="name" type="text" value="{{ $data->name }}" placeholder=" Enter Name" />
                                                @error('name')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                      <div class="form-group">
                                            <label class="col-lg-2 control-label">Phone :</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="Phone" name="phone" value="{{ $data->phone }}" placeholder="9874561230"/>

                                                @error('phone')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                          <div class="form-group">
                                            <label class="col-lg-2 control-label">Licence Number :</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="licence_no" name="licence_no" value="{{ $data->licence_no }}"  placeholder="GJ-1234567890123"/>
                                                @error('phone')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>

                                          <div class="form-group">
                                            <label class="col-lg-2 control-label">Truck Number :</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="truck_no" name="truck_no" value="{{ $data->truck_no }}" placeholder="Truck Number"/>
                                                @error('phone')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">PAN Number :</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="pan" name="pan" value="{{ $data->pan }}"  placeholder="PAN Number"/>
                                                 @error('pan')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label class="control-label col-md-2">R.C Book :</label>
                                            <div class="col-md-10">
                                                <input type="file" name="rc_book" id="rc_book" class="item-img file2 ">
                                                <div style=" overflow: hidden; width: 100px; ">
                                                <img src="{{ asset('/uploads') }}/{{ $data->rc_book }}" id="item-img-output2" style="margin-top: 1%;float: left;" width="100px" alt="" class="zoom">
                                                </div>
                                                @error('rc_book')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>

                                          <div class="form-group">
                                            <label class="control-label col-md-2">Pan Card :</label>
                                            <div class="col-md-10">
                                                <input type="file" name="pan_card"  id="pan_card" class="item-img file1 ">
                                                <figure>
                                                <div style=" overflow: hidden; width: 100px; ">
                                                <img src="{{ asset('/uploads') }}/{{ $data->pan_card }}" id="item-img-output1" style="margin-top: 1%;float: left;" width="100px" alt="" class="zoom">
                                                </div>
                                            </figure>
                                                @error('pan_card')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                           <div class="form-group">
                                            <label class="control-label col-md-2">Licence :</label>
                                            <div class="col-md-10">
                                            <input type="file" name="licence" accept="image/png, image/jpeg, image/jpg" id="licence" class="item-img file ">
                                                <figure>
                                                <div style=" overflow: hidden; width: 100px; ">
                                                <img src="{{ asset('/uploads') }}/{{ $data->licence }}" class="zoom" name="avatar" style="margin-top: 1%;float: left;" id="item-img-output" width="100px" alt="">
                                                </div>
                                            </figure>
                                                
                                                
                                                @error('licence')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>

                                     

                                        <div class="form-group ">
                                          <label for="cars" class="control-label col-lg-2">Status :</label>
                                           <div class="col-lg-10">
                                           <select class="form-control" name="status" style="">
                                            @if($data->status == '1')
                                              <option value="0" >Active</option>
                                              <option value="1" selected="selected">InActive</option>
                                            @else 
                                              <option value="0" selected="selected">Active</option>
                                              <option value="1" >InActive</option>
                                            @endif
                                          </select>
                                          </div>
                                      </div>

                                       <br>
                                      <h4><u>Login Details</u></h4>
                                      

                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Username :</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" placeholder="Enter Username"/>
                                                 <input type="hidden" name="oldusername" value="{{ $user->username }}" />
                                                 <input type="hidden" name="user_id" value="{{ $data->user_id }}" />
                                                @error('username')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>


                                          <div class="form-group">
                                            <label class="col-lg-2 control-label">Password :</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" id="password " name="password" placeholder="Enter Password"/>
                                                @error('password')
                                                 <span class="text-danger"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>

                                        
                         

                                        <div class="form-group save_cancle">
                                            <div class="col-lg-12 center">
                                                <button class="btn btn-success" type="submit">Save</button>
                                                <a href="{{route('transporterlist') }}" class="btn btn-default" type="button">Cancel</a>
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
    
    function Addmore(){

      var count = $('.myname').length + 1;

      

      var addrow = '<tr id="row'+count+'"><td><div class="control-label col-lg-10" ><input type="text" class="form-control myname" placeholder="Enter Name" name="name[]"  required="required"></div></td><td><div class="control-label col-lg-10" ><input type="number" class="form-control" name="sorting_id[]"></div></td><td class="text-center"><button onclick="deleterow('+count+')" style="background: #fff;border-radius: 0px;border: 0px;color: red;font-size: 25px;"><i class="fa fa-times"></i></button></tr>';

      $('#last').before(addrow);

    }

     function deleterow(id){

      $("#row"+id).remove();
     }
     function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#item-img-output').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#licence").change(function () {
        readURL(this);
    });
    function readURLL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#item-img-output2').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#rc_book").change(function () {
        readURLL(this);
    });
    function readURLs(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#item-img-output1').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#pan_card").change(function () {
        readURLs(this);
    });

</script>
  @endsection