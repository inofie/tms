@extends('layout.master')

@section('title')
Live Board | Tms
@endsection


<style>

body{background-color: #8f3f65;}
.todo-project {
   /* top: 0;
    bottom: 0;
    overflow-y: hidden;
    overflow-x: auto;*/
    top: 0;
    bottom: 0;
    overflow-y: hidden;
    overflow-x: auto;
    width: 1156px;
    position: absolute;
}
.todo-project .container{
  padding-left: 0;
  padding-right: 0;
}

.card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 15px;
    padding:10px;
    width: 350px;
    margin: 10px 0;
}
.box-height {
    height: 350px;
}
.file-icon{float: right;}

.mt-5{margin-top:15px;}
.text-white{color:#ffffff;}
.bg-dark{background: #101204;}
.bg-danger {background-color: #dc3545;}
.bg-info {background-color: #67bfcc;}
.bg-gray-dark {background-color: #343a40;}
.bg-gray {background-color: #6c757d;}
.bg-primary {background-color: #007bff;}
.bg-teal {background-color: #20c997;}

.bg-danger .project-list ul li {background-color: #e87c87;}
.bg-gray-dark .project-list ul li {background-color: #5a5a5a;}
.bg-gray .project-list ul li {background-color: #a0a6ab;}
.bg-primary .project-list ul li {background-color: #5aaaff;}
.bg-teal .project-list ul li {background-color: #6fdcbc;}

.card-header:first-child {
    border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
}
.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
    padding: 0.75rem 1.25rem;
    position: relative;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 10px;
}
.card-header span i{float: right;}
.box-display{display:flex;}
.card-body {
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1.25rem;
}
.card-body {
    overflow-y: scroll;
    padding: 0;
    position: relative;
}
.project-list {
    width: 95%;
}
.project-list ul li {
    background:#22272b;;
    padding: 1.25rem;
    margin-bottom: 10px;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
}
.card-footer {
    padding: 15px 1.25rem;
    border-top: 1px solid rgba(0,0,0,.125);
    font-size: 15px;
    font-weight: 600;
    margin-top:10px;
}
.card-footer .add-card{cursor: pointer;color: #fff;}
.card-footer .add-card:hover {
    color: #fff;
}
.card-footer:last-child {
    border-radius: 0 0 calc(0.25rem - 1px) calc(0.25rem - 1px);
}

@media only screen and (max-width: 1200px) {
    .card{margin-bottom: 20px;}
}
@media only screen and (max-width: 767px) {
   .todo-project{width: 100%;}
 }
    </style>


 @section('content') 
<body>
<section id="container" class="">
<section id="main-content">
 <section class="wrapper todo-project">     
   <div class="container">
    <div class="row box-display">
      <div class="col-lg-4 col-md-4 col-sm-12">
       <div class="card bg-dark text-white box-height">
        <div class="card-header">Pickup Confirm<span><i class="fa fa-ellipsis-h"></i></span></div>
         <div class="card-body">
          <div class="project-list">
           <ul>@foreach($pickup as $value)
            <li>{{$value->shipment_no}}</li>
            @endforeach
           </ul>
          </div>      
        </div>
      <div class="card-footer">
       <!-- <a class="add-card">+ Add a card</a> -->
       <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
     </div>
    </div>
  </div>
 
  <div class="col-lg-4 col-md-6 col-sm-12">
       <div class="card bg-gray-dark text-white">
        <div class="card-header">Truck Transfer<span><i class="fa fa-ellipsis-h"></i></span></div>
         <div class="card-body">
         <div class="project-list">
         <ul>@foreach($trucktransfer as $value)
         <li>{{$value->shipment_no}}</li>
            @endforeach
           
           </ul> 
          </div>     
         
        </div>
      <div class="card-footer">
       <!-- <a class="add-card">+ Add a card</a> -->
       <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
     </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6 col-sm-12">
       <div class="card bg-gray text-white">
        <div class="card-header">Reach at Company<span><i class="fa fa-ellipsis-h"></i></span></div>
         <div class="card-body">
         <div class="project-list">
           <ul>@foreach($reachcompany as $value)
            <li>{{$value->shipment_no}}</li>
            @endforeach
           </ul>
          </div>      
        </div>
      <div class="card-footer">
       <!-- <a class="add-card">+ Add a card</a> -->
       <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
     </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6 col-sm-12">
       <div class="card bg-danger text-white">
        <div class="card-header">Damage/Missing/Hold<span><i class="fa fa-ellipsis-h"></i></span></div>
         <div class="card-body">
         <div class="project-list">
           <ul>@foreach($damagemissinghold as $value)
            <li>{{$value->shipment_no}}</li>
            @endforeach
           </ul>
          </div>     
        </div>
      <div class="card-footer">
       <!-- <a class="add-card">+ Add a card</a> -->
       <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
     </div>
    </div>
  </div>

 <div class="col-lg-4 col-md-6 col-sm-12">
       <div class="card bg-primary text-white">
        <div class="card-header">Reach at port<span><i class="fa fa-ellipsis-h"></i></span></div>
         <div class="card-body">
         <div class="project-list">
           <ul>@foreach($reachport as $value)
            <li>{{$value->shipment_no}}</li>
            @endforeach
           </ul>
          </div>     
        </div>
      <div class="card-footer">
       <!-- <a class="add-card">+ Add a card</a> -->
       <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
     </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6 col-sm-12">
       <div class="card bg-teal text-white">
        <div class="card-header">Delivered<span><i class="fa fa-ellipsis-h"></i></span></div>
         <div class="card-body">
         <div class="project-list">
           <ul>@foreach($delivered as $value)
            <li>{{$value->shipment_no}}</li>
            @endforeach
           </ul>
          </div>     
        </div>
      <div class="card-footer">
       <!-- <a class="add-card">+ Add a card</a> -->
       <div class="file-icon"><i class="fa fa-file-text-o"></i></div>
     </div>
    </div>
  </div>


  
    </div>
    </div>  
    </section>
    </section>
    </section>

    @yield('js1')



<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<script class="include" type="text/javascript" src="{{ asset('js/jquery.dcjqaccordion.2.7.js') }}"></script>



@yield('js2')

<script src="{{ asset('js/respond.min.js') }}" ></script>

  @yield('js3')

<!--common script for all pages-->

<script src="{{ asset('js/common-scripts.js') }}"></script>

@yield('js4')

</body>
@endsection
  


