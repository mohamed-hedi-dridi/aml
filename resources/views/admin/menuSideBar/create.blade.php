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
            @can("Add Menu")
                <div class="col-md-3 col-sm-12 mb-30">
                    <div class="pd-20 card-box height">
                        <div class="clearfix mb-30">
                            <div class="pull-left">
                                <h4 class="text-blue h4">Ajouter Menu :</h4>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.MenuSideBar.store') }}">
                            @csrf
                            <div class="form-group @error('name')has-danger @enderror row">
                                <div class="mb-10">
                                    <label class="form-control-label col-sm-12 col-form-label">Nom Menu:</h5>
                                </div>
                                <input type="text" class="form-control @error('name') form-control-danger @enderror " required placeholder="Nom Menu" name="name">
                                @error('name') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                            </div>
                            <div class="form-group @error('route')has-danger @enderror row">
                                <div class="mb-10">
                                    <label class="form-control-label col-sm-12 col-form-label">URL:</h5>
                                </div>
                                <input type="text" class="form-control" placeholder="URL" name="route">
                            </div>
                            <div class="form-group @error('module_id')has-danger @enderror row">
                                <div class="mb-10">
                                    <label class="form-control-label col-sm-12 col-form-label">Module:</h5>
                                </div>
                                <select id="module" class="custom-select2 form-control @error('module_id') form-control-danger @enderror " data-size="5" data-style="btn-outline-danger" name="module_id">
                                    <option disabled hidden selected>Sélectionner Module</option>
                                    @foreach ($modules as $module )
                                        <option  value="{{ $module->id }}">{{ $module->name }}</option>
                                    @endforeach
                                </select>
                                @error('module_id') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                            </div>
                            <div id="permission">
                            </div>
                            <div class="form-group @error('icon')has-danger @enderror row">
                                <div class="mb-10">
                                    <label class="form-control-label col-sm-12 col-form-label">Icon:</h5>
                                </div>
                                <input type="text" class="form-control @error('icon') form-control-danger @enderror " placeholder="icon" name="icon">
                                @error('icon') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                            </div>
                            <div id="menus">
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
                            <h4 class="text-blue h4">Liste des Menus Par Module :</h4>
                        </div>
                    </div>
                    <table class="table hover multiple-select-row data-table-export nowrap">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Nom Module</td>
                                <td>Menus</td>
                                <!--<td>Action</td>-->
                            </tr>
                            @php
                                $i=1;
                            @endphp
                            <tbody>
                                @foreach ($modules as $module )
                                    @php
                                        $menus = $module->menu;
                                    @endphp
                                    <tr>
                                        <td style="width:10%">{{ $i++ }}</td>
                                        <td style="width:40%">{{ $module->name }}</td>
                                        <td style="width:50%">
                                            @foreach ($menus as $menu )
                                                <span class="badge badge-primary">{{ $menu->name }}</span>
                                            @endforeach
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
