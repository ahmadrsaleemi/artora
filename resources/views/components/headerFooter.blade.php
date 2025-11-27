<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>ETS</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-reset.css')}}" rel="stylesheet">
    <!--external css-->
    <link href="{{asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css')}}" rel="stylesheet" />
    <!--right slidebar-->
    <link href="{{asset('css/slidebars.css')}}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/style-responsive.css')}}" rel="stylesheet" />

  </head>

  <body>



    @yield('content')
    



    <!-- Ahmad here 232332 -->
  

  <!-- js placed at the end of the document so the pages load faster -->
  <script src="{{asset('js/jquery.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery-ui.min.js')}}"></script>
  <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/fullcalendar/fullcalendar/fullcalendar.min.js')}}"></script>
  <script class="include" type="text/javascript" src="{{asset('js/jquery.dcjqaccordion.2.7.js')}}"></script>
  <script src="{{asset('js/jquery.scrollTo.min.js')}}"></script>
  <script src="{{asset('js/jquery.nicescroll.js')}}" type="text/javascript"></script>
  <script src="{{asset('js/respond.min.js')}}" ></script>
  
  <!--right slidebar-->
  <script src="{{asset('js/slidebars.min.js')}}"></script>
  
    <!--common script for all pages-->
    <script src="{{asset('js/common-scripts.js')}}"></script>
   
  
    <!--script for this page only-->
    <script src="{{asset('js/external-dragging-calendar.js')}}"></script>
   

   
  
  </body>
  </html>