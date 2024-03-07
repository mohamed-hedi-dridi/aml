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
									<h4 class="text-blue h4">Liste des Utilisateur :</h4>
								</div>
                                @can("Add User")
                                    <div class="pull-right">
                                        <a class="btn btn-primary" href="{{ route('admin.users.create') }}">Ajouter Utilisateur</a>
                                    </div>
                                @endcan
							</div>
                            <table class="table hover multiple-select-row data-table-export nowrap">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Nom Prénom</td>
                                        <td>Email</td>
                                        <td>Telephone</td>
                                        <td>Rôle</td>
                                        <td>Direction</td>
                                        <td>Statut</td>
                                        <td>Action</td>
                                    </tr>
                                    @php
                                        $i=1;
                                    @endphp
                                    <tbody>
                                        @foreach ($users as $user )
                                            @php
                                                //$Permissions = Spatie\Permission\Models\Permission::where('module_id',$module->id)->get();
                                            @endphp
                                            <tr>
                                                <td style="width:5%">{{ $i++ }}</td>
                                                <td style="width:15%">{{ $user->name }}</td>
                                                <td style="width:20%">{{ $user->email }}</td>
                                                <td style="width:15%">{{ $user->telephone }}</td>
                                                <td style="width:15%">{{ count($user->getRoleNames())>0 ? ucfirst($user->getRoleNames()[0]) : 'Non attribué' }}</td>
                                                <td style="width:10%">{{ ucfirst($user->direction->name) }}</td>
                                                <td style="width:10%">
                                                    @if ($user->statut == 'Actif')
                                                        <span class="badge badge-success"> Actif</span>
                                                    @else
                                                        <span class="badge badge-danger"> Inactif</span>
                                                    @endif
                                                </td>
                                                <td style="width:10%">
                                                    <div class="dropdown">
                                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                            <i class="dw dw-more"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                            @can("Update User")
                                                                <a class="dropdown-item" href="{{ route('admin.users.edit',$user->id) }}"><i class="dw dw-edit2"></i> Edit</a>
                                                            @endcan
                                                            @can("Update Password")
                                                                <a class="dropdown-item" href="{{ route('admin.users.reset',$user->id) }}"><i class="dw dw-password"></i> Reset Password</a>
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
        @include('layouts.footer')
    </div>
</div>
@endsection
