@php
    $user =Auth::user();
@endphp
<div class="left-side-bar">
    <div class="brand-logo">
        <a href="/">
            <img src="/vendors/images/ViamobileWhiteLogo.png" alt="" class="dark-logo">
            <img src="/vendors/images/ViamobileWhiteLogo.png" alt="" class="light-logo">
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li class="dropdown">
                    <a href="/" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-house-1"></span><span class="mtext">Dashboard</span>
                    </a>
                </li>
                @php
                    $menus = App\Models\MenuSideBar::where('parent',null)->get();
                @endphp
                @foreach ($menus as $menu )
                    @php
                        $fils = $menu->fils;
                        $permission = $menu->permission();
                    @endphp
                    @can($permission->name)
                        @if (count($fils)>0)
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle">
                                    <span class="micon {{ $menu->icon }}"></span><span class="mtext">{{ $menu->name }}</span>
                                </a>
                                <ul class="submenu">
                                    @foreach($fils as $key => $fil)
                                        @php
                                            $permission = $menu->permission();
                                        @endphp
                                        @can($permission->name)
                                            @if (count($fil->fils)>0)
                                            <li class="dropdown">
                                                <a href="javascript:;" class="dropdown-toggle">
                                                    <span class="micon {{ $fil->icon }}"></span><span class="mtext">{{ $fil->name }}</span>
                                                </a>
                                                <ul class="submenu child">
                                                    @foreach ($fil->fils as $sous )
                                                        <li><a href="{{ $sous->route }}">{{ $sous->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <li><a href="{{ $fil->route }}">{{ $fil->name }}</a></li>
                                            @endif
                                        @endcan
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="dropdown">
                                <a href="@if($menu->route){{ ($menu->route) }} @else # @endif" class="dropdown-toggle no-arrow">
                                    <span class="micon {{ $menu->icon }}"></span><span class="mtext">{{ $menu->name }}</span>
                                </a>
                            </li>
                        @endif
                    @endcan
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="mobile-menu-overlay"></div>
