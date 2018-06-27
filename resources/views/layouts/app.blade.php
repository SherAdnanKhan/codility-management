<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('title')
    <meta name="description" content="Codility Management Application">
    <meta name="author" content="Codility">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="shortcut icon"  href="{{asset('images/logo.png')}}"/>
    <link rel="stylesheet" href="{{asset('styles/bootstrap-iso.css')}}">
    <!-- Bootstrap CSS-->
    {{--<link rel="stylesheet" href="{{asset('styles/styles.css')}}">--}}
    @yield('page_styles')
    <link rel="stylesheet" href="{{asset('styles/bootstrap.min.css')}}">

    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="{{asset('styles/fontastic.css')}}">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="{{asset('styles/grasp_mobile_progress_circle-1.0.0.min.css')}}">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="{{asset('styles/jquery.mCustomScrollbar.css')}}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{asset('styles/style.default.css ')}}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{asset('styles/custom.css')}}">
    <!-- Favicon-->
    {{--<link rel="shortcut icon" href="img/favicon.ico">--}}
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>
<body>
<!-- Side Navbar -->
    <nav class="side-navbar">
        <div class="side-navbar-wrapper">
            <!-- Sidebar Header    -->
            <div class="sidenav-header d-flex align-items-center justify-content-center">
                <!-- User Info-->
                <div class="sidenav-header-inner text-center">
                    <img src="{{url('/')}}/images/user/{{\Auth::user()->photos ? \Auth::user()->photos->path :'avatar_default.png'}}" alt="person" class="img-fluid rounded-circle">
                    <h2 class="h5">{{\Auth::user()->name }}</h2><span> {{\Auth::user()->designation ? \Auth::user()->designation  :"Administrator"}}  </span>
                </div>
                <!-- Small Brand information, appears on minimized sidebar-->
                <div class="sidenav-header-logo">
                    <a href="{{\Auth::user()->isAdmin() ? route('admin.home'): route('employee.home')}}" class="brand-small text-center">
                        <strong>C</strong><strong class="text-primary">M</strong>
                    </a>
                </div>
            </div>
            <!-- Sidebar Navigation Menus-->
            <div class="main-menu">
                <h5 class="sidenav-heading">MAIN MENU</h5>
                <ul id="side-main-menu" class="side-menu list-unstyled">
                    <li><a href="{{\Auth::user()->isAdmin() ? route('admin.home'): route('employee.home')}}">
                            <i class="fa fa-home"></i>Dashboard
                        </a>
                    </li>
                    <li><a href="{{route('attendance.index')}}">
                            <i class="fa fa-home"></i>Attendance
                        </a>
                    </li>
                    @if (\Auth::user()->isAdmin())
                    <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse">
                            <i class="fa fa-user"></i> Employees &nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                        </a>
                        <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                            <li><a href="{{route('profile.index')}}">Employee Lists</a></li>
                            <li><a href="{{route('register')}}">Employee Register</a></li>
                        </ul>
                    </li>
                    <li><a href="{{route('inform.index')}}"><i class="fa fa-info-circle"></i>Employee Inform</a></li>
                    <li><a href="{{route('register.admin.form')}}"><i class="fa fa-user-circle"></i>Admin Register</a></li>
                    <li><a href="{{route('applicant_list')}}"><i class="fa fa-user-circle"></i>Applicants list</a></li>
                    <li><a href="{{route('upload.cvs')}}"><i class="fa fa-file-excel"></i>Upload CSV</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <div class="page">
        <!-- navbar-->
        <header class="header">
            <nav class="navbar">
                <div class="container-fluid">
                    <div class="navbar-holder d-flex align-items-center justify-content-between">
                        <div class="navbar-header">
                            <a id="toggle-btn" href="#" class="menu-btn">
                                <i class="fa fa-bars"> </i>
                            </a>
                            <a href="{{\Auth::user()->isAdmin() ? route('admin.home'): route('employee.home')}}" class="navbar-brand">
                             <div class="navbar-brand d-none d-md-inline-block">
                                 <img style="max-width: 70%" class="img-responsive " src="{{asset('images/logo.png')}}" alt="Codility Management">
                             </div>
                            </a>
                        </div>
                        <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                           <!-- Log out-->
                            <li class="nav-item dropdown">
                                <a id="messages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link">
                                    <span>Setting &nbsp;</span>
                                    <i class="fa fa-caret-down"></i>
                                </a>
                                <ul aria-labelledby="notifications" class="dropdown-menu">
                                    @if (\Auth::user()->isAdmin())
                                    <li><a rel="nofollow" href="{{route('leave.index')}}" class="dropdown-item d-flex">Manage Leaves</a></li>
                                    <li><a  rel="nofollow" href="{{route('timetable.index')}}" class="dropdown-item d-flex">Manage TimeTable</a></li>
                                    @endif
                                    <li><a rel="nofollow" href="{{route('profile.edit',\Auth::user()->id )}}" class="dropdown-item d-flex">Profile</a></li>
                                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();" class="dropdown-item d-flex">Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    @yield('body')
</div>
<!-- JavaScript files-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="{{asset('scripts/jquery.min.js')}}"></script>
<script src="{{asset('scripts/popper.min.js')}}"> </script>
<script src="{{asset('scripts/bootstrap.min.js')}}"></script>
<script src="{{asset('scripts/grasp_mobile_progress_circle-1.0.0.min.js')}}"></script>
<script src="{{asset('scripts/jquery.cookie.js')}}"> </script>
<script src="{{asset('scripts/Chart.min.js')}}"></script>
<script src="{{asset('scripts/jquery.validate.min.js')}}"></script>
<script src="{{asset('scripts/jquery.mCustomScrollbar.concat.min.js')}}"></script>
{{--<script src="{{asset('scripts/charts-home.js')}}"></script>--}}
<!-- Main File-->
<script src="{{asset('scripts/front.js')}}"></script>
@yield('page_scripts')
</body>
</html>