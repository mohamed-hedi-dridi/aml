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
                    <div class="col-md-12 col-sm-12 mb-30">
                        <div class="pd-20 card-box height-100-p">
                            <form method="POST" action="{{route('admin.users.store')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group @error('name')has-danger @enderror">
                                            <label>Nom Prénom :</label>
                                            <input type="text"   class="form-control @error('name') form-control-danger @enderror" placeholder="Nom Prénom" required name="name" style="width: 100%; height: 38px;">
                                            @error('name') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group @error('email')has-danger @enderror">
                                            <label>Email :</label>
                                            <input type="email"  class="form-control @error('email') form-control-danger @enderror" placeholder="Email" required name="email" style="width: 100%; height: 38px;">
                                            @error('email') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group @error('telephone')has-danger @enderror">
                                            <label>Téléphone :</label>
                                            <input type="number" minlength="8" maxlength="13"  class="form-control @error('telephone') form-control-danger @enderror" placeholder="Téléphone" required name="telephone" style="width: 100%; height: 38px;">
                                            @error('telephone') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group @error('role')has-danger @enderror">
                                            <label>Rôle :</label>
                                            <select class="custom-select2 form-control @error('role') form-control-danger @enderror" name="role" required style="width: 100%; height: 38px;">
                                                <option value="" disabled selected hidden>Choisir Rôle</option>
                                                @foreach($roles as $key => $role)
                                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                                @endforeach
                                            </select>
                                            @error('role') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group @error('direction')has-danger @enderror">
                                            <label>Direction :</label>
                                            <select class="custom-select2 form-control @error('direction') form-control-danger @enderror" name="direction" required style="width: 100%; height: 38px;">
                                                <option value="" disabled selected hidden>Choisir Direction</option>
                                                @foreach($directions as $key => $direction)
                                                    <option value="{{ $direction->id }}">{{ ucfirst($direction->name) }}</option>
                                                @endforeach
                                            </select>
                                            @error('direction') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="mb-0 text-right form-group">
                                        <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
        @include('layouts.footer')
    </div>
</div>
@endsection
