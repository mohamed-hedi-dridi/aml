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
                            <form method="POST" action="{{route('admin.users.changePassword')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label>Email :</label>
                                            <input type="email" disabled class="form-control" placeholder="Email" value="{{Auth::user()->email}}" required style="width: 100%; height: 38px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group @error('Ancien') form-control-danger @enderror">
                                            <label>Ancien mot de passe :</label>
                                            <input minlength="5" type="Password" class="form-control @error('Ancien') form-control-danger @enderror" required placeholder="Ancien mot de passe" name="Ancien" style="width: 100%; height: 38px;">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group @error('Nouveau') form-control-danger @enderror">
                                            <label>Nouveau Mot de passe :</label>
                                            <input minlength="8" type="Password"  class="form-control @error('Nouveau') form-control-danger @enderror" placeholder="Nouveau Mot de passe" required name="Nouveau" style="width: 100%; height: 38px;">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group @error('ConfirmNouveau') form-control-danger @enderror">
                                            <label>Confirmer Mot de passe :</label>
                                            <input type="Password" class="form-control @error('ConfirmNouveau') form-control-danger @enderror" required placeholder="Confirm Mot de passe" name="ConfirmNouveau" style="width: 100%; height: 38px;">
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
