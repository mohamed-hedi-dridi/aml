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
                        <div class="pd-20 card-box height-100-p">
							<div class="clearfix mb-30">
								<div class="pull-left">
									<h4 class="text-blue h4">{{ $titre }} :</h4>
								</div>
                                @can("Add Type Mandats")
                                    <div class="pull-right">
                                        <a class="btn btn-primary" data-backdrop="static" data-toggle="modal" href="#" data-target="#login-modal">Ajouter Type Mandat</a>
                                    </div>
                                @endcan
							</div>
                            <table class="table hover multiple-select-row data-table-export nowrap">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Nom</td>
                                        <td>Code</td>
                                        <td>International</td>
                                    </tr>
                                    <tbody>
                                        @foreach ($mandats as $key=>$mandat )
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $mandat->nom }}</td>
                                                <td>{{ $mandat->code }}</td>
                                                <td>@if ($mandat->international == 1)
                                                        <span class="badge badge-pill badge-warning">OUI </span>
                                                    @else
                                                        <span class="badge badge-pill badge-success">NON</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                @can("Add Type Mandats")
                    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myLargeModalLabel">Ajouter Type Mandat</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="bg-white border-radius-10">
                                        <form method="POST" action="{{ route('admin.mandats.store') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group @error('nom')has-danger @enderror">
                                                <label class="form-control-label col-sm-12 col-form-label  @error('nom') form-control-label-danger @enderror">Nom Type Mandat</label>
                                                <input type="text" class="form-control-file form-control  @error('nom') form-control-danger @enderror height-auto" required name="nom" placeholder="Nom Type Mandat">
                                                @error('nom') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                            </div>
                                            <div class="form-group @error('code')has-danger @enderror">
                                                <label class="form-control-label col-sm-12 col-form-label @error('code') form-control-label-danger @enderror">Code Type Mandat</label>
                                                <input type="text" class="form-control-file form-control height-auto @error('code') form-control-danger @enderror" required name="code" placeholder="Code">
                                                @error('code') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                            </div>
                                            <div class="row pb-30">
                                                <div class="col-5">
                                                    <div class="custom-control custom-radio mb-5">
                                                        <input id="customRadio1" class="custom-control-input" checked name="international" type="radio" value="1">
                                                        <label class="custom-control-label" for="customRadio1">International</label>
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="custom-control custom-radio mb-5">
                                                        <input id="customRadio2" class="custom-control-input" name="international" type="radio" value="2">
                                                        <label class="custom-control-label" for="customRadio2">National</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row pb-30">
                                                <div class="col-6">
                                                    <button class="btn btn-danger" type="reset">Annuler</button>
                                                </div>
                                                <div class="col-6">
                                                    <div class="forgot-password"><button class="btn btn-primary" type="submit">Enregistrer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
        @include('layouts.footer')
    </div>
</div>
@endsection
