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
                <div class="card-box mb-30 pd-20">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group @error('name') has-danger @enderror">
                                    <label>Nom Prénom :</label>
                                    <input type="text"  class="form-control @error('name')has-danger @enderror" placeholder="Nom Prénom" value="{{$user->name}}" required name="name" style="width: 100%; height: 38px;">
                                    @error('name') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group @error('email') has-danger @enderror">
                                    <label>Email :</label>
                                    <input type="email" value="{{$user->email}}" disabled class="form-control @error('email')has-danger @enderror" placeholder="Email" required name="email" style="width: 100%; height: 38px;">
                                    @error('email') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="form-group @error('telephone')has-danger @enderror">
                                    <label>Téléphone :</label>
                                    <input type="number" minlength="8" value="{{$user->telephone}}" maxlength="13"  class="form-control @error('telephone')has-danger @enderror" placeholder="Téléphone" required name="telephone" style="width: 100%; height: 38px;">
                                    @error('telephone') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group @error('direction')has-danger @enderror">
                                    <label>Direction :</label>
                                    <select class="custom-select2 form-control @error('direction')has-danger @enderror" name="direction" required style="width: 100%; height: 38px;">
                                        @foreach (App\Models\Direction::all() as $Direction )
                                            @if ($user->id_direction == $Direction->id)
                                                <option value="{{$Direction->id}}" selected="true">{{$Direction->name}}</option>
                                            @else
                                                <option value="{{$Direction->id}}">{{$Direction->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('direction') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group @error('role')has-danger @enderror">
                                    <label>Rôle :</label>
                                    <select class="custom-select2 form-control @error('role')has-danger @enderror" name="role" required style="width: 100%; height: 38px;">
                                        @foreach ($roles as $role )
                                            @if ($role->name == $user->getRoleNames()[0])
                                                <option value="{{ $role->id }}"  selected="true" >{{ lcfirst($role->name) }}</option>
                                            @else
                                                <option value="{{ $role->id }}" >{{ lcfirst($role->name) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('role') <div class="form-control-feedback"> {{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group @error('statut')has-danger @enderror">
                                    <label>Statut :</label>
                                    <select class="custom-select2 form-control @error('statut')has-danger @enderror" name="statut" required style="width: 100%; height: 38px;">
                                        <option value="Actif" @if ($user->statut == "Actif") selected="true" @endif>Actif</option>
                                        <option value="Inactif" @if ($user->statut == "Inactif") selected="true" @endif >Inactif</option>
                                    </select>
                                    @error('statut') <div class="form-control-feedback"> {{ $message }}</div> @enderror
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
    </div>
</div>

@endsection
