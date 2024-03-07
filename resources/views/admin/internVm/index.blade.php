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
                                @can("Import Interne")
                                    <div class="pull-right">
                                        <a class="btn btn-outline-info" href="{{ asset('imports/ImportInterne.xlsx') }}">Télécharger un fichier exemple <i class="icon-copy fa fa-cloud-download"></i></a>
                                        <a class="btn btn-primary" data-backdrop="static" data-toggle="modal" href="#" data-target="#login-modal">Import Liste Interne</a>
                                    </div>
                                @endcan
							</div>
                            <table class="table hover multiple-select-row data-table-export nowrap">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Ref</td>
                                        <td>Nom Prénom</td>
                                        <td>CIN</td>
                                    </tr>
                                    @php
                                        $i=1;
                                    @endphp
                                    <tbody>
                                        @foreach ($liste as $intern )
                                        <tr>
                                            <td>{{ $i++}}</td>
                                            <td>{{ $intern->Ref }}</td>
                                            <td>{{ $intern->NOM_PRENOM }}</td>
                                            <td>{{ $intern->CIN }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                @can("Import Interne")
                    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myLargeModalLabel">Import Liste Interne</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="bg-white border-radius-10">
                                        <form method="POST" action="{{ route('admin.Interne.import') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group custom">
                                                <input type="file" class="form-control-file form-control height-auto" accept=".xls,.xlsx"  name="file">
                                                <div class="input-group-append custom">
                                                    <span class="input-group-text"><i class="fa fa-file-excel-o"></i></span>
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
