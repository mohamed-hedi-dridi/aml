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
                                @can("Import Mandats")
                                    <div class="pull-right">
                                        <a class="btn btn-outline-info" href="{{ asset('imports/IdentityCheck.xlsx') }}">Télécharger un fichier exemple <i class="icon-copy fa fa-cloud-download"></i></a>
                                        <a class="btn btn-primary" data-backdrop="static" data-toggle="modal" href="#" data-target="#login-modal">Import Mandats</a>
                                    </div>
                                @endcan
							</div>
                            <div class="clearfix mb-30">
                                    <div class="header-left">
                                        <div class="menu-icon dw dw-menu"></div>
                                        <div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
                                        <div class="header-search">
                                            <form>
                                                <input type="hidden" value="{{ $type }}" id="type">

                                                <div class="form-group mb-20">
                                                    <i class="dw dw-search2 search-icon"></i>
                                                    <input type="text" class="form-control search-input codeMandat" name="code" id="code" placeholder="Tapez le code mandat">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <i class="dw dw-search2 search-icon"></i>
                                                    <input class="form-control search-input input_identity" name="input_identity" id="input_identity" placeholder="Tapez Ouput Identity"  type="text">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
								</div>
                            <div id="index">
                                <table class="table hover multiple-select-row data-table-export nowrap dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr>
                                            <td>Code</td>
                                            <td>Date</td>
                                            <td>Input identity</td>
                                            <td>Output identity</td>
                                            <td>Birthday</td>
                                            <td>Nom pre Ben</td>
                                            <td>Tel</td>
                                            <td>Type_mandat</td>
                                            <td>Statut</td>
                                            <td>URL</td>
                                        </tr>
                                        <tbody id="myTableBody">
                                            @php
                                                $i=1;
                                            @endphp
                                            @foreach ($mandats as $key=>$mandat )
                                            @php
                                            if ($mandat->type_mandat == "RIA"){
                                                $detailsRIA = $mandat->getRIAdetail() ;
                                            }else{
                                                $getMGdetail = $mandat->getMGdetail() ;
                                            }

                                            @endphp
                                                <tr>
                                                    <td>{{ $mandat->code }}</td>
                                                    <td>{{ $mandat->date }}</td>
                                                    <td>{{ $mandat->input_identity }}</td>
                                                    <td>{{ $mandat->output_identity }}</td>
                                                    <td>{{ $mandat->birthday }} </td>
                                                    <td>
                                                        @php
                                                            if($mandat->type_mandat == "RIA"){
                                                                if(count($detailsRIA)==1){
                                                                    echo(@$detailsRIA[0]["custNameFirst"]." ".@$detailsRIA[0]["beneNameLast1"]);
                                                                }
                                                            }else{
                                                                if(count($getMGdetail)==1){
                                                                    echo(@$getMGdetail[0]["referenceNumber"]["receiverFirstName"]." ".@$getMGdetail[0]["referenceNumber"]["receiverLastName"]);
                                                                }
                                                            }
                                                        @endphp

                                                    </td>
                                                    <td>
                                                        @php
                                                            if($mandat->type_mandat == "RIA"){
                                                                if(count($detailsRIA)==1){
                                                                    echo(@$detailsRIA[0]["beneTelNo"]);
                                                                }
                                                            }else{
                                                                if(count($getMGdetail)==1){
                                                                    echo(@$getMGdetail[0]["receiveValidationReq"]["receiverPhone"]);
                                                                }
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        @php
                                                            if($mandat->type_mandat == "RIA"){
                                                                if(count($detailsRIA)==1){
                                                                    echo(@$detailsRIA[0]["status"]);
                                                                }
                                                            }else{
                                                                if(count($getMGdetail)==1){
                                                                    echo(@$getMGdetail[0]["status"]);
                                                                }
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        @if($mandat->type_mandat == "WU")
                                                            Western Union
                                                        @else
                                                            {{ $mandat->type_mandat }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $mandat->image_cin }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </thead>
                                </table>
                            </div>
                            <div id="result">
                            </div>
                        </div>
                    </div>
                </div>
                @can("Import Mandats")
                    <div class="modal fade" id="DataTables_Table_2_wrapper" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myLargeModalLabel">Import Nouveau Mandats</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="bg-white border-radius-10">
                                        <form method="POST" action="{{ route('admin.mandats.import') }}" enctype="multipart/form-data">
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
