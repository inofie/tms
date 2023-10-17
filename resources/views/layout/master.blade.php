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
    <!-- <script src="{{ asset('js/jquery.nicescroll.js') }}" type="text/javascript"></script> -->
    @yield('js2')
    <script src="{{ asset('js/respond.min.js') }}" ></script>
      @yield('js3')
    <!--common script for all pages-->
    <script src="{{ asset('js/common-scripts.js') }}"></script>
    @yield('js4')
    <script type="text/javascript">
    // $("html").mouseover(function() {
    //     $("html").getNiceScroll().resize();
    // });
      var h = window.innerHeight - 38;
    $("#main-content").css("min-height",h+"px");
    $( document ).ready(function() {
       setTimeout(function(){
               $('.alert').css("display","none");
        }, 5000);
    });
    </script>
    <script src="{{ asset('js/jquery.nicescroll.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        // $("html").mouseover(function() {
        //     $("html").getNiceScroll().resize();
        // });
      function isMobileView() {
        return $(window).width() < 768; // You can adjust the threshold value as needed
      }
      // Function to dynamically load a JavaScript file
      function loadMobileScript() {
          var script = document.createElement('script');
          script.src = "{{ asset('js/jquery.nicescroll.js') }}"; // Replace with the path to your mobile JavaScript file
          document.body.appendChild(script);
          $("html").mouseover(function() {
              $("html").getNiceScroll().resize();
          });
          $("#sidebar").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled:false, cursorborder: ''});
          $("html").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', spacebarenabled:false,  cursorborder: '', zindex: '1000'});
      }
      // Function to update the display based on the result
      function updateDisplay() {
          if (isMobileView()) {
            $(document).on("click",".fa-bars",function() {
           // $('.fa-bars').click(function () {
            if ($('#sidebar > ul').is(":visible") === true) {
                $('#main-content').css({
                    'margin-left': '0px'
                });
                $('#sidebar').css({
                    'margin-left': '-210px', 'display' : 'none'
                });
                $('#sidebar > ul').hide();
                $("#container").removeClass("sidebar-close");
                $("#container").addClass("sidebar-closed");
            } else {
                $('#main-content').css({
                    'margin-left': '210px'
                });
                $('#sidebar > ul').show();
                $('#sidebar').css({
                    'margin-left': '0', 'display' : 'block'
                });

                $("#container").addClass("sidebar-close");
                $("#container").removeClass("sidebar-closed");
            }
            });
          } else {
              loadMobileScript();
          }
      }
      // Check and update the display when the page loads and when it's resized
      $(document).ready(function () {
          updateDisplay(); // Check on page load
      });
    </script>
    @yield('last')
  </body>
</html>
