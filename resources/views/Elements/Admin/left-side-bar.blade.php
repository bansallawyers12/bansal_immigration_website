<div class="main-sidebar sidebar-style-2">
	<aside id="sidebar-wrapper">
		<div class="sidebar-brand">
			<a href="#">
				<img alt="Bansal Immigration" src="{{ asset('public/img/logo.png') }}" class="header-logo" />
				<!--<span class="logo-name"></span>-->
			</a>
		</div>
		<ul class="sidebar-menu">
            <?php
            $roles = \App\UserRole::find(Auth::user()->role);
            $newarray = json_decode($roles->module_access);
            $module_access = (array) $newarray;
            ?>
			<li class="menu-header">Main</li>
            <?php if(Route::currentRouteName() == 'admin.dashboard'){
                $dashclasstype = 'active';
            } ?>
			<li class="dropdown {{@$dashclasstype}}">
				<a href="{{route('admin.dashboard')}}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
			</li>

            <?php if( Auth::user()->role == 1 || Auth::user()->role == 12 ){ //super admin or admin ?>
			<li class="dropdown">
				<a href="#" class="menu-toggle nav-link has-dropdown"><i
				data-feather="briefcase"></i><span>Blogs section</span></a>
				<ul class="dropdown-menu">
					<li class=""><a class="nav-link" href="{{ route('admin.blogcategory.index') }}">Blogs Category</a></li>
					<li class=""><a class="nav-link" href="{{ route('admin.blog.index') }}">Blogs</a></li>
				</ul>
			</li>
			<?php } ?>

            <?php if( Auth::user()->role == 1 || Auth::user()->role == 12 ){ //super admin or admin ?>
    			@if(Route::currentRouteName() == 'admin.cms_pages.index' || Route::currentRouteName() == 'admin.cms_pages.create' || Route::currentRouteName() == 'admin.cms_pages.edit')
    			 @php	$cmsclasstype = 'active'; @endphp
    			@endif

    			<li class="dropdown {{@$cmsclasstype}}">
    				<a href="{{route('admin.cms_pages.index')}}" class="nav-link"><i data-feather="briefcase"></i><span>CMS Pages</span></a>
    			</li>
            <?php
            } ?>

            <li class="dropdown">
				<a href="{{route('admin.logout')}}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i data-feather="log-out"></i><span>Logout</span></a>
				{{ Form::open(array('url' => 'admin/logout', 'name'=>'admin_login', 'id' => 'logout-form')) }}
				<input type="hidden" name="id" value="{{Auth::user()->id}}">
				{{ Form::close() }}
			</li>
		</ul>
	</aside>
</div>
