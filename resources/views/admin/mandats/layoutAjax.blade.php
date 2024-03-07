<table class="table hover multiple-select-row data-table nowrap">
    <thead>
        <tr>
            <td style="width: 4%">#</td>
            <td>Date</td>
            <td>Code</td>
            <td>Input identity</td>
            <td>Output identity</td>
            <td>Birthday</td>
            <td>Status</td>
            <td>Suspect</td>
            <td>Type_mandat</td>
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
                    <td>
                        @php
                            $statut = $mandat->getStatus();
                            if ($statut == null){
                                $statut = ["class"=>"warning" , "text"=>"En cours"];
                            }else{
                                $statut = $mandat->getCSS($statut->statut);
                            }
                        @endphp
                        <span class="badge badge-pill badge-{{ $statut["class"] }}">{{ $statut["text"] }}</span>

                    </td>
                    <td>@if ($mandat->suspect() == true)
                            <span class="badge badge-pill badge-warning">True</span>
                        @else
                            <span class="badge badge-pill badge-success">False</span>
                        @endif
                    </td>
                    <td>
                        @if($mandat->type_mandat == "WU")
                            Western Union
                        @else
                            {{ $mandat->type_mandat }}
                        @endif
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
                        <div class="dropdown">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </thead>
</table>
