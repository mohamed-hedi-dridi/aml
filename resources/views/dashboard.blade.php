@extends('layouts.app')

@section('content')
@include('layouts.nav')
@include('layouts.sidebar')
<div class="main-container">
    <div class="pd-ltr-20">
        <div class="card-box pd-20 height-100-p mb-30">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <img src="vendors/images/banner-img.png" alt="">
                </div>
                <div class="col-md-8 mt-8">
                    <h4 class="font-20 weight-500 mb-10 text-capitalize">
                        Welcome back <div class="weight-600 font-30 text-blue">{{ Auth::user()->name }}!</div>
                    </h4>
                    <p class="font-18 max-width-600"></p>
                </div>
            </div>
        </div>
        <div class="card-box pd-20 height-100-p mb-30 d-none">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="h4 pd-20"> List benifisaire on 5 mandats ou plus la dernier semaine   </h2>
                </div>

            </div>
        </div>
    @include('layouts.footer')
    </div>
</div>
@endsection
