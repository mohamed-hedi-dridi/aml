@extends('layouts.app')

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
                        <div class="col-md-3 col-sm-12 mb-30">
                            <div class="pd-20 card-box height">
                                <div class="clearfix mb-30">
                                    <div class="pull-left">
                                        <h4 class="text-blue h4">Ajouter Rôle :</h4>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('admin.roles.store') }}">
                                    @csrf
                                    <div class="form-group @error('name')has-danger @enderror row">
                                        <div class="mb-10">
                                            <label class="form-control-label col-sm-12 col-form-label">Nom du rôle:</h5>
                                        </div>
                                        <input type="text" class="form-control @error('name') form-control-danger @enderror " placeholder="Nom du rôle" name="name">
                                        @error('name') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                    </div>
                                    <div class="mt-3 mb-0 text-center form-group">
                                        <button class="btn btn-primary btn-sm scroll-click" type="submit">Enregistrer </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <div class="col-md-9 col-sm-12 mb-30">
                        <div class="pd-20 card-box height-100-p">
							<div class="clearfix mb-30">
								<div class="pull-left">
									<h4 class="text-blue h4">Liste des rôles :</h4>
								</div>
							</div>
                            <table class="table hover multiple-select-row data-table-export nowrap">
                                <thead>
                                    <tr>
                                        <td style="width:10%">#</td>
                                        <td style="width:45%">Nom rôle</td>
                                        <td style="width:45%">Action</td>
                                    </tr>
                                    @php
                                        $i=1;
                                    @endphp
                                    <tbody>
                                        @foreach ($roles as $role )
                                            <tr>
                                                <td style="width:20%">{{ $i++ }}</td>
                                                <td style="width:40%">{{ ucfirst($role->name) }}</td>
                                                <td style="width:40%">
                                                    @can("Attribut permissions")
                                                        <a class="btn btn-primary btn-sm scroll-click" href="{{ route('admin.roles.edit',$role) }}"> Attribuer Permissions </a>
                                                    @endcan
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
