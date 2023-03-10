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
    <link rel="shortcut icon"  href="{{asset('images/favicon.png')}}"/>
    <link rel="stylesheet" href="{{asset('styles/bootstrap-iso.css')}}">
    <!-- Bootstrap CSS-->
    {{--<link rel="stylesheet" href="{{asset('styles/styles.css')}}">--}}
    @yield('page_styles')
    <link rel="stylesheet" href="{{asset('styles/bootstrap.min.css')}}">

    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="{{asset('styles/fontastic.css')}}">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
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
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
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
                <ul id="side-main-menu" class="side-menu list-unstyled <?php
                if (\Route::current()->getName() =='profile.index' || \Route::current()->getName() == 'register' || \Route::current()->getName() == 'register.admin.form' || \Route::current()->getName()== 'admin.list'){
                    echo 'show';
                }
                ?>
                        "
                >
                    <li {{\Route::current()->getName() == 'admin.home'?"class=active ":''}} {{\Route::current()->getName() == 'employee.home'?"class=active ":''}}><a href="{{\Auth::user()->isAdmin() ? route('admin.home'): route('employee.home')}}">
                            <i class="fa fa-home"></i>Dashboard
                        </a>
                    </li>
                    @if (\Auth::user()->isEmployee())
                        <li {{\Route::current()->getName() == 'task.index'?"class=active ":''}}><a href="{{route('task.index')}}" >
                                <i class="fa fa-tasks"></i>Task Management
                            </a>
                        </li>
                        <li {{\Route::current()->getName() == 'attendance.index'?"class=active ":''}}><a href="{{route('attendance.index')}}">
                                <i class="fa fa-user-tie"></i>Attendance
                            </a>
                        </li>
                    <li {{\Route::current()->getName() == 'view.time.tracking'?"class=active ":''}}><a href="{{route('view.time.tracking')}}">Time Tracking </a></li>
                        <li {{\Route::current()->getName() == 'request.index'?"class=active ":''}}><a href="{{route('request.index')}}">Request For Leave Approval </a></li>
                        @if (!(\Auth::user()->checkHr()))
                        <li {{\Route::current()->getName() == 'question.page'?"class=active ":''}} {{\Route::current()->getName() == 'searchQuestionByCategory'?"class=active ":''}}><a href="{{route('question.page')}}"><i class="fa fa-search"></i>Search Question</a></li>
                            @endif
                    @endif
                @if (\Auth::user()->isAdmin())
                        <li {{\Route::current()->getName() == 'email_template.index'?"class=active ":''}}><a href="{{route('email_template.index')}}"><i class="fa fa-file"></i>Email Templates</a></li>
                        <li><a href="#exampledropdownDropdown" <?php
                        if (\Route::current()->getName() =='profile.index' || \Route::current()->getName() == 'register' || \Route::current()->getName() == 'register.admin.form' || \Route::current()->getName()== 'admin.list'){

                            echo "aria-expanded='true'";
                        }
                        ?> data-toggle="collapse">
                            <i class="fa fa-users"></i> Admin &nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                        </a>
                        <ul id="exampledropdownDropdown" class="collapse list-unstyled <?php
                        if (\Route::current()->getName() =='profile.index' || \Route::current()->getName() == 'register' || \Route::current()->getName() == 'register.admin.form' || \Route::current()->getName()== 'admin.list'){
                            echo 'show';
                        }
                        ?>
                                "
                        >
                            <li {{\Route::current()->getName() == 'profile.index'?"class=active ":''}}><a href="{{route('profile.index')}}"><i class="fa fa-users"></i>Employee Lists</a></li>
                            <li {{\Route::current()->getName() == 'register'?"class=active ":''}}><a href="{{route('register')}}"><i class="fa fa-user"></i>Employee Register</a></li>
                            <li {{\Route::current()->getName() == 'register.admin.form'?"class=active ":''}}><a href="{{route('register.admin.form')}}"><i class="fa fa-user-circle"></i>Administrator Register</a></li>
                            <li {{\Route::current()->getName() == 'admin.list'?"class=active ":''}}><a href="{{route('admin.list')}}"><i class="fa fa-users"></i>Administrator List</a></li>

                        </ul>
                    </li>
                        <li><a href="#exampledropdownDropdownAttendance"  <?php
                            if (\Route::current()->getName() =='attendance.index' || \Route::current()->getName() == 'inform.index' || \Route::current()->getName() == 'task.index' || \Route::current()->getName()== 'upload.cvs' || \Route::current()->getName() == 'request.index'){

                                echo "aria-expanded='true'";
                            }
                            ?> data-toggle="collapse">
                                <i class="fa fa-users"></i> Attendance &nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                            </a>
                            <ul id="exampledropdownDropdownAttendance" class="collapse list-unstyled <?php
                            if (\Route::current()->getName() =='attendance.index' || \Route::current()->getName() == 'inform.index' || \Route::current()->getName() == 'task.index' || \Route::current()->getName()== 'upload.cvs' || \Route::current()->getName() == 'request.index'){
                                echo 'show';
                            }
                            ?>
                                    "
                            >
                                <li {{\Route::current()->getName() == 'attendance.index'?"class=active ":''}}><a href="{{route('attendance.index')}}"><i class="fa fa-user-tie"></i>Attendance</a></li>
                                <li {{\Route::current()->getName() == 'inform.index'?"class=active ":''}}><a href="{{route('inform.index')}}"><i class="fa fa-info-circle"></i>Employee Inform</a></li>
                                <li {{\Route::current()->getName() == 'task.index'?"class=active ":''}}><a href="{{route('task.index')}}"><i class="fa fa-tasks"></i>Task Management</a></li>
                                <li {{\Route::current()->getName() == 'upload.cvs'?"class=active ":''}}><a href="{{route('upload.cvs')}}"><i class="fa fa-file-excel"></i>Upload CSV</a></li>
                                <li {{\Route::current()->getName() == 'request.index'?"class=active ":''}}><a href="{{route('request.index')}}">Leave Request</a></li>
                            </ul>
                        </li>


                        <li><a href="#exampledropdownDropdowns" <?php
                            if (\Route::current()->getName() =='view.admin.report' || \Route::current()->getName() == 'view.admin.report.compensatory' || \Route::current()->getName() == 'view.admin..monthly.report' || \Route::current()->getName()== 'view.admin.inaccuracy.report' || \Route::current()->getName() == 'view.time.tracking' || \Route::current()->getName() == 'admin.report.search' || \Route::current()->getName() == 'admin.monthly.report.search' || \Route::current()->getName() == 'admin.monthly.inaccuracy.search' || \Route::current()->getName() ==  'time.tracking.search'){
                                echo "aria-expanded='true'";
                            }
                            ?>
                            data-toggle="collapse" >
                                <i class="fa fa-users"></i> Reports &nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                            </a>
                            <ul id="exampledropdownDropdowns" class="collapse list-unstyled
                            <?php
                                if (\Route::current()->getName() =='view.admin.report' || \Route::current()->getName() == 'view.admin.report.compensatory' || \Route::current()->getName() == 'view.admin..monthly.report' || \Route::current()->getName()== 'view.admin.inaccuracy.report' || \Route::current()->getName() == 'view.time.tracking' || \Route::current()->getName() == 'admin.report.search' || \Route::current()->getName() == 'admin.monthly.report.search' || \Route::current()->getName() == 'admin.monthly.inaccuracy.search' || \Route::current()->getName() ==  'time.tracking.search'){
                             echo 'show';
                                }
                                ?>
                            "
                            >
                                <li {{\Route::current()->getName() == 'view.admin.report'?"class=active ":''}} {{\Route::current()->getName() == 'admin.report.search'?"class=active ":''}} ><a href="{{route('view.admin.report')}}"><i class="fa fa-file"></i>Generate Leave Monthly</a></li>
                                <li {{\Route::current()->getName() == 'view.admin.report.compensatory'?"class=active ":''}}><a href="{{route('view.admin.report.compensatory')}}"><i class="fa fa-file"></i>Generate Leave Yearly</a></li>
                                <li {{\Route::current()->getName() == 'view.admin..monthly.report'?"class=active ":''}} {{\Route::current()->getName() == 'admin.monthly.report.search'?"class=active ":''}}><a href="{{route('view.admin..monthly.report')}}"><i class="fa fa-file"></i>Monthly Hours Detail</a></li>
                                <li {{\Route::current()->getName() == 'view.admin.inaccuracy.report'?"class=active ":''}} {{\Route::current()->getName() == 'admin.monthly.inaccuracy.search'?"class=active ":''}}><a href="{{route('view.admin.inaccuracy.report')}}"><i class="fa fa-file"></i>Monthly Inaccuracy  </a></li>
                                <li {{\Route::current()->getName() == 'view.time.tracking'?"class=active ":''}} {{\Route::current()->getName() == 'time.tracking.search'?"class=active ":''}}><a href="{{route('view.time.tracking')}}"><i class="fa fa-clock"></i>Time Tracking </a></li>
                            </ul>
                        </li>
                        <li {{\Route::current()->getName() == 'project.index'?"class=active ":''}}><a href="{{route('project.index')}}"><i class="fa fa-file"></i>Projects</a></li>

                    @endif
                    @if (\Auth::user()->checkHr() || \Auth::user()->isAdmin())
                    <li {{\Route::current()->getName() == 'applicant_list'?"class=active ":''}}><a href="{{route('applicant_list')}}" ><i class="fa fa-male"></i>Applicants list</a></li>
                        <li><a href="#qna" <?php
                            if (\Route::current()->getName() =='category.index' || \Route::current()->getName() == 'question-answers.index' || \Route::current()->getName() == 'print.view' || \Route::current()->getName()== 'question.page' || \Route::current()->getName()=='searchQuestionByCategory'){
                                echo "aria-expanded='true'";
                            }
                            ?> data-toggle="collapse">
                                <i class="fa fa-question-circle"></i> Question&Answer &nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                            </a>
                            <ul id="qna" class="collapse list-unstyled <?php
                            if (\Route::current()->getName() =='category.index' || \Route::current()->getName() == 'question-answers.index' || \Route::current()->getName() == 'print.view' || \Route::current()->getName()== 'question.page' || \Route::current()->getName()=='searchQuestionByCategory' || \Route::current()->getName() == 'searchQuestionByCategoryAdmin' ){
                                echo 'show';
                            }
                            ?>
                                    "
                            >
                                <li {{\Route::current()->getName() == 'category.index'?"class=active ":''}}><a href="{{route('category.index')}}"><i class="fa fa-list-alt"></i>Categories</a></li>
                                <li {{\Route::current()->getName() == 'question-answers.index'?"class=active ":''}} {{\Route::current()->getName() == 'searchQuestionByCategoryAdmin'?"class=active ":''}}><a href="{{route('question-answers.index')}}"><i class="fa fa-question-circle"></i>Question Answers</a></li>
                                <li {{\Route::current()->getName() == 'print.view'?"class=active ":''}}><a href="{{route('print.view')}}"><i class="fa fa-print"></i>Print QuestionAnswer</a></li>
                            @if(\Auth::user()->isEmployee() || \Auth::user()->checkHr())
                                <li {{\Route::current()->getName() == 'question.page'?"class=active ":''}} {{\Route::current()->getName() == 'searchQuestionByCategory'?"class=active ":''}}><a href="{{route('question.page')}}"><i class="fa fa-search"></i>Search Question</a></li>
                            @endif
                            </ul>
                        </li>
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
                                 <img style="" class="img-responsive " src="{{asset('images/logo.svg')}}" alt="Codility Management">
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
                                        <li {{\Route::current()->getName() == 'screen.capture.page'?"class=active ":''}}><a rel="nofollow" href="{{route('screen.capture.page')}}" class="dropdown-item d-flex">Manage Screen Capture</a></li>
                                    <li {{\Route::current()->getName() == 'leave.index'?"class=active ":''}}><a rel="nofollow" href="{{route('leave.index')}}" class="dropdown-item d-flex">Manage Leaves</a></li>
                                    <li {{\Route::current()->getName() == 'timetable.index'?"class=active ":''}}><a  rel="nofollow" href="{{route('timetable.index')}}" class="dropdown-item d-flex">Manage TimeTable</a></li>
                                    @endif
                                    <li {{\Route::current()->getName() == 'profile.edit'?"class=active ":''}}><a rel="nofollow" href="{{route('profile.edit',\Auth::user()->id )}}" class="dropdown-item d-flex">Profile</a></li>
                                    <li {{\Route::current()->getName() == 'logout'?"class=active ":''}}><a href="{{ route('logout') }}" onclick="event.preventDefault();
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