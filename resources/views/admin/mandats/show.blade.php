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

					<div class="invoice-box">
						<div class="invoice-header">
							<div class="logo text-center">
								<img src="{{ asset('vendors/images/deskapp-logo.png') }}" alt="">
							</div>
						</div>
						<h4 class="text-center mb-30 weight-600">{{ strtoupper($titre) }}</h4>
						<div class="row pb-30">
                            <div class="col-md-12">
                                <div class="dropdown mb-10">
                                    @php
                                        if(@$latestSuspect->statut){
                                            $statut = $mandat->getCSS(@$latestSuspect->statut);
                                        }else{
                                            $statut = ["class"=>"warning" , "text"=>"En cours"];
                                        }
                                    @endphp
                                    <a class="btn btn-{{ @$statut["class"] }} @can('Update Status Mandat')
                                            @if(@$latestSuspect->statut == 1 ) dropdown-toggle @endif @endcan" role="button" href="#" data-toggle="dropdown">
                                        {{ @$statut["text"] }}
                                    </a>
                                    @if(@$latestSuspect->statut == 1 )
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item change" data-status="0" data-mandat="{{ $mandat->code }}" data-id="{{ Auth::user()->id }}">En Cours</a>
                                            <a class="dropdown-item change" data-status="3" data-mandat="{{ $mandat->code }}" data-id="{{ Auth::user()->id }}">Bloquer Définitivement</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
							<div class="col-md-6">
								<p class="font-14 mb-5">Date : <strong class="weight-600">{{ $mandat->date }}</strong></p>
								<p class="font-14 mb-5">Type Mandat:
                                    <strong class="weight-600">
                                        @if($mandat->type_mandat == "WU")
                                            Western Union
                                        @else
                                            {{ $mandat->type_mandat }}
                                        @endif
                                    </strong>
                                </p>
                                @if($mandat->email_agent != null)
                                    <p class="font-14 mb-5">Email Agent: <strong class="weight-600">{{ $mandat->email_agent }}</strong></p>
                                @endif
                                <p class="font-14 mb-5">Nombre d'itération : <strong class="weight-600">{{ $mandat->iteration() }}</strong></p>
							</div>
							<div class="col-md-6">
								<div class="text-right">
									<p class="font-14 mb-5">N° Identité Input:  <strong class="weight-600">{{ $mandat->input_identity }}</strong> </p>
									<p class="font-14 mb-5">N° Identité Output: <strong class="weight-600">{{ $mandat->output_identity }}</strong></p>
									<p class="font-14 mb-5">Type d'identité: <strong class="weight-600">{{ $mandat->identity_type }}</strong></p>
									<p class="font-14 mb-5">Date de naissance: <strong class="weight-600">{{ $mandat->birthday }}</strong></p>
								</div>
							</div>
						</div>
						<div class="invoice-desc pb-30">
							<div class="invoice-desc-head row clearfix">
                                <div class="col-1 text-center"></div>
								<div class="col-3 text-center">Blacklisted</div>
								<div class="col-3 text-center">Interne</div>
                                <div class="col-3 text-center">CTAF</div>
								<div class="col-1 text-center"></div>
							</div>
							<div class="invoice-desc-body row">
                                <div class="col-1 text-center"></div>
								<div class="col-3 text-center">
                                    @if (strtoupper($mandat->blacklisted) == "TRUE")
                                        <span class="badge badge-pill badge-warning">True</span>
                                    @else
                                        <span class="badge badge-pill badge-success">False</span>
                                    @endif
                                </div>
								<div class="col-3 text-center">
                                    @if (strtoupper($mandat->interne) == "TRUE")
                                        <span class="badge badge-pill badge-warning">True</span>
                                    @else
                                        <span class="badge badge-pill badge-success">False</span>
                                    @endif
                                </div>
								<div class="col-3 text-center">
                                    @if (strtoupper($mandat->CTAF) == "TRUE")
                                        <span class="badge badge-pill badge-warning">True</span>
                                    @else
                                        <span class="badge badge-pill badge-success">False</span>
                                    @endif
                                </div>
                                <div class="col-1 text-center"></div>
                                <div class="col-12" style="margin-top: 5%">
                                    <h5>Liste d'iterations</h5>
                                </div>
                                <div class="col-12" style="margin-top: 5%">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <td><strong> N° d'Identité </strong></td>
                                                <td><strong> N° d'Identité </strong> </td>
                                                <td><strong> Date </strong></td>
                                                <td><strong> État </strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ( $mandat->getStatusListe() as $key=>$liste)
                                                @php
                                                    $statutiteration = $mandat->getCSS($liste->statut);
                                                @endphp
                                                <tr>
                                                    @if($liste->user_id == null)
                                                    <td> {{ $liste->input_identity }} </td>
                                                    <td> {{ $liste->output_identity }} </td>
                                                    <td> {{ $liste->date_modif }} </td>
                                                    <td> <span class="badge badge-pill badge-{{ @$statutiteration["class"] }}"> {{ @$statutiteration["text"] }} </span></td>
                                                    @else
                                                    <td colspan="2"> <strong>{{ $liste->user->name }}</strong>  a changé le statut du mondat </td>
                                                    <td> {{ $liste["date_modif"] }} </td>
                                                    <td> <span class="badge badge-pill badge-{{ @$statutiteration["class"] }}"> {{ @$statutiteration["text"] }} </span></td>
                                                    @endif
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
							</div>
						</div>
					</div>

                    </div>
                </div>
        @include('layouts.footer')
    </div>
</div>
<script>

</script>
@endsection
