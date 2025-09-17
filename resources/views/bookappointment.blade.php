@extends('layouts.frontend')
@section('seoinfo')
	<title>Book An  Appointment | Bansal Immigration-Your Future, Our Priority</title>
	<meta name="description" content="| Book An  Appointment" />
	<link rel="canonical" href="<?php echo URL::to('/'); ?>/book-an-appointment" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="Book An  Appointment | Bansal Immigration-Your Future, Our Priority" />
	<meta property="og:description" content="| Book An  Appointment" />
	<meta property="og:url" content="<?php echo URL::to('/'); ?>/book-an-appointment/" />
	<meta property="og:site_name" content="Bansal Immigration - Your Future, Our Priority" />
	<meta property="article:publisher" content="https://www.facebook.com/BANSALImmigration/" />
	<meta property="article:modified_time" content="2021-08-30T09:05:04+00:00" />
	<meta property="og:image" content="{{asset('img/bansal-immigration-icon.jpg')}}" />
	<meta property="og:image:width" content="200" />
	<meta property="og:image:height" content="200" />
	<meta property="og:image:type" content="image/jpeg" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@Bansalimmi" />
	<meta name="twitter:label1" content="Est. reading time" />
	<meta name="twitter:data1" content="3 minutes" />
@endsection
@section('content')
<style>
.timeslots .timeslot_col.active{/*border:1px solid #0062cc;background-color:#fff; */background-color: #007bff;margin: 0px 10px 8px 0px;}
#preloaderbook {
	display:none;
    background: #0d104d;
    background: -webkit-linear-gradient(to right, #0d104d, #28408b);
    background: linear-gradient(to right, #0d104d, #28408b);F
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    z-index: 5000;
}
#preloaderbook .circle-preloader {
    display: block;
    width: 60px;
    height: 60px;
    border: 2px solid rgba(255, 255, 255, 0.5);
    border-bottom-color: #ffffff;
    border-radius: 50%;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    animation: spin 2s infinite linear;
}


b, strong {
    font-weight: bold !important;
}
.appointment_page ol li {
    line-height: 30px !important;
}

.appointment_page p {
    line-height: 25px !important;
}

.services_item_title_span {
    font-size: 18px;
    line-height: 21px;
    color: #828F9A;
    display: inline-block;
    padding-left: 10px;
}

.services_item_duration {
    font-size: 14px;
    line-height: 18px;
    color: #828F9A;
    display: inline-block;
}
.services_item_price {
    float: right;
    display: inline-block;
    font-size: 24px;
    line-height: 30px;
    color: #53d56c;
   /* margin-top: 10px;*/
}

.services_item_description {
    font-size: 14px;
   /* line-height: 18px;*/
    color: #828F9A;
    display: inline-block;
    margin-bottom: 10px;
    margin-left: 25px;
    margin-top: 5px;
}
.heading_title {
    font-size: 18px;
    line-height: 21px;
    color: #000;
    font-weight: 600;
}
.form-group label {
    font-size: 15px;
    line-height: 21px;
    color: #000;
    font-weight: 600;
    margin: 0px 0px 6px;
}
.tab_header h4 {
    font-size: 18px;
    line-height: 21px;
    color: #000;
    font-weight: 600;
    margin: 0px;
}


#loading, #loading_popup{
    width: 100%;
    height: 100%;
    top: 0px;
    left: 0px;
    position: fixed;
    display: none;
    opacity: 0.7;
    background-color: #fff;
    z-index: 99;
    text-align: center;
}

#loading-image {
    position: absolute;
    top: 100px;
    left: 600px;
    z-index: 100;
}

#loading-image_popup {
    position: absolute;
    top: 100px;
    left: 100px;
    z-index: 100;
}


.row.no-gutters {
    margin-right: 0;
    margin-left: 0;
}

.row.no-gutters > [class*='col-'] {
    padding-right: 0;
    padding-left: 0;
}

.card-expiry-month {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none;
    text-align: right; /* Center the placeholder */
    padding-right: 0; /* Reduce padding */
}

.card-expiry-year {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: none;
    text-align: left; /* Center the placeholder */
    padding-left: 0; /* Reduce padding */
}

.hide {
    display: none !important;
}

ol.custom-alpha {
    list-style-type: upper-alpha;
}
ol > li::marker {
    color: #292929;
    line-height: 1.3;
    font-weight: 700;
}

/* Media Query for Mobile Devices */
@media (max-width: 480px) {
    .datePickerCls {height: 230px;width:200px;}
    .timeslotDivCls { width:65%;margin-top: 25px;}
    .confirmTblCls {width: 320px !important;}
    #loading-image,#loading-image_popup {position: absolute;top:0;left: 45px;z-index: 100;}
  ol{margin-left: 15px;}
}

/* Media Query for low resolution  Tablets, Ipads */
@media (min-width: 481px) and (max-width: 767px) {
    .datePickerCls {height: 230px;width:200px;}
    .timeslotDivCls { width:65%;margin-top: 25px;}
    .confirmTblCls {width: 481px !important;}
}

/* Media Query for Tablets Ipads portrait mode */
@media (min-width: 768px) and (max-width: 1024px){
    .datePickerCls {height: 230px;width:200px;}
    .timeslotDivCls { width:65%;margin-top: 25px;}
    .confirmTblCls {width: 458px !important;}
}

 /* Media Query for Laptops and Desktops */
 @media (min-width: 1025px) and (max-width: 1280px){
    .datePickerCls {height: 210px;margin-left: 15px;}
    .timeslotDivCls { width:65%;margin-left: 265px;margin-top: -212px;}
    .confirmTblCls {width: 802px !important;}
}

/* Media Query for Large screens */
@media (min-width: 1281px) {
    .datePickerCls {height: 210px;margin-left: 15px;}
    .timeslotDivCls { width:65%;margin-left: 265px;margin-top: -212px;}
    .confirmTblCls {width: 802px !important;}
}

</style>
<section class="custom_breadcrumb bg-img bg-overlay" style="background-image: url({{asset('img/Frontend/bg-2.jpg')}}); padding-top:40px">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-heading text-center mx-auto">
					<div class="section-header">
						<h1 style="color:#FFF !important;">Book An  Appointment</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="custom_inner_page appointment_page" section-padding-5-0="" style="background-color:#F7F7F7;">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-12 col-md-12">
				<div class="cus_inner_content">
                    <ol>
                        <li>
                            <h4>Migration Consultation (Free Option)</h4>
                            <ol type="A" style="margin-left:50px;">
                                <li>
                                    <h6 style="display:inline;">In-Person or Phone Meetings:</h6> We offer free consultations to discuss your immigration matters, either in person or over the phone. If your inquiry is related to Australian Permanent Residency (PR), we encourage you to select the PR Appointment option and provide the required information in advance.
                                </li>
                                <li>
                                    <h6 style="display:inline;">10-Minute Free Phone Consultations:</h6> We also provide a 10-minute free phone consultation for any immigration-related matter. However, due to time constraints and the need for specific details to provide accurate advice, we kindly request that you include all relevant information in the query box when booking your consultation.
                                </li>
                            </ol>
                        </li>


                        <li style="padding-top: 10px;">
                            <h4>Overseas Applicant Enquiry</h4>
                            <ol type="A" style="margin-left:50px;">
                                <li>
                                    <h6 style="display:inline;">Paid Appointment Only:</h6> The first free appointment service is exclusively available to clients who are currently within Australia. If you are outside Australia or inquiring on behalf of someone outside Australia, please select the Migration Advice option and pay the consultation fee of $150.
                                </li>
                                <li>
                                    <h6 style="display:inline;">Required Information:</h6> To assist us in providing accurate advice, please send a detailed resume of the applicant, along with any specific questions you have, to our email address. This will allow us to review the information before discussing the matter with you.
                                </li>
                            </ol>
                        </li>


                        <li style="padding-top: 10px;">
                            <h4>Migration Advice (Paid Service)</h4>
                            <ol type="A" style="margin-left:50px;">
                                <li>
                                    <h6 style="display:inline;">Consultation Fee:</h6> Our fee is $150 for most types of migration advice. Please select this option if your matter is complicated and includes Administrative Appeals Tribunal (AAT) cases, Visa cancellations, or Protection visas, etc. If you believe it will require more than 10 minutes to discuss, please select this paid option.
                                </li>
                                <li>
                                    <h6 style="display:inline;">Free Consultation Policy:</h6> Please note that we only provide one free consultation per client. If you have already used your free consultation, you will need to select this paid option for further advice.
                                </li>
                            </ol>
                        </li>
                    </ol>

                    <p><i>If you do not provide the required information, we may cancel your appointment without prior notice.</i></p>

                    <p>Additional Information:</p>
                    <p>If you book your appointment via this link, all appointments will be scheduled with one of our <strong>available MARA agents</strong>. Should you want to talk to any specific person, kindly mention their names in the query section.</p>

                    <p><strong>Ajay Bansal is no longer involved with BANSAL Immigration. He has his own firm, BANSAL Lawyers. If you want to book an appointment with him, kindly click this link to book appointment: </strong><a style="text-decoration: underline;" href="https://www.bansallawyers.com.au/book-an-appointment">Book an Appointment | Schedule a Consultation with Top Law Firm Bansal Lawyers Melbourne</a>.</p>

                    <p>Need Help?</p>
                    <p>If you're unsure which option to choose, feel free to call our friendly staff at <strong>03 9602 1330</strong>. They can assist with booking your appointment or help you choose the right option.</p>
                </div>

				<div class="appointment_form_tabs">
					<form class="contact_form" id="appintment_form" action="<?php echo URL::to('/'); ?>/book-an-appointment/store" method="post" enctype="multipart/form-data">

                        <div class="cus_tab_form">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="tab_logo">
									<a href="#">
										<img src="{{asset('img/logo_img/bansal-imm-logo-11_vrUFM77pu7.png')}}" alt="Bansal Immigration Consultants" />
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link active" id="nature_of_enquiry-tab" onclick="goto('nature_of_enquiry')" data-toggle="tab" href="#nature_of_enquiry" role="tab" aria-controls="nature_of_enquiry" aria-selected="true">Nature of Enquiry</a>
								</li>
								<li class="nav-item">
									<a class="nav-link disabled" id="services-tab" onclick="goto('services')" data-toggle="tab" href="#services" role="tab" aria-controls="services" aria-selected="true">Services</a>
								</li>
								<li class="nav-item">
									<a class="nav-link disabled" id="appointment_details-tab" onclick="goto('appointment_details')" data-toggle="tab" href="#appointment_details" role="tab" aria-controls="appointment_details" aria-selected="true">Location</a>
								</li>
								<li class="nav-item">
									<a class="nav-link disabled" id="info-tab" data-toggle="tab" onclick="goto('info')" href="#info" role="tab" aria-controls="info" aria-selected="true">Information</a>
								</li>

								<li class="nav-item">
									<a class="nav-link disabled" id="confirm-tab" data-toggle="tab" onclick="goto('confirm')" href="#confirm" role="tab" aria-controls="confirm" aria-selected="false">Confirmation</a>
								</li>
                            </ul>


                            <div id="confirm_div" class="col-12 col-md-6 col-lg-6" style="margin-left: 15px;width: 90%;">

                                <div id="loading">
                                    <img id="loading-image" src="{{asset('images/ajax-loader.gif')}}" alt="Loading..." />
                                </div>

                                <div class="row nature_of_enquiry_row" id="nature_of_enquiry">
                                    <div class="form-group">
                                        <label for="noe_id" style="margin-top: 10px;" class="heading_title">Nature of Enquiry </label>
                                        <select data-valid="" class="form-control enquiry_item" name="noe_id">
                                            <option value="">Select</option>
                                            @foreach(\App\NatureOfEnquiry::where('status',1)->get() as $enquiry)
                                                <option value="{{$enquiry->id}}">{{$enquiry->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row services_row" id="services" style="display: none;">
                                    <div class="form-group">
                                        <label for="service_id" class="heading_title">Services</label>
                                        @foreach(\App\BookService::where('status',1)->get() as $bookservices)
                                            <div class="services_item_header" id="serviceval_{{$bookservices->id}}">
                                                <div class="services_item_title">
                                                    <input type="radio" class="services_item" name="radioGroup"  value="{{$bookservices->id}}">
                                                    <div class="services_item_img" style="display:inline-block;margin-left: 10px;">
                                                        <img style="width: 80px;height:80px;" src="{{asset('img/service_img')}}/{{$bookservices->image}}" alt="{{$bookservices->title}}">
                                                    </div>
                                                    <span class="services_item_title_span">{{$bookservices->title}} <br/>{{$bookservices->duration}} minutes</span>
                                                    <span class="services_item_price"> {{$bookservices->price}}</span>
                                                    <span class="services_item_description">{{$bookservices->description}}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                        <input type="hidden"  id="service_id" name="service_id" value="">
                                    </div>
                                </div>

                                <div class="row appointment_row" id="appointment_details" style="display: none;">
                                    <div class="form-group inperson_address_cls">
                                        <label for="inperson_address" class="heading_title">Location</label>
                                        <div class="inperson_address_header" id="inperson_address_1">
                                            <label class="inperson_address_title">
                                                <input type="radio" class="inperson_address" name="inperson_address" data-val="1" value="1">
                                                <div class="inperson_address_title_span">
                                                    ADELAIDE<br/><span style="font-size: 10px;">(Unit 5 5/55 Gawler Pl, Adelaide SA 5000)</span>
                                                </div>
                                            </label>

                                            <label class="inperson_address_title">
                                                <input type="radio" class="inperson_address" name="inperson_address" data-val="2" value="2">
                                                <div class="inperson_address_title_span">
                                                    MELBOURNE<br/><span style="font-size: 10px;">(Next to Flight Center, Level 8/278 Collins St, Melbourne VIC 3000, Australia)</span>
                                                </div>
                                            </label>
                                        </div>

                                        <style>
                                            .inperson_address_header {
                                                display: flex;
                                                align-items: center;
                                                gap: 20px; /* Adjust spacing between radio options */
                                                flex-wrap: nowrap; /* Ensures everything stays in one line */
                                            }

                                            .inperson_address_title {
                                                display: flex;
                                                align-items: center;
                                                gap: 8px; /* Space between radio button and text */
                                                white-space: nowrap; /* Prevents text from breaking into multiple lines */
                                            }

                                            .inperson_address_title_span {
                                                display: inline-block;
                                                color: #828F9A;
                                            }
                                            /* Mobile Devices: Stack items vertically */
                                            @media (max-width: 768px) {
                                                .inperson_address_header {
                                                    display: inline;
                                                }
                                            }
                                        </style>
                                    </div>

                                    <div class="form-group appointment_details_cls" style="display: none;">
                                        <label for="appointment_details" class="heading_title">Appointment details</label>
                                        <select data-valid="" class="form-control appointment_item" name="appointment_details">
                                            <option value="">Select</option>
                                            <option value="phone"> Phone</option>
                                            <option value="in_person">In person</option>
                                            <option value="zoom_google_meeting" style="display: none;">Zoom / Google Meeting</option>
                                        </select>
                                     </div>

									 <div class="form-group appointment_details_cls" style="display: none;margin-left:50px;">
                                        <label for="preferred_language" class="heading_title">Preferred Language</label>
                                        <select data-valid="" class="form-control preferred_language" name="preferred_language">
                                            <option value="">Select</option>
                                            <option value="Hindi"> Hindi</option>
                                            <option value="English">English</option>
                                            <option value="Punjabi">Punjabi</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row info_row" id="info" style="display: none;">
                                    <div class="tab_header">
										<!--<h4 style="margin: 15px 0px 15px;">Fill Information</h4>-->
									</div>
									<form id="fromtopupvalues">
                                        <div class="tab_body">
                                            <input type="hidden" class="form-control " placeholder="" name="noe_id" />
                                            <input type="hidden" class="form-control " placeholder="" name="service_id" />
                                            <input type="hidden" class="form-control " placeholder="" name="date" />
                                            <input type="hidden" class="form-control " placeholder="" name="time" />
                                            <input type="hidden" class="form-control " placeholder="" name="appointment_details" />
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fullname">Full Name</label>
                                                        <input type="text" class="form-control fullname infoFormFields" placeholder="Enter Name" name="fullname" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input id="email" type="email" class="form-control email infoFormFields" placeholder="Enter Email" name="email" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label for="phone">Phone (With Country Code)</label>
                                                        <input style="display: inline-block;width: 230px;" id="phone" type="text" class="form-control phone infoFormFields" placeholder="+61285038000" name="phone" />
                                                        <button style="display: none;" class="btn btn-outline-secondary" type="button" id="sendCodeBtn">Send Code</button>
                                                        <span id="sendCodeBtn_txt" style="display: none;"></span>
                                                        <input type="hidden" id="phone_verified_bit" name="phone_verified_bit" value="0">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-9" id="verificationCodeSection"  style="display: none;">
                                                    <div class="form-group">
                                                        <input style="display: inline-block;width: 225px;" type="text" class="form-control" id="verification_code" placeholder="Enter verification code">
                                                        <button class="btn btn-outline-secondary" type="button" id="verifyCodeBtn">Verify Code</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description">Details Of Enquiry </label>
                                                        <textarea class="form-control description infoFormFields" placeholder="Enter Details Of Enquiry" name="description"></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description">Date & Time</label>

                                                        <div style="width:150%;height:205px;">
                                                            <div style="width:30%">
                                                                <div id='datetimepicker' class="datePickerCls infoFormFields"></div>
                                                            </div>
                                                            <div class="timeslotDivCls">
                                                                <div class="showselecteddate infoFormFields" style="font-size: 14px;text-align: center; padding: 5px 0 3px;border-bottom: 1px solid #E3EAF3;color: #0d0d0f !important;font-weight: bold;"></div>
                                                                <div class="timeslots infoFormFields" style="overflow:scroll !important;height:185px;"></div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden"  id="timeslot_col_date"  value=""  >
                                                        <input type="hidden"  id="timeslot_col_time"  value=""  >
                                                        <span class="timeslot_col_date_time" role="alert" style="display: none;color:#f00;">Date and Time is required.</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-btn text-center">
                                                        <input type="button" class="btn btn-primary  nextbtn" style="margin-bottom:12px;margin-top:12px;" data-steps="confirm" name="submit" value="Confirm" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</form>
                                </div>


                                <div class="row confirm_row" id="confirm" style="display: none;">
                                    <div class="tab_header">
										<h5 style="margin: 15px 0px 15px;">Confirm Details</h5>
									</div>
									<div class="tab_body">
                                        <div class="row">
											<div class="col-md-12">
												<div class="table-responsive confirmTblCls">
													<table class="table table-bordered table-striped">
														<thead>
															<tr>
																<th>Full Name</th>
																<th>Email</th>
																<th>Phone</th>
																<!--<th>Reference if any</th>-->
																<th>Description</th>
																<th>Date</th>
																<th>Time</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="full_name"></td>
																<td class="email"></td>
																<td class="phone"></td>
																<!--<td class="title"></td>-->
																<td class="description"></td>
																<td class="date"></td>
																<td class="time"></td>
															</tr>
														</tbody>
													</table>
												</div>
												<ul class="errors">
												</ul>

                                                <div class="col-md-12">
                                                    <div class="form-btn text-center">
                                                        <!--If paid option selected -->
                                                        <button type="button" class="btn btn-primary submitappointment_paid" data-toggle="modal" data-target="#exampleModal" style="margin-bottom:12px;margin-top:12px;">Pay & Submit</button>
                                                        <!--If free option selected -->
                                                        <input type="button" class="btn btn-primary submitappointment" style="margin-bottom:12px;margin-top:12px;" name="submit" value="Submit" />
                                                    </div>
                                                </div>
											</div>
										</div>
									</div>
                                </div>

                            </div>
                        </div>
                    </form>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Payment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
            @csrf
                <div id="loading_popup">
                    <img id="loading-image_popup" src="{{asset('images/ajax-loader.gif')}}" alt="Loading..." />
                </div>
                <div class="modal-body">
                    <input type="hidden" name="noe_id" id="noe_id_paid" value="">
                    <input type="hidden" name="radioGroup" id="radioGroup_paid" value="">
                    <input type="hidden" name="service_id" id="service_id_paid" value="">
                    <input type="hidden" name="inperson_address" id="inperson_address_paid" value="">
                    <input type="hidden" name="appointment_details" id="appointment_details_paid" value="">

                    <input type="hidden" name="date" id="date_paid" value="">
                    <input type="hidden" name="time" id="time_paid" value="">
                    <input type="hidden" name="fullname" id="fullname_paid" value="">
                    <input type="hidden" name="email" id="email_paid" value="">
                    <input type="hidden" name="phone" id="phone_paid" value="">
                    <input type="hidden" name="title" id="title_paid" value="">
                    <input type="hidden" name="description" id="description_paid" value="">

                    <div class='form-row row'>
						<div class='col-xs-12 col-md-12 form-group required'>
							<label class='control-label'>Name on Card</label>
							<input class='form-control card-name' size='4' type='text' maxlength="40">
						</div>
					</div>

					<div class='form-row row'>
						<div class='col-xs-12 col-md-12 form-group card required' style="border:none;">
							<label class='control-label'>Card Number</label>
							<input autocomplete='off' class='form-control card-number' size="20"  maxlength="20" type="text">
						</div>
					</div>

					<div class='form-row row'>
						<div class='col-xs-12 col-md-3 form-group cvc required'>
							<label class='control-label'>CVC</label>
							<input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' maxlength="4" type='text'>
						</div>
                        <div class='col-xs-12 col-md-8 form-group expiration required'>
                            <label class='control-label'>Expiration Month & Year</label>
                            <div class="row no-gutters">
                                <div class="col-xs-6 col-md-3">
                                    <input class='form-control card-expiry-month' placeholder='MM' size='2' maxlength="2" type='text'>
                                </div>
                                <div class="col-xs-6 col-md-3">
                                    <input class='form-control card-expiry-year' placeholder='YYYY' size='4' maxlength="4" type='text'>
                                </div>
                            </div>
                        </div>
					</div>

					<div class='form-row row'>
						<div class='col-md-12 error form-group hide'>
							<div class='alert-danger alert'>Please correct the errors and try again.</div>
						</div>
					</div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now ($150)</button>
                </div>

            </form>
        </div>
    </div>
</div>

<div id="querysuccess_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" class="modal fade custom_modal thankyoupack_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="text-center padding64">
					<div class="query_icons">
						<i class="fa-light fa-paper-plane"></i>
					</div>
					<div class="query_heading">
						<h4>Thank You!</h4>
					</div>
					<div class="query_info">
						<p>Your request is submitted successfully!<br/> Our Expert will get in tough with you at the earliest.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
$(function() {

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //First Check phone no is verified or not
    $('#phone').blur(function() {
        $('#sendCodeBtn').hide();
        $('#sendCodeBtn_txt').hide();
        const phoneNumber = $('#phone').val();
        if (!phoneNumber) return;

        $.post('{{ route("verify.is-phone-verify-or-not") }}', {
            phone_number: phoneNumber
        })
        .done(function(response) {
            //alert('success='+response.status_bit);
            if(response.status_bit == 1){
                $('#sendCodeBtn').hide();
                $('#sendCodeBtn_txt').css('display','block');
                $('#sendCodeBtn_txt').css('color','green');
                $('#sendCodeBtn_txt').text(response.message);
                $('#phone_verified_bit').val(1);
            } else {
                $('#sendCodeBtn').show();
                $('#sendCodeBtn_txt').css('color','red');
                $('#sendCodeBtn_txt').css('display','block');
                $('#sendCodeBtn_txt').text(response.message);
                $('#phone_verified_bit').val(0);
            }
        })
        .fail(function(xhr) {  //alert('fail='+xhr.status_bit);
            //alert('Failed to send verification code');
            $('#sendCodeBtn').show();
            $('#sendCodeBtn_txt').css('color','red');
            $('#sendCodeBtn_txt').css('display','block');
            $('#sendCodeBtn_txt').text('Phone number is not verified.');
            $('#phone_verified_bit').val(0);
        });
    });

    //Function to send code button
    $('#sendCodeBtn').click(function() {
        const phoneNumber = $('#phone').val();
        if (!phoneNumber) return;

        $.post('{{ route("verify.send-code") }}', {
            phone_number: phoneNumber
        })
        .done(function(response) {
            alert(response.message);
            $('#verificationCodeSection').show();
        })
        .fail(function(xhr) {
            alert('Failed to send verification code');
        });
    });

    $('#verifyCodeBtn').click(function() {
        const phoneNumber = $('#phone').val();
        const code = $('#verification_code').val();
        if (!phoneNumber || !code) return;

        $.post('{{ route("verify.check-code") }}', {
            phone_number: phoneNumber,
            verification_code: code
        })
        .done(function(response) {
            alert(response.message);
            $('#sendCodeBtn_txt').html("");
            $('#sendCodeBtn_txt').hide();
            $('#phone_verified_bit').val(1);
            //$('#verificationCodeSection').hide();
            //location.reload(); // Reload to show updated verified numbers list
        })
        .fail(function(xhr) {
            alert(xhr.responseJSON?.message || 'Verification failed');
            $('#phone_verified_bit').val(0);
        });
    });

    /*------------------------------------------
    --------------------------------------------
    Stripe Payment Code
    --------------------------------------------
    --------------------------------------------*/
    var $form = $(".require-validation");
    $('form.require-validation').bind('submit', function(e) {
        var $form = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid = true;
        $errorMessage.addClass('hide');

        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
        });

        if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }
    });

    /*------------------------------------------
    --------------------------------------------
    Stripe Response Handler
    --------------------------------------------
    --------------------------------------------*/
    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error').removeClass('hide').find('.alert').text(response.error.message);
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
            //$form.find('input[type=text]').empty();
            var card_name = $form.find('.card-name').val();
            $form.append("<input type='hidden' name='cardName' value='" + card_name + "'/>");
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            //$form.get(0).submit();
            stripeFormSubmit();
        }
    }

    function stripeFormSubmit(){
        $('#loading_popup').show();
        $.ajax({
            type:'POST',
            data: $('#payment-form').serialize(),
            url:'{{URL::to('/book-an-appointment/storepaid')}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data){
                $('#loading_popup').hide();
                var obj = JSON.parse(data);
                if(obj.success){
                    $('#exampleModal').modal('hide');
                    $('html, body').animate({scrollTop: $('#confirm_div').offset().top -100 }, 'slow');
                    $('#confirm_div').html('<div class="tab_header"><h4></h4></div><div class="tab_body"><h4 style="text-align: center;padding: 20px;">'+obj.message+'</h4></div>');
                    setTimeout(function(){ window.location.reload(); }, 5000);
                }else{
                    alert('Please try again. There is a issue in our system');
                }
            }
        });
    }
});
</script>

<script>
function goto(str) {
    const element = document.getElementById(str);
    element.scrollIntoView();
}

jQuery(document).ready(function($){
    $( "#myTab" ).tab( { disabled: [1, 2,3,4,5] } );
	var duration = 30;
	var starttime = '';
	var endtime = '';
	var daysOfWeek = '';
    var disabledtimeslotes = new Array();
    $(document).delegate('.infoFormFields', 'change', function(){
        $('#timeslot_col_date').val("");
        $('#timeslot_col_time').val("");
        $('.confirm_row').hide();
    });

    $(document).delegate('.enquiry_item', 'change', function(){
        var id = $(this).val();//alert(id);
        if(id != ""){
            var v = 'services';
            if(id == 8){  //If nature of service == INDIA/UK/CANADA/EUROPE TO AUSTRALIA
                $('#serviceval_2').hide();
            } else {
                $('#serviceval_2').show();
            }
            $('.services_row').show();
            $('#myTab .nav-item #nature_of_enquiry-tab').addClass('disabled');
            $('#myTab .nav-item #services-tab').removeClass('disabled');
            $('#myTab a[href="#'+v+'"]').trigger('click');

            $('.services_item').prop('checked', false);
            $('.appointment_row').hide();
            $('.info_row').hide();
            $('.confirm_row').hide();

            $('.timeslots').html('');
            $('.showselecteddate').html('');

            $('#timeslot_col_date').val("");
            $('#timeslot_col_time').val(""); //Do blank Timeslot selected date and time
        } else {
            var v = 'nature_of_enquiry';
            $('.services_row').hide();
            $('.appointment_row').hide();
            $('.info_row').hide();
            $('.confirm_row').hide();

            $('#myTab .nav-item #services-tab').addClass('disabled');
            $('#myTab .nav-item #nature_of_enquiry-tab').removeClass('disabled');
            $('#myTab a[href="#'+v+'"]').trigger('click');
        }
        $('input[name="noe_id"]').val(id);
	});

    $(document).on('change', '.inperson_address', function() {
        var id = $("input[name='inperson_address']:checked").attr('data-val'); //alert(id);
        if(id != ""){
            var v = 'info';
            $('.info_row').show();
            $('.appointment_details_cls').show();

            $('#myTab .nav-item #appointment_details-tab').addClass('disabled');
            $('#myTab .nav-item #info-tab').removeClass('disabled');
            $('#myTab a[href="#'+v+'"]').trigger('click');
        } else {
            var v = 'appointment_details';
            $('.info_row').hide();
            $('.appointment_details_cls').hide();
            $('.confirm_row').hide();

            $('#myTab .nav-item #info-tab').addClass('disabled');
            $('#myTab .nav-item #appointment_details-tab').removeClass('disabled');
            $('#myTab a[href="#'+v+'"]').trigger('click');
        }
        $("input[name='inperson_address']:checked").val(id);
        $('.timeslots').html('');
        if(id != "")
        {
            var enquiry_item  = $('.enquiry_item').val(); //alert(enquiry_item);
            var service_id = $("input[name='radioGroup']:checked").val(); //alert(service_id);
            var inperson_address = $("input[name='inperson_address']:checked").attr('data-val'); //alert(inperson_address);
            $.ajax({
                url:'{{URL::to('/getdatetime')}}',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:'POST',
                data:{ id:service_id, enquiry_item:enquiry_item, inperson_address:inperson_address },
                datatype:'json',
                success:function(res){
                    var obj = JSON.parse(res);
                    if(obj.success){
                        duration = obj.duration;
                        daysOfWeek =  obj.weeks;
                        starttime =  obj.start_time;
                        endtime =  obj.end_time;
                        disabledtimeslotes = obj.disabledtimeslotes;
                        var datesForDisable = obj.disabledatesarray;

                        $('#datetimepicker').datepicker({
                            inline: true,
                            startDate: new Date(),
                            datesDisabled: datesForDisable,
                            daysOfWeekDisabled: daysOfWeek,
                            beforeShowDay: function(date) {
                                var day = date.getDay();  //console.log('day='+day);
                                var hours = date.getHours();  //console.log('hours='+hours);
                                var dateString = moment(date).format('YYYY-MM-DD'); //console.log('dateString='+dateString);
                                // Disable specific time slots for specific dates
                                if (dateString === '2024-05-23' && (hours == 10 ) ) {
                                    return { enabled: false, tooltip: 'Time slots are disabled for this date' };
                                }
                                // Enable all other time slots
                                return { enabled: true };
                            },
                            format: 'dd/mm/yyyy'
                        }).on('changeDate', function(e) {
                            var date = e.format();
                            var checked_date=e.date.toLocaleDateString('en-US');
                            $('.showselecteddate').html(date);
                            $('input[name="date"]').val(date);
                            $('#timeslot_col_date').val(date);

                            $('.timeslots').html('');
                            var start_time = parseTime(starttime),
                            end_time = parseTime(endtime),
                            interval = parseInt(duration);
                            var service_id = $("input[name='radioGroup']:checked").val(); //alert(service_id);
                            var inperson_address = $("input[name='inperson_address']:checked").attr('data-val'); //alert(inperson_address);
                            var enquiry_item  = $('.enquiry_item').val(); //alert(enquiry_item);
                            $.ajax({
                                url:'{{URL::to('/getdisableddatetime')}}',
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                type:'POST',
                                data:{service_id:service_id,sel_date:date, enquiry_item:enquiry_item,inperson_address:inperson_address},
                                datatype:'json',
                                success:function(res){
                                    $('.timeslots').html('');
                                    var obj = JSON.parse(res);
                                    if(obj.success){
                                        var objdisable = obj.disabledtimeslotes;
                                        var start_timer = start_time;
                                        for(var i = start_time; i<end_time; i = i+interval){
                                            var timeString = start_timer + interval;
                                            // Prepend any date. Use your birthday.
                                            const timeString12hr = new Date('1970-01-01T' + convertHours(start_timer) + 'Z')
                                            .toLocaleTimeString('en-US',
                                                {timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'}
                                            );
                                            const timetoString12hr = new Date('1970-01-01T' + convertHours(timeString) + 'Z')
                                            .toLocaleTimeString('en-US',
                                                {timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'}
                                            );

                                            var today_date = new Date();
                                            //const options = { timeZone: 'Australia/Sydney'};
                                            today_date = today_date.toLocaleDateString('en-US');

                                            // current time
                                            var now = new Date();
                                            var nowTime = new Date('1/1/1900 ' + now.toLocaleTimeString(navigator.language, {
                                                hour: '2-digit',
                                                minute: '2-digit',
                                                hour12: true
                                            }));

                                            var current_time=nowTime.toLocaleTimeString('en-US');
                                            if(objdisable.length > 0){
                                                //if(jQuery.inArray(timeString12hr, objdisable) != -1  || jQuery.inArray(timetoString12hr, objdisable) != -1) { //console.log('ifff');
                                                if(jQuery.inArray(timeString12hr, objdisable) != -1  ) {

                                                } else if ((checked_date == today_date) && (current_time > timeString12hr || current_time > timetoString12hr)){ //console.log('elseee-ifff');
                                                } else{
                                                    $('.timeslots').append('<div data-fromtime="'+timeString12hr+'" data-totime="'+timetoString12hr+'" style="cursor: pointer;" class="timeslot_col"><span>'+timeString12hr+'</span></div>');
                                                }
                                            } else{
                                                if((checked_date == today_date) && (current_time > timeString12hr || current_time > timetoString12hr)){
                                                } else {
                                                    $('.timeslots').append('<div data-fromtime="'+timeString12hr+'" data-totime="'+timetoString12hr+'" style="cursor: pointer;" class="timeslot_col"><span>'+timeString12hr+'</span></div>');
                                                }
                                            }
                                            start_timer = timeString;
                                        }
                                    }else{

                                    }
                                }
                            });
                        });

                        if(id != ""){
                            var v = 'appointment_details';
                            $('#myTab .nav-item #services-tab').addClass('disabled');
                            $('#myTab .nav-item #appointment_details-tab').removeClass('disabled');
                            $('#myTab a[href="#'+v+'"]').trigger('click');
                        } else {
                            var v = 'services';
                            $('#myTab .nav-item #services-tab').removeClass('disabled');
                            $('#myTab .nav-item #appointment_details-tab').addClass('disabled');
                            $('#myTab a[href="#'+v+'"]').trigger('click');
                        }
                        $('input[name="service_id"]').val($("input[name='radioGroup']:checked").val());
                    } else {
                        $('input[name="service_id"]').val('');
                        var v = 'services';
                        alert('There is a problem in our system. please try again');
                        $('#myTab .nav-item #services-tab').removeClass('disabled');
                        $('#myTab .nav-item #appointment_details-tab').addClass('disabled');
                    }
                }
            })
        }
	});


    $(document).delegate('.appointment_item', 'change', function(){
        var id = $(this).val();
        if(id != ""){
            $('input[name="appointment_details"]').val(id);
        } else {
            $('input[name="appointment_details"]').val("");
        }
    });

	$(document).delegate('.services_item', 'change', function(){
        $('.info_row').hide();
        $('.confirm_row').hide();
        $("input[name='inperson_address']").prop("checked", false);
        $('.appointment_item').val("");
        $('.appointment_details_cls').hide();

        var id = $(this).val(); //console.log('id='+id);
        if ($("input[name='radioGroup'][value='+id+']").prop("checked"))
        $('#service_id').val(id);

        if( $("input[name='radioGroup']:checked").val() == 1 ){ //paid
            $('.submitappointment_paid').show();
            $('.submitappointment').hide();
        } else { //free
            $('.submitappointment').show();
            $('.submitappointment_paid').hide();
        }

        if(id != ""){
            var v = 'appointment_details';
            if( id == 1 ){ //paid service
                // Show the "Zoom / Google Meeting" option
                $('select[name="appointment_details"] option[value="zoom_google_meeting"]').show();
            } else {
                // Hide the "Zoom / Google Meeting" option
                $('select[name="appointment_details"] option[value="zoom_google_meeting"]').hide();
            }
            $('.appointment_row').show();
        } else {
            var v = 'services';
            $('.appointment_row').hide();
        }
        $('.timeslots').html('');
		$('.showselecteddate').html('');

        $('#timeslot_col_date').val("");
        $('#timeslot_col_time').val(""); //Do blank Timeslot selected date and time

        /*var enquiry_item  = $('.enquiry_item').val(); //alert(enquiry_item);
        var inperson_address  = $('.inperson_address').val(); //alert(inperson_address);
		$.ajax({
			url:'{{URL::to('/getdatetime')}}',
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type:'POST',
			data:{ id:id, enquiry_item:enquiry_item, inperson_address:inperson_address },
			datatype:'json',
			success:function(res){
				var obj = JSON.parse(res);
				if(obj.success){
                    duration = obj.duration;
					daysOfWeek =  obj.weeks;
					starttime =  obj.start_time;
					endtime =  obj.end_time;
					disabledtimeslotes = obj.disabledtimeslotes;
                    var datesForDisable = obj.disabledatesarray;

					$('#datetimepicker').datepicker({
						inline: true,
						startDate: new Date(),
						datesDisabled: datesForDisable,
						daysOfWeekDisabled: daysOfWeek,
                        beforeShowDay: function(date) {
                            var day = date.getDay();  //console.log('day='+day);
                            var hours = date.getHours();  //console.log('hours='+hours);
                            var dateString = moment(date).format('YYYY-MM-DD'); //console.log('dateString='+dateString);
                            // Disable specific time slots for specific dates
                            if (dateString === '2024-05-23' && (hours == 10 ) ) {
                                return { enabled: false, tooltip: 'Time slots are disabled for this date' };
                            }
                            // Enable all other time slots
                            return { enabled: true };
                        },
						format: 'dd/mm/yyyy'
					}).on('changeDate', function(e) {
                        var date = e.format();
                        var checked_date=e.date.toLocaleDateString('en-US');
                        $('.showselecteddate').html(date);
                        $('input[name="date"]').val(date);
                        $('#timeslot_col_date').val(date);

                        $('.timeslots').html('');
                        var start_time = parseTime(starttime),
				        end_time = parseTime(endtime),
			            interval = parseInt(duration);
                        var service_id = $('input[name="service_id"]').val();

                        var enquiry_item  = $('.enquiry_item').val(); //alert(enquiry_item);
                        $.ajax({
                            url:'{{URL::to('/getdisableddatetime')}}',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            type:'POST',
                            data:{service_id:service_id,sel_date:date, enquiry_item:enquiry_item},
                            datatype:'json',
                            success:function(res){
                                $('.timeslots').html('');
                                var obj = JSON.parse(res);
                                 if(obj.success){
                                    var objdisable = obj.disabledtimeslotes;
                                    var start_timer = start_time;
                                    for(var i = start_time; i<end_time; i = i+interval){
                                        var timeString = start_timer + interval;
                                        // Prepend any date. Use your birthday.
                                        const timeString12hr = new Date('1970-01-01T' + convertHours(start_timer) + 'Z')
                                        .toLocaleTimeString('en-US',
                                            {timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'}
                                        );
                                        const timetoString12hr = new Date('1970-01-01T' + convertHours(timeString) + 'Z')
                                        .toLocaleTimeString('en-US',
                                            {timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'}
                                        );

                                        var today_date = new Date();
                                        //const options = { timeZone: 'Australia/Sydney'};
                                        today_date = today_date.toLocaleDateString('en-US');

                                        // current time
                                        var now = new Date();
                                        var nowTime = new Date('1/1/1900 ' + now.toLocaleTimeString(navigator.language, {
                                            hour: '2-digit',
                                            minute: '2-digit',
                                            hour12: true
									    }));

                                        var current_time=nowTime.toLocaleTimeString('en-US');
                                        if(objdisable.length > 0){
                                            //if(jQuery.inArray(timeString12hr, objdisable) != -1  || jQuery.inArray(timetoString12hr, objdisable) != -1) { //console.log('ifff');
                                            if(jQuery.inArray(timeString12hr, objdisable) != -1  ) {

                                            } else if ((checked_date == today_date) && (current_time > timeString12hr || current_time > timetoString12hr)){ //console.log('elseee-ifff');
                                            } else{
                                                $('.timeslots').append('<div data-fromtime="'+timeString12hr+'" data-totime="'+timetoString12hr+'" style="cursor: pointer;" class="timeslot_col"><span>'+timeString12hr+'</span></div>');
                                            }
                                        } else{
                                            if((checked_date == today_date) && (current_time > timeString12hr || current_time > timetoString12hr)){
                                            } else {
                                                $('.timeslots').append('<div data-fromtime="'+timeString12hr+'" data-totime="'+timetoString12hr+'" style="cursor: pointer;" class="timeslot_col"><span>'+timeString12hr+'</span></div>');
                                            }
                                            // $('.timeslots').append('<div data-fromtime="'+timeString12hr+'" data-totime="'+timetoString12hr+'" style="cursor: pointer;" class="timeslot_col"><span>'+timeString12hr+'</span><span>'+timetoString12hr+'</span></div>');
                                        }
						                start_timer = timeString;
					                }
                                }else{

                                }
                            }
                        });
                        //	var times_ara = calculate_time_slot( start_time, end_time, interval );
                    });

                    if(id != ""){
                        var v = 'appointment_details';
                        $('#myTab .nav-item #services-tab').addClass('disabled');
                        $('#myTab .nav-item #appointment_details-tab').removeClass('disabled');
                        $('#myTab a[href="#'+v+'"]').trigger('click');
                    } else {
                        var v = 'services';
                        $('#myTab .nav-item #services-tab').removeClass('disabled');
                        $('#myTab .nav-item #appointment_details-tab').addClass('disabled');
                        $('#myTab a[href="#'+v+'"]').trigger('click');
                    }
                    $('input[name="service_id"]').val(id);
				} else {
                    $('input[name="service_id"]').val('');
                    var v = 'services';
                    alert('There is a problem in our system. please try again');
                    $('#myTab .nav-item #services-tab').removeClass('disabled');
                    $('#myTab .nav-item #appointment_details-tab').addClass('disabled');
				}
			}
		})*/
	});

    $(document).delegate('.nextbtn', 'click', function(){
		var v = $(this).attr('data-steps');
		$(".custom-error").remove();
		var flag = 1;
        if(v == 'confirm'){ //datetime
            $('#sendCodeBtn_txt').html("");
            $('#sendCodeBtn_txt').hide();

			var fullname = $('.fullname').val();
			var email = $('.email').val();
			//var title = $('.title').val();
			var phone = $('.phone').val();
			var description = $('.description').val();
            var timeslot_col_date = $('#timeslot_col_date').val();
            var timeslot_col_time = $('#timeslot_col_time').val();

            //var phoneRegex = /^[0-9]{10,}$/;
            var phoneRegex = /^\+?[0-9]{1,4}[-.\s]?[0-9]{10,}$/;
            // Regular expression to allow only letters and spaces (no special characters)
            var nameRegex = /^[a-zA-Z\s]+$/;
            var appointment_item = $('.appointment_item').val();
            var preferred_language = $('.preferred_language').val();
            if( !$.trim(appointment_item) ){
                flag = 0;
                $('.appointment_item').after('<span class="custom-error" role="alert">Appointment detail is required</span>');
            }
          
            if( !$.trim(preferred_language) ){
                flag = 0;
                $('.preferred_language').after('<span class="custom-error" role="alert">Preferred language is required</span>');
            }
          
            if( !$.trim(fullname) ){
				flag = 0;
				$('.fullname').after('<span class="custom-error" role="alert">Fullname is required</span>');
			}
          
            
            else if (!nameRegex.test(fullname)) {
                flag = 0;
                // Show error message if fullname contains special characters
                $('.fullname').after('<span class="custom-error" role="alert">Full name must not contain special characters</span>');
            }

			if( !ValidateEmail(email) ){
				flag = 0;
				if(!$.trim(email)){
					$('.email').after('<span class="custom-error" role="alert">Email is required.</span>');
				}else{
					$('.email').after('<span class="custom-error" role="alert">You have entered an invalid email address!</span>');
				}
			}

            if( !$.trim(phone) ){
				flag = 0;
				$('#sendCodeBtn').after('<span class="custom-error" role="alert">Phone number is required</span>');
			} else if (!phoneRegex.test(phone)) {
                flag = 0;
                // Show error message if phone number is not valid (less than 10 digits or contains non-digits)
                //$('.phone').after('<span class="custom-error" role="alert">Phone number must be at least 10 digits and only contain numbers</span>');
                $('#sendCodeBtn').after('<span class="custom-error" role="alert">Phone must contain extension with phone.</span>');
            } else if( $('#phone_verified_bit').val() != "1" ){
				flag = 0;
				$('#sendCodeBtn').after('<span class="custom-error" role="alert">Phone number is not verified</span>');
			}

            if( !$.trim(description) ){
				flag = 0;
				$('.description').after('<span class="custom-error" role="alert">Description is required</span>');
			}
            if( !$.trim(timeslot_col_date) || !$.trim(timeslot_col_time)  ){
				flag = 0;
				$('.timeslot_col_date_time').after('<span class="custom-error" role="alert">Date and Time is required</span>');
			}
        }/*else if(v == 'confirm'){

		}*/
        //alert('flag=='+flag+'---v=='+v);
		if(flag == 1 && v == 'confirm'){
            $('.confirm_row').show();
            $('#myTab .nav-item .nav-link').addClass('disabled');
		    $('#myTab .nav-item #'+v+'-tab').removeClass('disabled');
			$('#myTab a[href="#'+v+'"]').trigger('click');

            $('.full_name').text($('.fullname').val());
            $('.email').text($('.email').val());
            //$('.title').text($('.title').val());
            $('.phone').text($('.phone').val());
            $('.description').text($('.description').val());
            $('.date').text($('input[name="date"]').val());
            $('.time').text($('input[name="time"]').val());

            if(  $("input[name='radioGroup']:checked").val() == 1 ){ //paid
                $('.submitappointment_paid').show();
                $('.submitappointment').hide();
            } else { //free
                $('.submitappointment').show();
                $('.submitappointment_paid').hide();
            }
		} else {
            $('.confirm_row').hide();
        }

		function ValidateEmail(inputText) {
			var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
			if(inputText.match(mailformat)) {
			    return true;
			} else {
			    return false;
			}
		}
    });

    $(document).delegate('.timeslot_col', 'click', function(){
		$('.timeslot_col').removeClass('active');
		$(this).addClass('active');
        var service_id_val = $("input[name='radioGroup']:checked").val(); //alert(service_id_val);
		var fromtime = $(this).attr('data-fromtime');
        if(service_id_val == 2){ //15 min service
            var fromtime11 = parseTimeLatest(fromtime);
            var interval11 = 15;
            var timeString11 = fromtime11 + interval11;
            var totime = new Date('1970-01-01T' + convertHours(timeString11) + 'Z').toLocaleTimeString('en-US', {timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'} );
        } else {
            var totime = $(this).attr('data-totime');
        }
		//alert('totime='+totime);
		$('input[name="time"]').val(fromtime+'-'+totime);
        $('#timeslot_col_time').val(fromtime+'-'+totime);
    });

    function parseTime(s) {
        var c = s.split(':');
        return parseInt(c[0]) * 60 + parseInt(c[1]);
    }

    function parseTimeLatest(s) {
        var c = s.split(':');
        var c11 = c[1].split(' ');
        if(c11[1] == 'PM'){
            if(parseInt(c[0]) != 12 ){
                return ( parseInt(c[0])+12 ) * 60 + parseInt(c[1]);
            } else {
                return parseInt(c[0]) * 60 + parseInt(c[1]);
            }
        } else {
            return parseInt(c[0]) * 60 + parseInt(c[1]);
        }
    }

    function convertHours(mins){
        var hour = Math.floor(mins/60);
        var mins = mins%60;
        var converted = pad(hour, 2)+':'+pad(mins, 2);
        return converted;
    }

    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }

    function calculate_time_slot(start_time, end_time, interval = "15"){
        var i, formatted_time;
        var time_slots = new Array();
        for(var i=start_time; i<=end_time; i = i+interval){
            formatted_time = convertHours(i);
            const timeString = formatted_time;
            time_slots.push(timeString);
        }
        return time_slots;
    }

    $(document).delegate('.submitappointment','click',function (e) {
		var flag = 1;
		$('.errors').html('');
		var fullname = $('.fullname').val();
		var email = $('.email').val();
		//var title = $('.title').val();
		var phone = $('.phone').val();
		var date = $('input[name="date"]').val();
		var time = $('input[name="time"]').val();
		var service_id = $('input[name="service_id"]').val();
        //var service_id = $("input[name='radioGroup']:checked").val();
        var description = $('.description').val();
		if( !$.trim(date) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Date is required</span></li>');
		}if( !$.trim(time) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Time is required</span></li>');
		}if( !$.trim(service_id) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Service is required</span></li>');
		}
		if( !$.trim(fullname) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Name is required</span></li>');
		}
		if( !$.trim(email) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Email is required</span></li>');
		}
		if( !$.trim(phone) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Phone is required</span></li>');
		}
		if( !$.trim(description) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Description is required</span></li>');
		}
		if(flag == 1){
			$('#loading').show();
			$.ajax({
			    type:'POST',
			    data: $('#appintment_form').serialize(),
			    url:'{{URL::to('/book-an-appointment/store')}}',
			    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			    success:function(data){
				    $('#loading').hide();
				    var obj = JSON.parse(data);
				    if(obj.success){
                        $('html, body').animate({scrollTop: $('#confirm_div').offset().top -100 }, 'slow');
					    $('#confirm_div').html('<div class="tab_header"><h4></h4></div><div class="tab_body"><h4 style="text-align: center;padding: 20px;">'+obj.message+'</h4></div>');
					    setTimeout(function(){ window.location.reload(); }, 5000);
				    }else{
					    alert('Please try again. There is a issue in our system');
				    }
			    }
		    });
		}
	});

    function closePopup() {
        $('#exampleModal').hide();
    }

    $(document).delegate('.submitappointment_paid','click',function (e) {
		var flag = 1;
		$('.errors').html('');
		var fullname = $('.fullname').val();
		var email = $('.email').val();
		//var title = $('.title').val();
		var phone = $('.phone').val();
		var date = $('input[name="date"]').val();
		var time = $('input[name="time"]').val();
		//var service_id = $('input[name="service_id"]').val();
        var service_id = $("input[name='radioGroup']:checked").val();
        var description = $('.description').val();
		if( !$.trim(date) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Date is required</span></li>');
		}if( !$.trim(time) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Time is required</span></li>');
		}if( !$.trim(service_id) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Service is required</span></li>');
		}
		if( !$.trim(fullname) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Name is required</span></li>');
		}
		if( !$.trim(email) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Email is required</span></li>');
		}
		if( !$.trim(phone) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Phone is required</span></li>');
		}
		if( !$.trim(description) ){
			flag = 0;
			$('.errors').append('<li><span class="custom-error" role="alert">Description is required</span></li>');
		}

        if(flag == 0){
            closePopup();
        }

		if(flag == 1){
            $('#noe_id_paid').val( $('input[name="noe_id"]').val() );
            $('#radioGroup_paid').val( $('input[name="radioGroup"]').val() );
            $('#service_id_paid').val( $("input[name='radioGroup']:checked").val() );
            $('#inperson_address_paid').val( $("input[name='inperson_address']:checked").attr('data-val') );
            $('#appointment_details_paid').val( $('input[name="appointment_details"]').val() );

            $('#date_paid').val(date);
            $('#time_paid').val(time);
            $('#fullname_paid').val(fullname);
            $('#email_paid').val(email);
            $('#phone_paid').val(phone);
            //$('#title_paid').val(title);
            $('#description_paid').val(description);
        }
	});
});
</script>

@endsection
