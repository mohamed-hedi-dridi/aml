@php
    $uri = App\Models\MenuSideBar::GetModelByUri();
@endphp
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>{{ $titre }}</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    @if($uri != null)
                    <li class="breadcrumb-item">{{ $uri }}</li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $titre }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">

        </div>
    </div>
</div>
