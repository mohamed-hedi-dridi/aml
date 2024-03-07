<div class="header">
		<div class="header-left">
			<div class="menu-icon dw dw-menu"></div>
			<div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
			<div class="header-search">
			</div>
		</div>
		<div class="header-right">
			<div class="dashboard-setting user-notification">
				<div class="dropdown">
				</div>
			</div>
			<div class="user-notification">
				<div class="dropdown">
				</div>
			</div>
			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon">
							<img src="/vendors/images/photo1.jpg" alt="">
						</span>
						<span class="user-name">{{Auth::user()->name}}</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="{{ route('admin.user.newPassword') }}"><i class="dw dw-settings2"></i> Paramètres</a>
						<form method="POST" action="{{route('logout')}}" >
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="dw dw-logout"></i>Se déconnecter</a>
                        </form>
					</div>
				</div>
			</div>
			<div class="github-link">
				<a href="#" target="_blank"><img src="{{ asset('vendors/images/github.svg') }}" alt=""></a>
			</div>
		</div>
	</div>
