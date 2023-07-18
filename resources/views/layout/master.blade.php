<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="">

    <meta name="author" content="Mosaddek">

    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">



      <!-- CSRF Token -->



    <meta name="csrf-token" content="{{ csrf_token() }}">



     <title>@yield('title')</title>



      @yield('css1')


    <!-- Bootstrap core CSS -->

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/bootstrap-reset.css') }}" rel="stylesheet">

    <!--external css-->
   @yield('css2')

    <link href="{{ asset('assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

 

    <!-- Custom styles for this template -->

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <link href="{{ asset('css/style-responsive.css') }}" rel="stylesheet" />

  @yield('css3')

    

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->

    <!--[if lt IE 9]>

      <script src="{{ asset('js/html5shiv.js') }}"></script>

      <script src="{{ asset('js/respond.min.js') }}"></script>

    <![endif]-->

    @yield('js0')

  </head>



  <body style="overflow-y:scroll;
    position:relative;">


  <section id="container" class="">

      @include('layout.header')

      <!--sidebar start-->

      @include('layout.menu')

      <!--sidebar end-->

       @yield('content')


     @include('layout.footer')

  </section>



    <!-- js placed at the end of the document so the pages load faster -->

    

      @yield('js1')



    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script class="include" type="text/javascript" src="{{ asset('js/jquery.dcjqaccordion.2.7.js') }}"></script>

    <script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>

    <script src="{{ asset('js/jquery.nicescroll.js') }}" type="text/javascript"></script>

    @yield('js2')

    <script src="{{ asset('js/respond.min.js') }}" ></script>

      @yield('js3')

    <!--common script for all pages-->

    <script src="{{ asset('js/common-scripts.js') }}"></script>

    @yield('js4')


    <script type="text/javascript">
      var h = window.innerHeight - 38;
  
    //$("#main-content").css("min-height",h+"px");


   

    $( document ).ready(function() {

       setTimeout(function(){
               $('.alert').css("display","none");
        }, 5000);
    
});

    </script>
    @yield('last')

  </body>

</html>

