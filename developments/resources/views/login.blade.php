<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="">

    <meta name="author" content="Mosaddek">

    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <link rel="shortcut icon" href="img/favicon.png">



    <title>Login | TMS</title>



    <!-- Bootstrap core CSS -->

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <link href="{{asset('css/bootstrap-reset.css')}}" rel="stylesheet">

    <!--external css-->

    <link href="{{asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />

    <!-- Custom styles for this template -->

    <link href="{{asset('css/style.css')}}" rel="stylesheet">

    <link href="{{asset('css/style-responsive.css')}}" rel="stylesheet" />



    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->

    <!--[if lt IE 9]>

    <script src="{{asset('js/html5shiv.js')}}"></script>

    <script src="{{asset('js/respond.min.js')}}"></script>

    <![endif]-->

</head>



  <body class="login-body">



    <div class="container">



      <form class="form-signin" method="post" action="{{ route('logincheck') }}">
        @csrf
        <h2 class="form-signin-heading">Swayamprakash Login</h2>

      

        <div class="login-wrap">

            <input type="text" name="username" class="form-control" required="required" placeholder="Enter Your Username" autofocus style="margin-bottom:5%;color:#000;" value="{{old('username')}}">

            @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <input type="password" name="password" class="form-control" required="required" placeholder="Password" style="color: #000;">


               @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                   
                        <strong>{{ $message }}</strong>
                </div>
             @endif

              

            </label>
            {{-- <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button> --}}

            <button  type="Submit" class="btn btn-lg btn-login btn-block" >Sign in</button>

            <div class="text-center"> <a data-toggle="modal" href="#myModal"> Forgot Password?</a></div>


        </div>

             </form>

          <!-- Modal -->

          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">

              <div class="modal-dialog">

                  <div class="modal-content">

                      <div class="modal-header">

                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                          <h4 class="modal-title">Forgot Password ?</h4>

                      </div>

                      <div class="modal-body">

                          <p>Contact To Administration.</p>

                         {{--  <input type="text" name="email" required="required" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix"> --}}



                      </div>

                      {{-- <div class="modal-footer">

                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>

                          <button class="btn btn-success" type="button">Submit</button>

                      </div> --}}

                  </div>

              </div>

          </div>

          <!-- modal -->



 



    </div>







    <!-- js placed at the end of the document so the pages load faster -->

    <script src="{{asset('js/jquery.js')}}"></script>

    <script src="{{asset('js/bootstrap.min.js')}}"></script>





  </body>

</html>

