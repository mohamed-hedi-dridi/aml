@extends('layouts.app')
@php
    $auth = Auth::user();
@endphp
@section('content')
@include('layouts.nav')
@include('layouts.sidebar')
<div class="main-container">
    <div class="pd-ltr-20">
		        @include('layouts.page-title')
                <div class="row">
                    @if(Session::has('status'))
                        <div class="col-md-12 col-sm-12 mb-30">
                            <div class="pd-20 card-box height">
                                @if( Session::get('status') == "success" )
                                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                                @else
                                    <div class="alert alert-danger">{{ Session::get('message') }}</div>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12 col-sm-12 mb-30">
                        <form method="POST" action="{{ route('admin.NationUnis.valider') }}">
                            @csrf
                            <div class="pd-20 card-box">
                                <div class="clearfix mb-30">
                                    <div class="pull-left">
                                        <h4 class="text-blue h4">{{ $titre }} :</h4>
                                    </div>
                                    @can("Import Nation unis")
                                        <div class="pull-right">
                                            <a class="btn btn-outline-danger ml-5" href="{{ route('admin.NationUnis.index') }}">Annuler</a>
                                            <button class="btn btn-primary" type="submit">Valider</a>
                                        </div>
                                    @endcan
                                </div>
                                <input type="hidden" name="url" value="{{ $url }}">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td> # </td>
                                            <td>NAME</td>
                                            <td>NAME_ORIGINAL_SCRIPT</td>
                                            <td>NATIONALITY</td>
                                            <td>TYPE_OF_DOCUMENT</td>
                                            <td>NUMBER</td>
                                            <td>COUNTRY_OF_ISSUE</td>
                                            <td>TYPE_OF_DATE</td>
                                            <td>DATE_BIRTH</td>
                                        </tr>
                                        <tbody>
                                            @foreach ($liste as $key=>$nation)
                                                @php
                                                    $nationality = null ;
                                                    if($nation["NATIONALITY"] != "" && $nation["NATIONALITY"] != null ){
                                                        $nationality = implode(',', $nation["NATIONALITY"]);
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $key+1 }} </td>
                                                    <td> {{ $nation["NAME"] }} </td>
                                                    <td> {{ $nation["NAME_ORIGINAL_SCRIPT"] }} </td>
                                                    <td> {{ $nationality }} </td>
                                                    <td> {{ $nation["TYPE_OF_DOCUMENT"] }} </td>
                                                    <td> {{ $nation["NUMBER"] }} </td>
                                                    <td> {{ $nation["COUNTRY_OF_ISSUE"] }} </td>
                                                    <td> {{ $nation["TYPE_OF_DATE"] }} </td>
                                                    <td> {{ $nation["DATE_BIRTH"] }} </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </thead>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
        @include('layouts.footer')
    </div>
</div>
@endsection
