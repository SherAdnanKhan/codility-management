<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')
    <meta name="description" content="Codility Management Application">
    <meta name="author" content="Codility">
    <link rel="shortcut icon"  href="{{asset('images/favicon.png')}}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
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

    @yield('body')
    <!-- JavaScript files-->
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
    </body>
</html>