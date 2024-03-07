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
                    @can("Add Module")
                        <div class="col-md-3 col-sm-12 mb-30">
                            <div class="pd-20 card-box height">
                                <div class="clearfix mb-30">
                                    <div class="pull-left">
                                        <h4 class="text-blue h4">Ajouter Module :</h4>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('admin.modules.store') }}">
                                    @csrf
                                    <div class="form-group @error('name')has-danger @enderror row">
                                        <div class="mb-10">
                                            <label class="form-control-label col-sm-12 col-form-label">Nom Module:</h5>
                                        </div>
                                        <input type="text" class="form-control @error('name') form-control-danger @enderror " required placeholder="Nom Module" name="name">
                                        @error('name') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                    </div>
                                    <div class="mt-3 mb-0 text-center form-group">
                                        <button class="btn btn-primary btn-sm scroll-click" type="submit">Enregistrer </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endcan

                    <div class="col-md-9 col-sm-12 mb-30">
                        <div class="pd-20 card-box height-100-p">
							<div class="clearfix mb-30">
								<div class="pull-left">
									<h4 class="text-blue h4">Liste des Modules :</h4>
								</div>
							</div>
                            <table class="table hover multiple-select-row data-table-export nowrap">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Nom Module</td>
                                        <td>Permissions</td>
                                        <td>Actif</td>
                                    </tr>
                                    @php
                                        $i=1;
                                    @endphp
                                    <tbody>
                                        @foreach ($modules as $module )
                                            @php
                                                $Permissions = Spatie\Permission\Models\Permission::where('module_id',$module->id)->get();
                                            @endphp
                                            <tr>
                                                <td style="width:10%">{{ $i++ }}</td>
                                                <td style="width:40%">{{ $module->name }}</td>
                                                <td style="width:40%">
                                                    @foreach ($Permissions as $Permission )
                                                        <span class="badge badge-primary">{{ $Permission->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td style="width:10%">
                                                    @if ($module->actif)
                                                        <span class="badge badge-success"> Actif</span>
                                                    @else
                                                        <span class="badge badge-danger"> Inactif</span>
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
        @include('layouts.footer')
    </div>
</div>
@endsection
