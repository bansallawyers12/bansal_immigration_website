@extends('layouts.admin')
@section('title', 'Admin Dashboard')

@section('content')

<!-- Main Content -->
<div class="main-content">
	<section class="section">
		


        <div class="row">

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-4">
				<div class="card dash_card">
				    <?php

					if(Auth::user()->role == 1){
						$countfollowup = \App\Note::select('id')->whereDate('followup_date', date('Y-m-d'))->count();
					}else{
						$countfollowup = \App\Note::whereDate('followup_date', date('Y-m-d'))->where('assigned_to', Auth::user()->id)->count();
					}
                    
                    ?>
					<div class="card-statistic-4">
						<div class="align-items-center justify-content-between">
							<div class="row ">
								<div class="col-lg-12 col-md-12">
										<div class="card-content">
										<h5 class="font-14">Today Followup</h5>
										<h2 class="mb-3 font-18">{{$countfollowup}}</h2>
										<p class="mb-0"><span class="col-green">{{$countfollowup}}</span> <a href="{{URL::to('/admin/followup-dates/')}}">click here</a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-4">
                <div class="card dash_card">
                    <div class="card-statistic-4">
                        <div class="card-content cus_card_content">
                            <div class="card_header">
                                <h5 class="font-14">My Appointment</h5>
                                <!--<a href="javascript:;" data-toggle="modal" data-target=".add_appiontment" class="btn btn-outline-primary btn-sm add_btn"><i class="fa fa-plus"></i> Add</a>-->
                            </div>
                            <div class="card_body">
                                <?php
                                //$atotalData = \App\Appointment::whereDate('date', date('Y-m-d'))->count(); dd($atotalData );
                                $atotalData = \App\Appointment::whereDate('date', date('Y-m-d'))->select('date','time','client_id','title')->orderby('created_at','Desc')->get();
                                //echo "$$$".count($atotalData);die;
                                ?>
                                @if(@count($atotalData) !== 0)
                                <div class="appli_remind">
                                <?php
                                //foreach(\App\Appointment::whereDate('date', date('Y-m-d'))->orderby('created_at','Desc')->get() as $alist)
                                foreach($atotalData as $alist)
                                {
                                    $day = date('d', strtotime($alist->date));
                                    $time = date('h:i A', strtotime($alist->time));
                                    $week = date('D', strtotime($alist->date));
                                    $month = date('M', strtotime($alist->date));
                                    $year = date('Y', strtotime($alist->date));
                                    $admin = \App\Admin::where('id', $alist->client_id)->select('id','first_name')->first();
                                    ?>
                                    <div class="appli_column">
                                        <div class="date">{{$day}}<span>{{$week}}</span>
                                        </div>
                                        <div class="appli_content">
                                            <a href="{{URL::to('admin/clients/detail/')}}/{{base64_encode(convert_uuencode(@$admin->id))}}">{{@$admin->first_name}}</a>
                                            <div class="event_end"><span></span> - {{@$alist->title}}</div>
                                            <span class="end_date">{{$month}} {{$year}} {{$time}}</span>
                                        </div>
                                    </div>
                                    </tr>
                                <?php
                                }
                                ?>
                                </div>
                                @else
                                <p class="text-muted">All Clear! No appointments.</p>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-4">
                <div class="card dash_card">
                    <div class="card-statistic-4">
                        <div class="card-content cus_card_content">
                            <div class="card_header">
                                <h5 class="font-14">My Tasks for </h5>
                                <!--<a href="javascript:;" id="create_task" class="btn btn-outline-primary btn-sm add_btn"><i class="fa fa-plus"></i> Add</a>-->
                            </div>
                            <div class="card_body">
                                <?php
                                if(Auth::user()->role == 1){
                                    //echo date('Y-m-d');
                                    $liststodo = \App\Task::whereDate('due_date', date('Y-m-d'))->select('id','user_id','status','due_date','due_time')->orderby('created_at','Desc')->get();
                                }else{
                                    $liststodo = \App\Task::whereDate('due_date', date('Y-m-d'))
                                    ->where(function($query){
                                        $query->where('assignee', Auth::user()->id)
                                              ->orWhere('followers', Auth::user()->id);
                                    })->select('id','user_id','status','due_date','due_time')->orderby('created_at','Desc')->get();
                                } //dd($liststodo);
                                ?>
                                @if(@$totalData !== 0)
                                <div class="taskdata_list">
                                    <div class="table-responsive">
                                        <table id="my-datatable" class="table-2 table text_wrap">
                                            <tbody class="taskdata">
                                            <?php
                                            foreach($liststodo as $alist)
                                            { //dd($alist);
                                                $admin = \App\Admin::where('id', $alist->user_id)->select('last_name','first_name')->first();//dd($admin);
                                                if($admin){
                                                    $first_name = $admin->first_name ?? 'N/A';
                                                    $last_name = $admin->last_name ?? 'N/A';
                                                    $full_name = $first_name.' '.$last_name;
                                                } else {
                                                    $full_name = 'N/A';
                                                } ?>
                                                <tr class="opentaskview" style="cursor:pointer;" id="{{$alist->id}}">
                                                    <td><?php if($alist->status == 1 || $alist->status == 2){ echo "<span class='check'><i class='fa fa-check'></i></span>"; } else{ echo "<span class='round'></span>"; } ?></td>
                                                    <td>{{$full_name}}<br><i class="fa fa-clock"></i>{{date('d/m/Y h:i A', strtotime($alist->due_date.' '.$alist->due_time))}} </td>
                                                    <td>
                                                    <?php
                                                    if($alist->status == 1){
                                                        echo '<span style="color: rgb(113, 204, 83); width: 84px;">Completed</span>';
                                                    }else if($alist->status == 2){
                                                        echo '<span style="color: rgb(255, 173, 0); width: 84px;">In Progress</span>';
                                                    }else if($alist->status == 3){
                                                        echo '<span style="color: rgb(156, 156, 156); width: 84px;">On Review</span>';
                                                    }else{
                                                        echo '<span style="color: rgb(255, 173, 0); width: 84px;">Todo</span>';
                                                    }
                                                    ?></td>
                                                </tr>
                                                <?php
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @else
                                <p class="text-muted">No tasks at the moment.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-4">
                <div class="card dash_card">
                    <div class="card-statistic-4">
                        <?php
                        $checkins 		= \App\CheckinLog::where('id', '!=', '')->where('status', '=', '0')->select('id','client_id','created_at');
                        $checkinstotalData 	= $checkins->count();
                        $checkinslists		= 		$checkins->get()
                        ?>
                        <div class="card-content cus_card_content">
                            <div class="card_header">
                                <h5 class="font-14">Check-In Queue</h5>
                            </div>
                            <div class="card_body">
                            @if($checkinstotalData !== 0)
                                <table>
                                    <tbody>
                                    @foreach($checkinslists as $checkinslist)
                                        <?php
                                        $client = \App\Admin::where('role', '=', '7')->where('id', '=', $checkinslist->client_id)->select('last_name','first_name')->first();
                                        ?>
                                        <tr>
                                            <td><a id="{{@$checkinslist->id}}" class="opencheckindetail" href="javascript:;">{{@$client->first_name}} {{@$client->last_name}} </a>
                                            <br>
                                            <span>Waiting since {{date('h:i A', strtotime($checkinslist->created_at))}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">No office check-in at the moment.</p>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            
       </div>

	</section>
</div>



@endsection

