<div class="main-sidebar sidebar-style-2">
	<aside id="sidebar-wrapper">
		<div class="sidebar-brand">
			<a href="#">
				<img alt="Bansal CRM" src="{{ asset('public/img/logo.png') }}" class="header-logo" />
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
			<?php
			if(Route::currentRouteName() == 'admin.dashboard'){
				$dashclasstype = 'active';
			}
			?> 
			<li class="dropdown {{@$dashclasstype}}">
				<a href="{{route('admin.dashboard')}}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
			</li>
			<!-- <li class="dropdown">
				<a href="{{URL::to('/admin/appointments-cal')}}" class="nav-link"><i data-feather="calendar"></i><span>Appointment</span></a>
			</li> -->
			
            <?php
            //echo Route::currentRouteName();
            if( Route::currentRouteName() == 'appointments.index' || Route::currentRouteName() == 'appointments-education'  || Route::currentRouteName() == 'appointments-jrp' || Route::currentRouteName() == 'appointments-tourist' || Route::currentRouteName() == 'appointments-others' || Route::currentRouteName() == 'admin.feature.appointmentdisabledate.index'){
				$appointmentsclasstype = 'active';
			}
			?>
			
			<li class="dropdown {{@$appointmentsclasstype}}">
				<a href="#" class="menu-toggle nav-link has-dropdown"><i
				data-feather="file-text"></i><span>Appointments</span></a> 
				<ul class="dropdown-menu"> 
				    <li class=""><a class="nav-link" href="{{ route('appointments.index') }}">Listings</a></li>
                  
                    <li class="{{(Route::currentRouteName() == 'appointments-others') ? 'active' : ''}}"><a class="nav-link" href="{{URL::to('/admin/appointments-others')}}">Ajay Calendar</a></li>
                    <li class="{{(Route::currentRouteName() == 'appointments-jrp') ? 'active' : ''}}"><a class="nav-link" href="{{URL::to('/admin/appointments-jrp')}}">Shubam Calendar</a></li><!--(Tr/skill assessment/jrp)-->
                    <li class="{{(Route::currentRouteName() == 'appointments-education') ? 'active' : ''}}"><a class="nav-link" href="{{URL::to('/admin/appointments-education')}}">Education</a></li>
                    <li class="{{(Route::currentRouteName() == 'appointments-tourist') ? 'active' : ''}}"><a class="nav-link" href="{{URL::to('/admin/appointments-tourist')}}">Tourist visa</a></li>
					 <?php
                    if( Auth::user()->role == 1 || Auth::user()->role == 12 ){ //super admin or admin
                    ?>
                    <li class="{{(Route::currentRouteName() == 'admin.feature.appointmentdisabledate.index' ) ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.feature.appointmentdisabledate.index')}}">Block Slot</a></li>
                    <?php } ?>
					
				</ul> 
			</li>
            <?php
            if( Auth::user()->role == 1 || Auth::user()->role == 12 ){ //super admin or admin
            ?>
			<li class="dropdown">
				<a href="#" class="menu-toggle nav-link has-dropdown"><i
				data-feather="briefcase"></i><span>Blogs section</span></a> 
				<ul class="dropdown-menu"> 
					<li class=""><a class="nav-link" href="{{ route('admin.blogcategory.index') }}">Blogs Category</a></li>
					<li class=""><a class="nav-link" href="{{ route('admin.blog.index') }}">Blogs</a></li>
				</ul> 
			</li>
			<?php
            } ?>
				<?php
			if(Route::currentRouteName() == 'admin.leads.index' || Route::currentRouteName() == 'admin.leads.create' || Route::currentRouteName() == 'admin.leads.edit' || Route::currentRouteName() == 'admin.leads.history'){
				$leadstype = 'active'; 
			}
			?>
			<li class="dropdown {{@$leadstype}}">
				<a href="{{route('admin.leads.create')}}" class="nav-link"><i data-feather="user"></i><span>Add Lead</span></a>
			</li>

			<?php
			if( Route::currentRouteName() == 'assignee.activities' || Route::currentRouteName() == 'assignee.activities_completed' ){
				$assigneetype = 'active';
			}
			
			if(\Auth::user()->role == 1){
                //$assigneesCount = \App\Note::where('type','client')->whereNotNull('client_id')->where('folloup',1)->where('status',0)->orderBy('created_at', 'desc')->count();
                $assigneesCount = \App\Note::where('type','client')->whereNotNull('client_id')->where('folloup',1)->where('status',0)->count();
            }else{
                //$assigneesCount = \App\Note::where('assigned_to',Auth::user()->id)->where('type','client')->where('folloup',1)->where('status',0)->orderBy('created_at', 'desc')->count();
                $assigneesCount = \App\Note::where('assigned_to',Auth::user()->id)->where('type','client')->where('folloup',1)->where('status',0)->count();
            }
			?>
            <li class="dropdown {{@$assigneetype}}">
				<a href="{{route('assignee.activities')}}" class="nav-link">
                  <i data-feather="check"></i>
                  <span>Action
                    <span class="countTotalActivityAction" style="background: #1f1655;padding: 0px 5px;border-radius: 50%;color: #fff;margin-left: 5px;">{{ $assigneesCount }}</span>
                  </span>
              </a>
			</li>

			<!--<li class="dropdown {{-- @$assigneetype --}}"><!--
				<a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="check"></i><span>Activity   <span class="countAction" style="background: #1f1655;
					padding: 0px 5px;
					border-radius: 50%;
					color: #fff;">{{-- $assigneesCount --}}</span></span></a>

                <!--<ul class="dropdown-menu">-->
                    <?php //echo "@@@".Route::currentRouteName();?>
                    <!--<li class="{{-- (Route::currentRouteName()=='assignee.index')?'active':'' --}}">
                        <a class="nav-link" href="{{-- URL::to('/admin/assignee') --}}">Assignee</a>
                    </li>-->
                     <!--<li class="{{-- (Route::currentRouteName()=='assignee.assigned_by_me')?'active':'' --}}">
                        <a class="nav-link" id="assigned_by_me"  href="{{URL::to('/admin/assigned_by_me')}}">Assigned by me</a>
                    </li>
                    <li class="{{-- (Route::currentRouteName()=='assignee.assigned_to_me')?'active':'' --}}">
                        <a class="nav-link" id="assigned_to_me" href="{{URL::to('/admin/assigned_to_me')}}">Assigned to me</a>
                    </li>-->
                 <!--</ul>
			</li>-->

			<?php
            if( Auth::user()->role == 1 || Auth::user()->role == 12 ){ //super admin or admin
            ?>
    			@if(Route::currentRouteName() == 'admin.cms_pages.index' || Route::currentRouteName() == 'admin.cms_pages.create' || Route::currentRouteName() == 'admin.cms_pages.edit')
    			 @php	$cmsclasstype = 'active'; @endphp
    			@endif
    			
    			<li class="dropdown {{@$cmsclasstype}}">
    				<a href="{{route('admin.cms_pages.index')}}" class="nav-link"><i data-feather="briefcase"></i><span>CMS Pages</span></a>
    			</li>
		
            <?php
            }
            
			if(Route::currentRouteName() == 'admin.officevisits.index' || Route::currentRouteName() == 'admin.officevisits.waiting' || Route::currentRouteName() == 'admin.officevisits.attending' || Route::currentRouteName() == 'admin.officevisits.completed' || Route::currentRouteName() == 'admin.officevisits.archived'){
				$checlasstype = 'active'; 
			}
			 //if(\Auth::user()->role == 1){
                $InPersonwaitingCount = \App\CheckinLog::where('status',0)->count();
            /*}else{
                $InPersonwaitingCount = \App\CheckinLog::where('user_id',Auth::user()->id)->where('status',0)->orderBy('created_at', 'desc')->count();
            }*/
			?>
			<li class="dropdown {{@$checlasstype}}">
				<a href="{{route('admin.officevisits.waiting')}}" class="nav-link"><i data-feather="check-circle"></i><span>In Person<span class="countInPersonWaitingAction" style="background: #1f1655;
                    padding: 0px 5px;border-radius: 50%;color: #fff;margin-left: 5px;">{{ $InPersonwaitingCount }}</span></span></a>
			</li>
			<?php
			if(Route::currentRouteName() == 'admin.enquiries.index' || Route::currentRouteName() == 'admin.enquiries.archived'){
				$cheenquiriestype = 'active';
			}
			?>
			<li class="dropdown {{@$cheenquiriestype}}">
				<a href="{{route('admin.enquiries.index')}}" class="nav-link"><i data-feather="user-check"></i><span>Queries</span></a>
			</li>

			<?php
			
			if(Route::currentRouteName() == 'admin.clients.index' || Route::currentRouteName() == 'admin.clients.create' || Route::currentRouteName() == 'admin.clients.edit' || Route::currentRouteName() == 'admin.clients.detail'){
				$clientclasstype = 'active'; 
			}
			?> 
			<?php
				if(array_key_exists('21',  $module_access)) {
			?>
			<li class="dropdown {{@$clientclasstype}}">
				<a href="{{route('admin.clients.index')}}" class="nav-link"><i data-feather="user"></i><span>Clients Manager</span></a>
			</li>
			<?php
				}


				//if( Auth::user()->role == 1 ){ //super admin or admin

                    if(Route::currentRouteName() == 'admin.clients.clientreceiptlist'){
                        $clientaccountmanagerclasstype = 'active';
                    }
                    ?>
                <li class="dropdown {{@$clientaccountmanagerclasstype}}">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="file-text"></i><span>Client Account Manager</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{(Route::currentRouteName() == 'admin.clients.clientreceiptlist') ? 'active' : ''}}">
                            <a href="{{route('admin.clients.clientreceiptlist')}}" class="nav-link"><i data-feather="file-text"></i><span>Clients Receipts</span></a>
                        </li>
                      
                        <li class="{{(Route::currentRouteName() == 'admin.commissionreport') ? 'active' : ''}}">
                            <a href="{{route('admin.commissionreport')}}" class="nav-link"><i data-feather="file-text"></i><span>Commission Report</span></a>
                        </li>
                    </ul>
                </li>
                <?php
               // }



			if(Route::currentRouteName() == 'admin.services.index'){
				$serviceclasstype = 'active';
			}
		
				/*	if(Auth::user()->role == 1){
						$countfollowup = \App\Note::whereDate('followup_date', date('Y-m-d'))->count();					
					}else{
						$countfollowup = \App\Note::whereDate('followup_date', date('Y-m-d'))->where('assigned_to', Auth::user()->id)->count();
					}*/
					
	
									?>
			<!--<li class="dropdown {{--@$clientclasstype--}}">
				<a href="{{--URL::to('/admin/followup-dates/')--}}" class="nav-link"><i data-feather="user"></i><span>Today Followup <span class="countfollowup" style="background: #1f1655;
    padding: 0px 5px;
    border-radius: 50%;
    color: #fff;">{{--$countfollowup--}}</span></span></a>
			</li>-->
			<?php
				if(array_key_exists('5',  $module_access)) {
			?>			
			<li class="dropdown {{@$serviceclasstype}}">
				<a href="{{route('admin.services.index')}}" class="nav-link"><i class="fa fa-cogs"></i><span>Services</span></a>  
			</li>
			<?php
				}
			if(Route::currentRouteName() == 'admin.partners.index' || Route::currentRouteName() == 'admin.partners.create' || Route::currentRouteName() == 'admin.partners.edit' || Route::currentRouteName() == 'admin.partners.detail'){
				$partnerclasstype = 'active';
			}
			?> 
			<?php
				if(array_key_exists('7',  $module_access)) {
			?>
			<li class="dropdown {{@$partnerclasstype}}">
				<a href="{{route('admin.partners.index')}}" class="nav-link"><i data-feather="users"></i><span>Partners Manager</span></a>  
			</li>
			<?php
				}
			if(Route::currentRouteName() == 'admin.products.index' || Route::currentRouteName() == 'admin.products.create' || Route::currentRouteName() == 'admin.products.edit' || Route::currentRouteName() == 'admin.products.detail'){
				$productclasstype = 'active';
			}
			
			?> 
			<?php
				if(array_key_exists('12',  $module_access)) {
			?>
			<li class="dropdown {{@$productclasstype}}">
				<a href="{{route('admin.products.index')}}" class="nav-link"><i data-feather="shopping-cart"></i><span>Products Manager</span></a>  
			</li>
			<?php
				}
			if(Route::currentRouteName() == 'admin.applications.index'){
				$applicationclasstype = 'active';
			} 
			if(Route::currentRouteName() == 'admin.migration.index'){
				$migrationclasstype = 'active';
			} 
			?> 
			<?php
				if(array_key_exists('34',  $module_access)) {
			?>
			<li class="dropdown {{@$applicationclasstype}}">
				<a href="{{route('admin.applications.index')}}" class="nav-link"><i data-feather="server"></i><span>Applications Manager</span></a>  
			</li>
			<li class="dropdown {{@$migrationclasstype}}">
                <a href="{{route('admin.migration.index')}}" class="nav-link"><i data-feather="server"></i><span>Migration Manager</span></a>  
			</li>
			<?php
			}  
			?> 
			
			<?php
			if(Route::currentRouteName() == 'admin.quotations.index'){
				$quotationclasstype = 'active';
			}
			?> 	
			<?php
					if(array_key_exists('54',  $module_access)) {
					?>
			<li class="dropdown {{@$quotationclasstype}}">
				<a href="{{route('admin.quotations.index')}}" class="nav-link"><i data-feather="file-text"></i><span>Quotations</span></a>  
			</li>
			<?php
					}
			if(Route::currentRouteName() == 'admin.invoice.unpaid' || Route::currentRouteName() == 'admin.invoice.paid' || Route::currentRouteName() == 'admin.account.payment' || Route::currentRouteName() == 'admin.invoice.unpaidgroupinvoice' || Route::currentRouteName() == 'admin.invoice.paidgroupinvoice' || Route::currentRouteName() == 'admin.invoice.invoiceschedules' || Route::currentRouteName() == 'admin.account.payableunpaid' || Route::currentRouteName() == 'admin.account.payablepaid' || Route::currentRouteName() == 'admin.account.receivableunpaid' || Route::currentRouteName() == 'admin.account.receivablepaid'){
				$accountclasstype = 'active';
			}
			?> 	
			<li class="dropdown {{@$accountclasstype}}">
				<a href="#" class="menu-toggle nav-link has-dropdown"><i
				data-feather="dollar-sign"></i><span>Accounts</span></a>
				<ul class="dropdown-menu"> 
				<?php
					if(array_key_exists('46',  $module_access)) {
					?>
					<li class="{{(Route::currentRouteName() == 'admin.invoice.unpaid' || Route::currentRouteName() == 'admin.invoice.paid') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.invoice.unpaid')}}">Invoices</a></li>
					<?php } ?>
					<?php
					if(array_key_exists('47',  $module_access)) {
					?>
					<li class="{{(Route::currentRouteName() == 'admin.account.payment') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.account.payment')}}">Payment</a></li>
					<?php } ?>
					
					<!-- <li class="{{(Route::currentRouteName() == 'admin.invoice.unpaidgroupinvoice' || Route::currentRouteName() == 'admin.invoice.paidgroupinvoice') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.invoice.unpaidgroupinvoice')}}">Group Invoice</a></li> -->
					<li class="{{(Route::currentRouteName() == 'admin.invoice.invoiceschedules') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.invoice.invoiceschedules')}}">Invoice Schedule</a></li> 
					<li class="{{(Route::currentRouteName() == 'admin.account.payableunpaid' || Route::currentRouteName() == 'admin.account.payablepaid' || Route::currentRouteName() == 'admin.account.receivableunpaid' || Route::currentRouteName() == 'admin.account.receivablepaid') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.account.payableunpaid')}}">Income Sharing</a></li> 
				</ul>
			</li> 
			<?php
			if(Route::currentRouteName() == 'admin.agents.active' || Route::currentRouteName() == 'admin.agents.inactive' || Route::currentRouteName() == 'admin.agents.create' || Route::currentRouteName() == 'admin.agents.edit' || Route::currentRouteName() == 'admin.agents.detail'){
				$agentclasstype = 'active';
			}
			?> 
			<?php
				if(array_key_exists('15',  $module_access)) {
			?>
			<li class="dropdown {{@$agentclasstype}}">
				<a href="{{route('admin.agents.active')}}" class="nav-link"><i data-feather="users"></i><span>Agents Manager</span></a>  
			</li>
			<?php
				}
			/*if(Route::currentRouteName() == 'admin.tasks.index'){
				$taskclasstype = 'active';
			}*/
			?> 
		<!--<liclass="dropdown@$taskclasstype">
				<a href="{{-- route('admin.tasks.index') --}}" class="nav-link"><i data-feather="list"></i><span>To Do Lists</span></a>
			</li>-->
			
			<?php
            if( Auth::user()->role == 1 || Auth::user()->role == 12 ){ //super admin or admin
            ?>
			<li class="dropdown">
				<a href="#" class="menu-toggle nav-link has-dropdown"><i
				data-feather="file-text"></i><span>Reports</span></a> 
				<ul class="dropdown-menu"> 
				<?php
					if(array_key_exists('62',  $module_access)) {
					?>
					<li class=""><a class="nav-link" href="{{route('admin.reports.client')}}">Client</a></li>
					<li class=""><a class="nav-link" href="{{route('admin.reports.application')}}">Applications</a></li>
					<?php } ?>
					<?php
					if(array_key_exists('63',  $module_access)) {
					?>
					<li class=""><a class="nav-link" href="{{route('admin.reports.invoice')}}">Invoice</a></li>
					<?php } ?>
					<?php
					if(array_key_exists('64',  $module_access)) {
					?>
					<li class=""><a class="nav-link" href="{{route('admin.reports.office-visit')}}">Office Check-In</a></li>
					<?php } ?>
					<?php
					if(array_key_exists('65',  $module_access)) {
					?>
					<li class=""><a class="nav-link" href="{{route('admin.reports.saleforecast-application')}}">Sale Forecast</a></li>
					<?php } ?>
					<?php
					if(array_key_exists('68',  $module_access)) {
					?>
					<li class=""><a class="nav-link" href="{{route('admin.reports.personal-task-report')}}">Tasks</a></li>
					<?php } ?>
					<li class=""><a class="nav-link" href="{{URL::to('/admin/reports/visaexpires')}}">Visa Expires</a></li>
					<li class=""><a class="nav-link" href="{{URL::to('/admin/reports/agreementexpires')}}">Agreement Expires</a></li>
					
					@if(Auth::user()->role ===1)
                    <li class=""><a class="nav-link" href="{{route('admin.reports.noofpersonofficevisit')}}">Office Visit Report Date wise</a></li>
                    @endif
                    
                    
                    @if(Auth::user()->role ===1)
                    <li class=""><a class="nav-link" href="{{route('admin.reports.clientrandomlyselectmonthly')}}">Client Select Monthly Report</a></li>
                    @endif
                    
				</ul> 
			</li>
			<?php
            }
            
			if(Route::currentRouteName() == 'admin.auditlogs.index'){
				$auditlogsclasstype = 'active';
			}
			?> 

			@if(Auth::user()->role ===1)
			<li class="dropdown {{@$auditlogsclasstype}}">
				<a href="{{route('admin.auditlogs.index')}}" class="nav-link"><i data-feather="log-in"></i><span>Login Report</span></a>  
			</li>
			@endif
			<?php
			/* if(Route::currentRouteName() == 'admin.users.index' || Route::currentRouteName() == 'admin.users.create' || Route::currentRouteName() == 'admin.users.edit' || Route::currentRouteName() == 'admin.users.clientlist' || Route::currentRouteName() == 'admin.users.createclient' || Route::currentRouteName() == 'admin.users.editclient' || Route::currentRouteName() == 'admin.usertype.index' || Route::currentRouteName() == 'admin.usertype.create' || Route::currentRouteName() == 'admin.usertype.edit' || Route::currentRouteName() == 'admin.userrole.index' || Route::currentRouteName() == 'admin.userrole.create' || Route::currentRouteName() == 'admin.userrole.edit'){
				$userclasstype = 'active';
			}
			?> 			
			<li class="dropdown {{@$userclasstype}}">
				<a href="#" class="menu-toggle nav-link has-dropdown"><i
				data-feather="user"></i><span>User Management</span></a>
				<ul class="dropdown-menu">
					<li class="{{(Route::currentRouteName() == 'admin.users.index' || Route::currentRouteName() == 'admin.users.create' || Route::currentRouteName() == 'admin.users.edit') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.users.index')}}">Users</a></li>
					<li class="{{(Route::currentRouteName() == 'admin.users.clientlist' || Route::currentRouteName() == 'admin.users.createclient' || Route::currentRouteName() == 'admin.users.editclient') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.users.clientlist')}}">Create Client</a></li>
					<li class="{{(Route::currentRouteName() == 'admin.usertype.index' || Route::currentRouteName() == 'admin.usertype.create' || Route::currentRouteName() == 'admin.usertype.edit') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.usertype.index')}}">User Type</a></li>
					<li class="{{(Route::currentRouteName() == 'admin.userrole.index' || Route::currentRouteName() == 'admin.userrole.create' || Route::currentRouteName() == 'admin.userrole.edit') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.userrole.index')}}">User Role</a></li>
				</ul>
			</li>
			<?php 
			if(Route::currentRouteName() == 'admin.services.index' || Route::currentRouteName() == 'admin.services.create' || Route::currentRouteName() == 'admin.services.edit'){
				$servclasstype = 'active';
			}
			?> 
			<li class="dropdown {{@$servclasstype}}">
				<a href="#" class="menu-toggle nav-link has-dropdown"><i
				data-feather="user"></i><span>Services</span></a>
				<ul class="dropdown-menu"> 
					<li class="{{(Route::currentRouteName() == 'admin.services.index' || Route::currentRouteName() == 'admin.services.create' || Route::currentRouteName() == 'admin.services.edit') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.services.index')}}">Services List</a></li>
				</ul>
			</li>
			<?php
			 if(Route::currentRouteName() == 'admin.providers.index' || Route::currentRouteName() == 'admin.providers.create' || Route::currentRouteName() == 'admin.providers.edit'){
				$provclasstype = 'active';
			}
			?> 
			<li class="dropdown {{@$provclasstype}}">
				<a href="#" class="menu-toggle nav-link has-dropdown"><i
				data-feather="user"></i><span>Providers</span></a>
				<ul class="dropdown-menu">
					<li class="{{(Route::currentRouteName() == 'admin.providers.index' || Route::currentRouteName() == 'admin.providers.create' || Route::currentRouteName() == 'admin.providers.edit') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.providers.index')}}">Providers List</a></li>
				</ul>
			</li>
			<?php
			if(Route::currentRouteName() == 'admin.leads.index'){
				$leadclasstype = 'active';
			}
			?> 
			<li class="dropdown {{@$leadclasstype}}">
				<a href="{{route('admin.leads.index')}}" class="nav-link"><i data-feather="briefcase"></i><span>Leads</span></a>
			</li>
			<?php
			if(Route::currentRouteName() == 'admin.invoice.index'){
				$invclasstype = 'active';
			}
			?> 
			<li class="dropdown {{@$invclasstype}}">
				<a href="#" class="menu-toggle nav-link has-dropdown"><i
				data-feather="briefcase"></i><span>Invoices</span></a>
				<ul class="dropdown-menu">
					<li class="{{(Route::currentRouteName() == 'admin.invoice.index') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.invoice.index')}}">Invoices</a></li>
					<li><a class="nav-link" href="#">Payment Received</a></li>
				</ul>
			</li>
			<?php
			if(Route::currentRouteName() == 'admin.managecontact.index' || Route::currentRouteName() == 'admin.managecontact.create' || Route::currentRouteName() == 'admin.managecontact.edit'){
				$contclasstype = 'active';
			}
			?>
			<li class="dropdown {{@$contclasstype}}">
				<a href="{{route('admin.managecontact.index')}}" class="nav-link"><i data-feather="phone"></i><span>Manage Contacts</span></a>
			</li>
			<?php
			if(Route::currentRouteName() == 'admin.offer.index'){
				$offerclasstype = 'active';
			}
			?>
			<li class="dropdown {{@$offerclasstype}}">
				<a href="{{route('admin.offer.index')}}" class="nav-link"><i data-feather="gift"></i><span>Offers</span></a>
			</li>
			<?php
			if(Route::currentRouteName() == 'admin.cms_pages.index'){
				$cmspgclasstype = 'active';
			}
			?>
			<li class="dropdown {{@$cmspgclasstype}}">
				<a href="{{route('admin.cms_pages.index')}}" class="nav-link"><i data-feather="file"></i><span>Pages</span></a>
			</li>
			<?php
			if(Route::currentRouteName() == 'admin.customer.index' || Route::currentRouteName() == 'admin.staff.index'){
				$reguvclasstype = 'active';
			}
			?> 
			<li class="dropdown {{@$reguvclasstype}}">
				<a href="#" class="menu-toggle nav-link has-dropdown"><i
				data-feather="users"></i><span>Users</span></a>
				<ul class="dropdown-menu">
					<li class="{{(Route::currentRouteName() == 'admin.customer.index') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.customer.index')}}">Registered Users</a></li> 
					<li class="{{(Route::currentRouteName() == 'admin.staff.index') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.staff.index')}}">Staffs</a></li>
				</ul>
			</li>
			<?php
			if(Route::currentRouteName() == 'admin.email.index'){
				$emtemclasstype = 'active';
			}
			?>
			<li class="dropdown {{@$emtemclasstype}}">
				<a href="{{route('admin.email.index')}}" class="nav-link"><i data-feather="mail"></i><span>Email Templates</span></a>
			</li>
			<?php
			if(Route::currentRouteName() == 'admin.my_profile' || Route::currentRouteName() == 'admin.change_password' || Route::currentRouteName() == 'admin.edit_api'){
				$actsetclasstype = 'active';
			}*/ 
			?> 
			<!--<li class="dropdown {{@$actsetclasstype}}">
				<a href="#" class="menu-toggle nav-link has-dropdown"><i
				data-feather="settings"></i><span>My Account & Settings</span></a>
				<ul class="dropdown-menu">
					<li class="{{(Route::currentRouteName() == 'admin.my_profile') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.my_profile')}}">Manage Profile</a></li>
					<li class="{{(Route::currentRouteName() == 'admin.change_password') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.change_password')}}">Change Password</a></li>
					<li class="{{(Route::currentRouteName() == 'admin.edit_api') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.edit_api')}}">Api Key</a></li>
				</ul> 
			</li>-->
			<li class="dropdown">
				<a href="{{route('admin.logout')}}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i data-feather="log-out"></i><span>Logout</span></a>
				{{ Form::open(array('url' => 'admin/logout', 'name'=>'admin_login', 'id' => 'logout-form')) }}
				<input type="hidden" name="id" value="{{Auth::user()->id}}">
				{{ Form::close() }}
			</li>
		</ul>
	</aside>
</div>