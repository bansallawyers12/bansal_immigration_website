@extends('layouts.frontend')
@section('seoinfo')

	<title><?php echo @\App\HomeContent::where('meta_key','meta_title')->first()->meta_value;?></title>


	<meta name="description" content="<?php echo @\App\HomeContent::where('meta_key','meta_description')->first()->meta_value; ?>">
	<link rel="canonical" href="<?php echo URL::to('/'); ?>">
	<meta property="og:locale" content="en_US">
	<meta property="og:type" content="website">
	<meta property="og:title" content="<?php echo @\App\HomeContent::where('meta_key','meta_title')->first()->meta_value; ?>">
	<meta property="og:description" content="<?php echo @\App\HomeContent::where('meta_key','meta_description')->first()->meta_value; ?>">
	<meta property="og:url" content="<?php echo URL::to('/'); ?>">
	<meta property="og:site_name" content="<?php echo @\App\ThemeOption::where('meta_key','site_name')->first()->meta_value; ?>">
	<meta property="article:modified_time" content="2023-04-04T21:06:24+00:00">
	<meta property="og:image" content="{{asset('public/img/bansal-immigration-icon.jpg')}}">
	<meta property="og:image:width" content="200">
	<meta property="og:image:height" content="200">
	<meta property="og:image:type" content="image/jpeg">
    <meta name="google-site-verification" content="2bh6NrWvA9u4iSWE5YVL4iuZ2gPHHbnWW6gn-Z1UHbk">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo @\App\HomeContent::where('meta_key','meta_title')->first()->meta_value; ?>">
	<meta name="twitter:description" content="<?php echo @\App\HomeContent::where('meta_key','meta_description')->first()->meta_value; ?>">
	<meta name="twitter:image" content="{{asset('public/img/bansal-immigration-icon.jpg')}}">
	<meta name="twitter:site" content="@Bansalimmi">
	<meta name="twitter:label1" content="Est. reading time">
	<meta name="twitter:data1" content="6 minutes">
    
@endsection
@section('content')

<style>
  #trustpilot-gtm-floating-wrapper { display:none;}
</style>

<div id="popup-loging">
    <div class="pop-custom-loging">
        <span class="close" onclick="closePopup()">&times;</span>
        <h2 style="Merienda, cursive;font-optical-sizing: auto;font-style: normal;">Contact Us</h2>
        <form>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="tel">Mobile No:</label>
            <input type="tel" id="tel" name="tel" required>
            
            <label for="query">Your Query:</label>
            <input type="text" id="query" name="query" required>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>


<?php $sliderstat = @\App\HomeContent::where('meta_key','sliderstatus')->first()->meta_value;
// dd($sliderstat);
if(@$sliderstat == 1){
 ?>
<section class="hero-area">
	<div class="hero-slides1">
		@foreach (@$sliderlists as $list)
		<div class="single-hero-slide1" style="background-image:url('{{ asset('public/img/slider/' . @$list->image) }}');background-size: 100% 100%;">

			<div class="container h-100" style="z-index:2">
				<div class="row h-100 align-items-center"> 
					<div class="col-12 col-md-12">
						<div class="hero-slides-content1 text-center">
							<h1 style="Merienda, cursive;font-optical-sizing: auto;font-style: normal; font-weight:500;">Bansal Immigration Consultants</h1> 
							<h2 data-animation="fadeInUp" data-delay="400ms" style="Merienda, cursive;font-optical-sizing: auto;font-style: normal; font-weight:500;">{{@$list->title}}</h2>
							<h4 data-animation="fadeInUp" data-delay="400ms" style="Merienda, cursive;font-optical-sizing: auto;font-style: normal; font-weight:500;">{{@$list->subtitle}}</h4>
							<!--<div class="slider_search">
								<form class="search_form" action="{{--URL::to('/search_result')--}}" method="get">
									<div class="search_field">
										<input type="text" class="form-control" placeholder="Search" name="search" />
										<input type="submit" class="search_btn" value="Search"/>
									</div>
								</form>
							</div>-->
						</div>
					</div>
				</div>
			</div> 
		</div>
	  @endforeach	
	</div>
</section>



<!-- ##### Hero Area End ##### -->
<?php } ?>
<!-- ##### About Area Start ##### -->
<section class="cryptos-about-area" style="background-color:#F7F7F7;display:none;">
	<div class="container">
		<div class="row align-items-center" style="padding-top:60px;">
			<div class="col-12 col-md-6">
				<div class="about-thumbnail mb-100">
					<img src="{{asset('public/img/Frontend/about.jpg') }}" style="border-radius:20px" alt="About Bansal Immigration - Trusted Australian Immigration Experts" />
				</div>
			</div>
			<div class="col-12 col-md-6">
				<div class="about-content mb-100"> 
					<div class="section-heading">
						<h3 style="Merienda, cursive;font-optical-sizing: auto;font-style: normal;font-weight:500"><span><?php echo @\App\HomeContent::where('meta_key','about_title')->first()->meta_value; ?></span></h3>
						<h6 style="line-height:26px;Merienda, cursive;font-optical-sizing: auto;font-style: normal;"><?php echo @\App\HomeContent::where('meta_key','about_description')->first()->meta_value; ?></h6>
						<a href="<?php echo @\App\HomeContent::where('meta_key','about_link')->first()->meta_value; ?>" class="btn cryptos-btn mt-30">Read More</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- ##### About Area End ##### -->
<?php $whychoosestat = @\App\HomeContent::where('meta_key','whychoosestatus')->first()->meta_value;
if(@$whychoosestat == 1){
 ?>
 <!-- ##### Course Area Start ##### -->
<div class="cryptos-feature-area why_immigration_sec">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-heading text-center mx-auto">
					<h3 style="Merienda, cursive;font-optical-sizing: auto;font-style: normal;font-weight:500"><span>Why BANSAL Immigration?</span></h3>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- Single Course Area -->
			@foreach (@$whychoosequerylists as $list)		
			<div class="col-12 col-md-6 col-xl-4 why_col3">
				<div class="why_box">	
					<div class="why_icon">
						<i class="fa fa-{{ @$list->icon }}"></i>
					</div>
					<div class="why_content">					
						<h4 style="Merienda, cursive;font-optical-sizing: auto;font-style: normal;">{{@$list->title}}</h4>
						<p style="Merienda, cursive;font-optical-sizing: auto;font-style: normal;">{{@$list->description}}</p>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</div>
<!-- ##### Course Area End ##### -->
<?php } ?>
<?php $servicestat = @\App\HomeContent::where('meta_key','servicestatus')->first()->meta_value;
if(@$servicestat == 1){
 ?>
<!--================Project Area =================-->
<section class="services_area" style="padding-top:40px; padding-bottom:30px; background-image:url({{asset('public/img/Frontend/projectback.jpg')}}); background-size: 100% 100%;">
	<div class="container">
		<h2 class="text-center"><span style="color:#fff;Merienda, cursive;font-optical-sizing: auto;font-style: normal;font-weight:500">SERVICES WE OFFER</span></h2>
		<p class="text-center" style="color:#fff">We provide the following services at  <a style="color:#ffaf02" href="<?php echo URL::to('/'); ?>">Bansal Immigration Consultants</a></p>
		<div class="inner_service"> 
			<div class="service_row"> 
				@foreach (@$servicelists as $list)				
				<div class="service_col" style="border: 1px solid #FFFFFF;animation: fadeIn 3s ease-in;">
					<div class="service_item">  
						<div class="service_icon"><i class="fa fa-{{ @$list->serv_icon }}"></i></div>
						<h4 style="animation: fadeIn 5s ease-in;">{{@$list->title}}</h4>
						<p style="animation: fadeIn 5s ease-in;">{{@$list->short_description}}</p> 
						<a class="readmore" href="<?php echo URL::to('/'); ?>/ourservices/{{@$list->slug}}">Read More</a>
					</div>
				</div>
				<!--<div class="service_col service_empty_col"></div>-->
				@endforeach
			</div> 
			<div class="text-center">
				<a href="<?php echo URL::to('/'); ?>/ourservices" class="btn cryptos-btn mt-30">View All</a>
			</div>
		</div>
	</div>
</section>

<!--================End Project Area =================-->
<?php } ?>

<!--==========================
      Testimonials Section
    ============================-->
<section id="testimonials" class="wow fadeInUp" style="background-image: url({{ asset('public/img/testimonial_img/test.jpg') }}); background-size: 100% 100%;">
	<div class="container">
		<div class="row row-cols-3">
			<?php $testmioalstat = @\App\HomeContent::where('meta_key','testimonialstatus')->first()->meta_value;
			if(@$testmioalstat == 1){
              $testimoniallists = \App\Testimonial::orderBy('created_at', 'desc')->get();
			 ?>
			<div class="col-lg-12 col-md-12">
				<div class="section-header">
					<h3 style="text-align:center"><span>TESTIMONIALS</span></h3>
				</div>
				<div class="owl-carousel testimonials-carousel">
					@foreach (@$testimoniallists as $list)
					<div class="testimonial-item" style="background-color:#FFFFFF">
						<img src="{{asset('public/img/Frontend/quote-sign-left.png')}}" class="quote-sign-left" alt="Opening quote mark">
						<p>{{@$list->description}}</p>
						<img src="{{asset('public/img/Frontend/quote-sign-right.png')}}" class="quote-sign-right" alt="Closing quote mark">
						<img src="{{asset('public/img/testimonial_img')}}/{{@$list->image}}" class="testimonial-img" alt="{{@$list->name}}">
						<p><b>{{@$list->name}}</b></p>
					</div>
					@endforeach
				</div>
				<div class="text-center">
					<a href="<?php echo URL::to('/'); ?>/testimonials" class="btn cryptos-btn view_all_btn">View All</a>
				</div>
			</div>
			<?php } ?>

    </div>
  </div>
</section>


   



<!-- ##### Currency Area Start ##### -->
<section class="currency-calculator-area section-padding-50 bg-img bg-overlay" style="background-image: url({{ asset('public/img/Frontend/bg-2.jpg') }});">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading text-center white mx-auto">
                    <h3><strong>REQUEST A CALL BACK</strong></h3>
                    <h5 class="mb-2">Get the right guidance about complex immigration situations from Registered Migration Agents</h5>
                </div>
            </div>
        </div>

        <div class="form_area cus_form_area"> 
            <form class="contact_form" action="{{ url('/contact') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fullname">Name</label>
                            <input type="text" class="form-control" placeholder="Name" name="fullname" required/>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" placeholder="Email" name="email" required/>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" placeholder="Phone" name="phone" required/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subject">Your Query</label>
                            <input type="text" class="form-control" placeholder="Write Query" name="subject" required/>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" placeholder="Message" name="message" required></textarea>
                        </div>
                        <div class="capcha_code" style="margin-bottom:20px;">
                            <label>Verify Code:</label>
                            <div class="code_verify">
                                <div class="image">
                                    <?php echo $captcha; ?>
                                </div>
                            </div>
                            <div class="code_refresh">
                                @if ($errors->has('captcha'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('captcha') }}</strong>
                                    </span>
                                @endif
                                <input type="text" name="captcha" id="captcha" required="required" class="form-control cap1" placeholder="Enter Code Here" min="6" maxlength="6" autocomplete="off"/>
                                <a class="refresh" href="javascript:;"><i class="fa fa-refresh"></i></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-btn">
                            <input type="submit" class="btn" name="submit" value="Submit" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Success Modal -->
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalMessage">
                Your request has been submitted successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


 
<!-- ##### Currency Area End ##### -->

<?php $blogstat = @\App\HomeContent::where('meta_key','blogstatus')->first()->meta_value;
if(@$blogstat == 1){
 ?>
<!-- ##### Blog Area Start ##### -->
<section class="cryptos-blog-area blog_sec">
	<div class="container">
		<h3 class="text-center" style="Merienda, cursive;font-optical-sizing: auto;font-style: normal;font-weight:500"><span>RECENT BLOGS</span></h3>
		<div class="col-12">
			<div class="row"> 
			<!-- Single Course Area -->
				@foreach (@$bloglists as $list)
				<div class="col-12 col-sm-6 col-lg-4">
					<div class="single-feature-area style-2 mb-4">
						@if($list->image)
							<a href="<?php echo URL::to('/'); ?>/{{@$list->slug}}"><img src="https://bansalimmigration.com.au/public/img/blog/{{@$list->image}}" alt="{{@$list->image_alt}}" style="width:318px !important;height:198px !important;"></img></a> 
						@endif
						<h4 style="Merienda, cursive;font-optical-sizing: auto;font-style: normal;">{{@$list->title}}</h4>
						<p style="Merienda, cursive;font-optical-sizing: auto;font-style: normal;">{{@$list->short_description}}</p>
						<a href="<?php echo URL::to('/'); ?>/{{@$list->slug}}">Read More</a>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		<div class="text-center">
			<a href="<?php echo URL::to('/'); ?>/blogs" class="btn cryptos-btn view_all_btn">View All</a>
		</div>
	</div>
</section>


<!-- ##### Blog Area End ##### -->
<?php } ?>











<section class="meet_us" style="background-color: #1a365d; padding: 20px 0; color: white;">
	<div class="container">
		<div class="row">
			<div class="col-12 text-center">
				<h1 style="font-size: 3rem; font-weight: 600; margin-bottom: 20px; color: white;">Our Team</h1>
				<p style="font-size: 1.2rem; color: white;margin-bottom: -68px;">"Expert Guidance, Personalised Care – Meet the Team Behind Your Migration Success."</p>
			</div>
		</div>
		
		<div class="row">
			<!-- Arun Bansal -->
			<div class="col-12 col-md-4 mb-4">
				<div class="team-member text-center">
					<div class="member-photo mb-1">
						<img src="{{asset('img/profile_imgs')}}/Arun Sir.png" style="width: 400px; height: 500px; object-fit: cover; border-radius: 15px;" alt="Arun Bansal - Director" />
					</div>
					<h3 style="color: #ddcc14; font-weight: bold; font-size: 1.5rem; margin-bottom: 5px; margin-top: -130px;">Arun Bansal</h3>
					<p style="color: #ddcc14; font-size: 0.9rem; margin-bottom: 15px;">Director (MARN: 2418466)</p>
					<p style="color: white; font-size: 0.9rem; line-height: 1.4;">Director of Bansal Immigration Consultants, offers 10+ years of legal and migration experience with trusted, expert guidance backed by LLM and Migration Law qualifications.</p>
				</div>
			</div>
			
			<!-- Vipul Goyal -->
			<div class="col-12 col-md-4 mb-4">
				<div class="team-member text-center">
					<div class="member-photo mb-1">
						<img src="{{asset('img/profile_imgs')}}/Vipul Sir.png" style="width: 400px; height: 500px; object-fit: cover; border-radius: 15px;" alt="Vipul Goyal - Migration Agent" />
					</div>
					<h3 style="color: #ddcc14; font-weight: bold; font-size: 1.5rem; margin-bottom: 5px; margin-top: -130px;">Vipul Goyal</h3>
					<p style="color: #ddcc14; font-size: 0.9rem; margin-bottom: 15px;">MARA: 2418571</p>
					<p style="color: white; font-size: 0.9rem; line-height: 1.4;">Migration Agent at Bansal Immigration Consultants, provides expert guidance across skilled, student, and family visas with deep legal knowledge and a client-focused approach.</p>
				</div>
			</div>
			
			<!-- Maleesha Thawalampola -->
			<div class="col-12 col-md-4 mb-4">
				<div class="team-member text-center">
					<div class="member-photo mb-1">
						<img src="{{asset('img/profile_imgs')}}/Maleesha Maam.png" style="width: 400px; height: 500px; object-fit: cover; border-radius: 15px;" alt="Maleesha Thawalampola - Migration Agent" />
					</div>
					<h3 style="color: #ddcc14; font-weight: bold; font-size: 1.5rem; margin-bottom: 5px; margin-top:-130px;">Maleesha Thawalampola</h3>
					<p style="color: #ddcc14; font-size: 0.9rem; margin-bottom: 15px;">MARA: 2518893</p>
					<p style="color: white; font-size: 0.9rem; line-height: 1.4;">Migration Agent at Bansal Immigration Consultants, offers expert support in student, partner, and skilled visas, guiding clients with clarity, precision, and personalised migration solutions.</p>
				</div>
			</div>
		</div>
		<a style="margin-left: 480px;" href="<?php echo URL::to('/'); ?>/<?php echo @\App\HomeContent::where('meta_key','meet_link')->first()->meta_value; ?>" class="btn cryptos-btn contact_btn">Contact Us</a>
	</div>
</section>








@endsection 

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>




<script>
    @if ($message = Session::get('success'))
        $(document).ready(function() {
            $('#modalMessage').text("{{ $message }}"); // Set the success message
            $('#successModal').modal('show'); // Show the modal
        });
    @endif
</script>
<script>
    /*function openPopup() {
        document.getElementById("popup-loging").style.display = "flex";
    }

    function closePopup() {
        document.getElementById("popup-loging").style.display = "none";
        sessionStorage.setItem('popupClosed', 'true'); // Mark that the popup has been closed
    }

    // Check if the popup was closed in this session
    if (!sessionStorage.getItem('popupClosed')) {
        setTimeout(openPopup, 2000); // Show after 2 seconds if it hasn't been closed
    }*/
</script>



   
@endsection



