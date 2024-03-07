@extends('layouts.app')

@section('content')
@include('layouts.nav')
@include('layouts.sidebar')
<div class="main-container">
    <div class="pd-ltr-20">
        <div class="card-box pd-20 height-100-p mb-30">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <img src="vendors/images/banner-img.png" alt="">
                </div>
                <div class="col-md-8 mt-8">
                    <h4 class="font-20 weight-500 mb-10 text-capitalize">
                        Welcome back <div class="weight-600 font-30 text-blue">{{ Auth::user()->name }}!</div>
                    </h4>
                    <p class="font-18 max-width-600"></p>
                </div>
            </div>
        </div>
        <!--<div class="row">
            <div class="col-xl-4 mb-30">
                <div class="card-box height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="progress-data">
                            <div id="chart"></div>
                        </div>
                        <div class="widget-data">
                            <div class="h4 mb-0">12</div>
                            <div class="weight-600 font-14">Total Réclamations</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 mb-30">
                <div class="card-box height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="progress-data">
                            <div id="chart2"></div>
                        </div>
                        <div class="widget-data">
                            <div class="h4 mb-0">12</div>
                            <div class="weight-600 font-14">Réclamations En Cours</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 mb-30">
                <div class="card-box height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="progress-data">
                            <div id="chart3"></div>
                        </div>
                        <div class="widget-data">
                            <div class="h4 mb-0">12</div>
                            <div class="weight-600 font-14">Réclamations Clôturées</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-box mb-30">
            <div class="pd-20">
                @if (isset($_GET['test']))
                    @if ($_GET['test']==1)
                    <div class="alert alert-success" role="alert">
                        Réclamation Ajouter avec Succès
                    </div>
                    @endif
                @endif
                <h4 class="text-blue h4">Liste des Réclamations</h4>
            </div>
            <div class="pb-20">
                <table class="table hover multiple-select-row data-table-export nowrap" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th >Réf</th>
                            <th>Date Réclamation </th>
                            <th>Réclamant</th>
                            <th>Zone</th>
                            <th>Typologie</th>
                            <th>Détails</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>-->
    @include('layouts.footer')
    </div>
</div>
@endsection
