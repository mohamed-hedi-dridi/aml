@extends('layouts.app')
@php

    //$auth = Auth::user();
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
                                @can("Add Agent")
                                    <div class="pull-right">
                                        <a class="btn btn-primary" data-backdrop="static" data-toggle="modal" href="#" data-target="#Medium-modal">Ajouter Nouvel Agent</a>
                                    </div>
                                @endcan
							</div>
                            <table class="table hover multiple-select-row data-table-export nowrap">
                                <thead>
                                    <tr>
                                        <td style="width: 5%">#</td>
                                        <td  style="width: 15%">Nom Prénom</td>
                                        <td  style="width: 15%">Email</td>
                                        <td  style="width: 15%">Téléphone</td>
                                        <td style="width: 15%">Gouvernorat</td>
                                        <td style="width: 10%">Statut</td>
                                        <td style="width: 10%">KYC</td>
                                        <td style="width: 15%">Action</td>
                                    </tr>
                                    @php
                                        $i=1;
                                    @endphp
                                    <tbody>
                                        @foreach ($agents as $agent )
                                        <tr>
                                            <td>{{ $i++}}</td>
                                            <td>{{ $agent->name }}</td>
                                            <td>{{ $agent->email }}</td>
                                            <td>{{ $agent->tel }}</td>
                                            <td>{{ $agent->state->name }}</td>
                                            <td>@if ($agent->statut == 1)
                                                    <span class="badge badge-pill badge-success">Actif</span>
                                                @else
                                                    <span class="badge badge-pill badge-warning">Inactif</span>
                                                @endif
                                            </td>
                                            <td>@if ($agent->wu == 1)
                                                    <span class="badge badge-pill badge-success">OUI</span>
                                                @else
                                                    <span class="badge badge-pill badge-warning">NON</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                        @can("Update KYC")
                                                            @if ($agent->wu == 0)
                                                                <input type="hidden" class="url" value="{{ $agent->email }}">
                                                                <a class="dropdown-item d-none " onclick="updatePost('{{ $agent->email }}')" ><i class="fi-plus"></i> Ajouter KYC</a>
                                                            @else
                                                                <a class="dropdown-item d-none" onclick="updatePost('{{ $agent->email }}')"><i class="fi-x"></i> Annuler KWC</a>
                                                            @endif
                                                            <a class="dropdown-item " onclick="updateAgent('{{ $agent->email }}')" ><i class="fa fa-edit"></i> Edit Agent</a>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                @can('Add Agent')
                <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myLargeModalLabel">Ajouter Nouvel Agent</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <form class="saveAgent" action="{{ route('admin.Agent.store') }}"  method="POST">
                                    @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group  col-6">
                                            <label>Nom Prénom Agent *:</label>
                                            <input type="text" class="form-control form-control height-auto" placeholder="Nom Prénom Agent *" required id="name" name="name">
                                        </div>
                                        <div class="form-group col-6">
                                            <label>Téléphone Agent *:</label>
                                            <input type="text" class="form-control form-control height-auto" placeholder="Téléphone Agent *" required id="tel" name="tel">
                                        </div>
                                        <div class="form-group email col-12">
                                            <label>Email Agent *:</label>
                                            <input type="text" class="form-control form-control height-auto" placeholder="Email Agent *" required id="email" name="email">
                                            <div class="form-control-feedback"> </div>
                                        </div>
                                        <div class="form-group col-6">
                                            <label>Gouvernorat Agent*:</label>
                                            <select type="custom-select form-control" class="form-control form-control height-auto" id="state_id" required name="state_id">
                                                <option disabled>Gouvernorat Agent* </option>
                                                @foreach(App\Models\State::all() as $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6">
                                            <label>Autorisé KYC *:</label>
                                            <div class="custom-control custom-radio mb-5">
                                                <input type="radio" id="oui" class="custom-control-input" placeholder="Téléphone Agent *" value="1" required name="wu">
                                                <label class="custom-control-label" for="oui"> OUI </label>
                                            </div>
                                            <div class="custom-control custom-radio mb-5">
                                                <input type="radio" id="non" checked class="custom-control-input" placeholder="Téléphone Agent *" value="0" required name="wu">
                                                <label class="custom-control-label" for="non"> NON </label>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group col-6">
                                            <h5> Ria Money : </h5>
                                            <label>CorrespLocID Agent :</label>
                                            <input type="text" class="form-control form-control height-auto" placeholder="CorrespLocID" id="CorrespLocID" name="CorrespLocID">
                                        </div>
                                        <div class="form-group col-6">
                                            <h5> MyEasyTransfer : </h5>
                                            <label>AgentID_easy :</label>
                                            <input type="text" class="form-control form-control height-auto METagentID" placeholder="AgentID_easy" id="METagentID" name="agentID_easy">
                                        </div>
                                        <hr>
                                        <div class="form-group col-12">
                                            <h5> MoneyGram : </h5>
                                        </div>
                                        <div class="form-group col-6">
                                            <label>Agent ID :</label>
                                            <input type="text" class="form-control form-control height-auto" placeholder="agentID" id="agentID" name="agentID">
                                        </div>
                                        <hr>
                                        <div class="form-group col-6">
                                            <label>POSPassWord :</label>
                                            <input type="text" class="form-control form-control height-auto" placeholder="POSPassWord" id="POSPassWord" name="POSPassWord">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-secondary" >Annuler</button>
                                    <button type="submit" class="btn btn-primary saveAgent">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endcan
                <div class="modal fade" id="Edit_agent" tabindex="-1" role="dialog" aria-labelledby="EditAgent" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="EditAgent">Ajouter Nouvel Agent</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <form class="EditAgent" action=""  method="POST">
                                    @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group  col-6">
                                            <label>Nom Prénom Agent *:</label>
                                            <input type="text" disabled class="form-control form-control height-auto" placeholder="Nom Prénom Agent *" required id="nameAgent" name="name">
                                        </div>
                                        <div class="form-group email col-6">
                                            <label>Email Agent *:</label>
                                            <input type="text" disabled class="form-control form-control height-auto" placeholder="Email Agent *" required id="emailAgent" name="email">
                                            <div class="form-control-feedback"> </div>
                                        </div>
                                        <div class="form-group col-6">
                                            <label>Téléphone Agent *:</label>
                                            <input type="text" disabled class="form-control form-control height-auto" placeholder="Téléphone Agent *" required id="telAgent" name="tel">
                                        </div>
                                        <div class="form-group col-6">
                                            <label>Autorisé KYC *:</label>
                                            <div class="custom-control custom-radio mb-5">
                                                <input type="radio" id="ouiKYC" class="custom-control-input" value="1" required name="wu">
                                                <label class="custom-control-label" for="ouiKYC"> OUI </label>
                                            </div>
                                            <div class="custom-control custom-radio mb-5">
                                                <input type="radio" id="nonKYC" class="custom-control-input" value="0" required name="wu">
                                                <label class="custom-control-label" for="nonKYC"> NON </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-6">
                                            <h5> Ria Money : </h5>
                                            <label>CorrespLocID Agent :</label>
                                            <input type="text" class="form-control form-control height-auto" placeholder="CorrespLocID" id="CorrespLocIDAgent" name="CorrespLocID">
                                        </div>
                                        <div class="form-group col-6">
                                            <h5> MyEasyTransfer : </h5>
                                            <label>AgentID_easy :</label>
                                            <input type="text" class="form-control form-control height-auto" placeholder="AgentID_easy" id="agentID_easy" name="agentID_easy">
                                        </div>
                                        <div class="form-group col-12">
                                            <h5> MoneyGram : </h5>
                                        </div>
                                        <div class="form-group col-6">
                                            <label>Agent ID :</label>
                                            <input type="text" class="form-control form-control height-auto" placeholder="agentID" id="agentIDAgent" name="agentID">
                                        </div>
                                        <hr>
                                        <div class="form-group col-6">
                                            <label>POSPassWord :</label>
                                            <input type="text" class="form-control form-control height-auto" placeholder="POSPassWord" id="POSPassWordAgent" name="POSPassWord">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary saveAgent">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        @include('layouts.footer')
    </div>
</div>

<script>

    function updatePost(email) {
        $.ajax({
            url: '{{ route("admin.Agent.updateKYC") }}',
                method:'POST',
                data: {
                    email: email,
                    _token: "{{csrf_token()}}",
                },
                success: function(response) {
                    if(response.status=="success"){
                        swal(
                            'Succès!',
                            'Statut Modifié avec succès.',
                            'success'
                        ).then(()=>{
                            location.reload();
                        })
                    }else{
                        swal(
                                {
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Quelque chose s\'mal passé !',
                                }
                            )
                    }
                },
                error: function(error) {
                    swal(
                        {
                            type: 'error',
                            title: 'Oops...',
                            text: 'Error Updating !',
                        }
                    )
                }
        });
    }

    function updateAgent(email){
        action = '/admin/Agent/edit/'+email;
        route = "/admin/Agent/"+email;
        $.get(route, function(data){
            $("#EditAgent").text("Edit "+data.nom);
            $("#nameAgent").val(data.nom);
            $("#emailAgent").val(data.email);
            $("#telAgent").val(data.tel);
            $("#state_idAgent").val(data.id_state);
            if(data.western == 1){
                $('#ouiKYC').prop('checked', true);
                $('#nonKYC').prop('checked', false)
            }else{
                $('#ouiKYC').prop('checked', false);
                $('#nonKYC').prop('checked', true);
            }
            $("#CorrespLocIDAgent").val(data.CorrespLocID);
            $("#agentIDAgent").val(data.agentID);
            $("#agentID_easy").val(data.agentID_easy);
            $("#POSPassWordAgent").val(data.POSPassWord);
            $('.EditAgent').attr('action', action);
            $('#Edit_agent').modal('show');
        })
    }
</script>
@endsection

