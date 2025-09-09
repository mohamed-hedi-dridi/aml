<div class="clearfix mb-30">
    <div class="header-search">
        <form method="GET" action="{{ route('admin.mandats.index',["type"=>$url]) }}">
            <div class="row">
                <div class="form-group mb-0 col-4">
                    <label>Code Mandat : </label>
                    <input type="text" class="form-control search-input codeMandat" name="code" id="code" placeholder="Tapez le code mandat">
                </div>
                <div class="form-group mb-0 col-4">
                    <label>De : </label>
                    <input type="date" class="form-control search-input dateDebut" value="{{ $startDate }}" name="dateDebut" id="dateDebut" placeholder="Tapez le code mandat">
                </div>
                <div class="form-group mb-0 col-4">
                    <label>vers : </label>
                    <input class="form-control search-input dateFin" value="{{ $endDate }}" name="dateFin" id="dateFin" placeholder="Tapez Ouput Identity"  type="date">
                </div>
                <div class="form-group mb-0 col-4">
                    <label>Email Agent : </label>
                    <input class="form-control search-input emailAgent" name="email" id="emailAgent" placeholder="Tapez Email Agent"  type="email">
                </div>
                <div class="form-group mb-0 col-4">
                    <label>Type mandat : </label>
                    <select class="form-control type_mandat" name="type_mandat">
                        <option @if ($url == "All") selected @endif value="All"> All </option>
                        <option @if ($url == "Western Union") selected @endif value="Western Union"> Western Union </option>
                        <option @if ($url == "Ria Money") selected @endif value="Ria Money"> RIA </option>
                        <option @if ($url == "MoneyGram") selected @endif value="MoneyGram"> MG </option>
                        <option @if ($url == "EasyTransferMobile") selected @endif value="EasyTransferMobile"> My Easy Transfert Mobile</option>
                        <option @if ($url == "EasyTransfer") selected @endif value="EasyTransfer"> My Easy Transfert </option>
                        <option @if ($url == "Zepz") selected @endif> Zepz </option>
                        <option @if ($url == "Worldremit") selected @endif> Worldremit </option>
                        <option @if ($url == "TapTapSend") selected @endif> TapTapSend </option>
                    </select>
                </div>
                <div class="form-group mt-4 col-3">
                    <button type="submit" class="btn btn-primary btn-lg btn-block " style="color: #fff;"> Chercher </button>
                </div>
            </div>
        </form>
    </div>
</div>
<table class="table hover multiple-select-row data-table-export nowrap">
    <thead>
        <tr>
            <td style="width: 4%">#</td>
            <td>Date</td>
            <td>Code</td>
            <td>Input identity</td>
            <td>Output identity</td>
            <td>Birthday</td>
            <td>Suspect</td>
            <td>Type</td>
            <td> Mantant </td>
            <td>Action</td>
        </tr>
        <tbody id="myTableBody">
            @php
                $i=1;
            @endphp
            @foreach ($mandats as $key=>$mandat )
                <tr>
                    <td>{{ $i++}}</td>
                    <td>{{ $mandat->date }}</td>
                    <td>{{ $mandat->code }}</td>
                    <td>{{ $mandat->input_identity }}</td>
                    <td>{{ $mandat->output_identity }}</td>
                    <td>{{ $mandat->birthday }} </td>
                    <td>@if ($mandat->suspect() == true)
                            <span class="badge badge-pill badge-warning">True</span>
                        @else
                            <span class="badge badge-pill badge-success">False</span>
                        @endif
                    </td>
                    <td>
                            {{ $mandat->type_mandat }}
                    </td>
                   <td>
                        {{ $mandat->getAmount() }}
                   </td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                <i class="dw dw-more"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                @can('view mandat')
                                    <a class="dropdown-item" href="{{ route('admin.mandats.view', $mandat->code) }}"><i class="dw dw-eye"></i> View</a>
                                @endcan
                                <!--<a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>-->
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </thead>
</table>
