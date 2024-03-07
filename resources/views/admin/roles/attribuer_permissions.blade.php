@extends('layouts.app')

@section('content')
@include('layouts.nav')
@include('layouts.sidebar')
<div class="main-container">
    <div class="pd-ltr-20">
		        @include('layouts.page-title')
                <form method="POST"  action="{{ route('admin.roles.attribuer',['role'=>$role]) }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
                            <div class="pd-20 card-box height-100-p">
                                <h3 class="h3 text-blue">Rôle : {{ucfirst( $role->name ) }}</h3>
                            </div>
                        </div>
                        @foreach($modules as $module)
                                <div class="col-lg-4 col-md-6 col-sm-12 mb-30">
                                    <div class="pd-20 card-box height-100-p">
                                        <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input checkbox" id="customCheck{{$module->id }}" data-id="{{$module->id}}" type="checkbox"><label class="custom-control-label mb-20 h4" for="customCheck{{$module->id }}">{{$module->name}}</div>
                                        <ul class="list-group">
                                            @php
                                                $permissions = $module->permissions ;
                                            @endphp

                                            @foreach($permissions as $key => $permission)
                                                <li class="list-group-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input module{{ $module->id }}" @if($role->hasPermissionTo($permission)) checked @endif id="customCheck{{ $module->id }}{{ $key }}" value="{{ $permission->id }}" type="checkbox" name="permission[]">
                                                        <label class="custom-control-label" for="customCheck{{ $module->id }}{{ $key }}">{{ $permission->name }}</label>
                                                    </div>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                        @endforeach
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
                            <div class="pd-20 card-box height-100-p">
                                <div class="pull-right">
                                    <button class="btn btn-primary mb-3" type="submit"> Enregistrer </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
        @include('layouts.footer')
    </div>
</div>
@endsection
