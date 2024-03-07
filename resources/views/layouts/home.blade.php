@extends('layouts.app')

@section('content')
@include('layouts.nav')
@include('layouts.sidebar')
<div class="main-container">
    <div class="pd-ltr-20">
        <div class="row">
            <div class="col-xl-4 mb-30">
                <div class="card-box height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="progress-data">
                            <div id="chart"></div>
                        </div>
                        <div class="widget-data">
                            <div class="h4 mb-0">{{$total}}</div>
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
                            <div class="h4 mb-0">{{$nbEn}}</div>
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
                            <div class="h4 mb-0">{{$nbClo}}</div>
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
                    <tbody>@php
                        $i = 1;
                    @endphp
                        @foreach ($reclamations as $reclamation )
                        <tr>
                            <td >{{$i++}}</td>
                            <td >{{$reclamation->ref}}</td>
                            <td>{{$reclamation->created_at}}</td>
                            <td>{{$reclamation->nom_rec}}<br>{{$reclamation->cathegorie_rec}}</td>
                            <td>{{$reclamation->zone->name}}</td>
                            <td>{{$reclamation->typologie->lib }}<br>
                                @if ($reclamation->id_soustypologie !=null)
                                    {{$reclamation->sous_typologie->lib}}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{$reclamation->details}}</td>
                            <td>
                                @if ($reclamation->Statut == "Clôturé")
                                    <span class="badge badge-success">Clôturée</span>
                                @else
                                    <span class="badge @if($reclamation->Statut == 'En Cours') badge-secondary @else badge-info @endif">{{$reclamation->Statut}}</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="{{route('showReclamation',$reclamation->id)}}"><i class="dw dw-eye"></i> Afficher</a>
                                        @if ($reclamation->appartient())
                                            <a class="dropdown-item" href="{{route('Edit-reclamation',$reclamation->id)}}"><i class="dw dw-edit2"></i> Edit</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @include('layouts.footer')
    </div>
</div>
@endsection
