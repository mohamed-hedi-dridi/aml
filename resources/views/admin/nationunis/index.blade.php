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
                                @can("Import Nation unis")
                                    <div class="pull-right">
                                        <!--<a class="btn btn-outline-info" href="{{ asset('imports/IdentityCheck.xlsx') }}">Télécharger un fichier exemple <i class="icon-copy fa fa-cloud-download"></i></a>-->
                                        <a class="btn btn-primary" data-backdrop="static" data-toggle="modal" href="#" data-target="#login-modal">Import Nation Unis Liste</a>
                                    </div>
                                @endcan
							</div>
                            <table class="table hover multiple-select-row data-table-export nowrap">
                                <thead>
                                    <tr>
                                        <td>#</td>
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
                                            <tr>
                                                <td>{{ $key+=1 }}</td>
                                                <td>{{ $nation->NAME }}</td>
                                                <td>{{ $nation->NAME_ORIGINAL_SCRIPT }}</td>
                                                <td>{{ $nation->NATIONALITY }}</td>
                                                <td>{{ $nation->TYPE_OF_DOCUMENT }}</td>
                                                <td>{{ $nation->NUMBER }}</td>
                                                <td>{{ $nation->COUNTRY_OF_ISSUE }}</td>
                                                <td>{{ $nation->TYPE_OF_DATE }}</td>
                                                <td>{{ $nation->DATE_BIRTH }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                @can("Import Nation unis")
                    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myLargeModalLabel">Import Nation Unis Liste</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="bg-white border-radius-10">
                                        <form method="POST" action="{{ route('admin.NationUnis.import') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label> Saisie le lien de Fichier </label>
                                                <input type="text" id='url' placeholder="https://scsanctions.un.org/resources/xml/fr/consolidated.xml" required class=" form-control height-auto" name="url">
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
