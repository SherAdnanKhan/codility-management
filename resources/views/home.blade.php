@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} |Employee Dashboard </title>
@endsection
@section('body')
    <div class="container" >
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    {{--<div class="panel-heading">Dashboard</div>--}}
                    <div class="panel-body text-capitalize text-center" >
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                            <h1 style="margin: 102px;">
                                <a  style="font-size: 99px;color: #3c3b39;text-decoration: none;" href="" class="typewrite" data-period="2000" data-type='[ "Hye ! {{\Auth::user()->name}}."," Welcome To.", "Codility Office Management System." ]'>
                                    <span class="wrap"></span>
                                </a>
                            </h1>
                            @if (\Auth::user()->checkHr() || \Auth::user()->isAdmin())
                                @php
                                $interview=\App\Interview::whereBetween('date',[\Carbon\Carbon::now()->startOfDay()->timestamp,\Carbon\Carbon::now()->endOfDay()->timestamp])->first();
                                @endphp
                            @if($interview != null)
                                @php
                                    $interviews=\App\Interview::whereBetween('date',[\Carbon\Carbon::now()->startOfDay()->timestamp,\Carbon\Carbon::now()->endOfDay()->timestamp])->get();

                                @endphp
                                <div class="alert alert-success" style="text-align: left">
                                    These Applicants have an interview Today
                                @foreach($interviews as $items)
                                    
                                    <li>{{$items->applicant->firstName}} {{$items->applicant->middleName?$items->applicant->middleName:''}} {{$items->applicant->LastName?$items->applicant->LastName:''}} On : {{\Carbon\Carbon::createFromTimestamp($items->date)}}</li>
                                    @endforeach
                                </div>
                              @endif
                            @endif
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="row">--}}
            {{--<div class="col-md-12">--}}
                {{--<img src="{{asset('images/banneremploye.jpg')}}">--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
@endsection
@section('page_scripts')

    <script type="text/javascript">
        var TxtType = function(el, toRotate, period) {
            this.toRotate = toRotate;
            this.el = el;
            this.loopNum = 0;
            this.period = parseInt(period, 10) || 2000;
            this.txt = '';
            this.tick();
            this.isDeleting = false;
        };

        TxtType.prototype.tick = function() {
            var i = this.loopNum % this.toRotate.length;
            var fullTxt = this.toRotate[i];

            if (this.isDeleting) {
                this.txt = fullTxt.substring(0, this.txt.length - 1);
            } else {
                this.txt = fullTxt.substring(0, this.txt.length + 1);
            }

            this.el.innerHTML = '<span class="wrap">'+this.txt+'</span>';

            var that = this;
            var delta = 200 - Math.random() * 100;

            if (this.isDeleting) { delta /= 2; }

            if (!this.isDeleting && this.txt === fullTxt) {
                delta = this.period;
                this.isDeleting = true;
            } else if (this.isDeleting && this.txt === '') {
                this.isDeleting = false;
                this.loopNum++;
                delta = 500;
            }

            setTimeout(function() {
                that.tick();
            }, delta);
        };

        window.onload = function() {
            var elements = document.getElementsByClassName('typewrite');
            for (var i=0; i<elements.length; i++) {
                var toRotate = elements[i].getAttribute('data-type');
                var period = elements[i].getAttribute('data-period');
                if (toRotate) {
                    new TxtType(elements[i], JSON.parse(toRotate), period);
                }
            }
            // INJECT CSS
            var css = document.createElement("style");
            css.type = "text/css";
            css.innerHTML = ".typewrite > .wrap { border-right: 0.08em solid #3c3b39}";
            document.body.appendChild(css);
        };

    </script>
@endsection