<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @yield('seoinfo')

    <!-- Title of the Page -->
    <title>@yield('title', '#1 Best Education & Migration Agent in Australia | Education Consultants in Melbourne')</title>

    <!-- Meta Description (SEO) -->
    <meta name="description" content="@yield('meta_description', 'Best education & migration consultants in Melbourne for all your education visas & migration visa services. Your trusted Collins Street migration agency.')">

    <!--<title>{{--@yield('title')--}}</title>-->
	<!-- Favicons-->
    <link rel="shortcut icon" href="{{asset('/img/logo_img')}}/<?php echo @\App\ThemeOption::where('meta_key','fav_icon')->first()->meta_value; ?>" type="image/x-icon">
    <!-- <link rel="apple-touch-icon" type="image/x-icon" href="{{asset('img/Frontend/img/apple-touch-icon-57x57-precomposed.png')}}">-->

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,900|Open+Sans:400,600,700" rel="stylesheet">
    <!-- BASE CSS -->
    <link href="{{asset('css/Frontend/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/Frontend/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/Frontend/animate.css')}}" rel="stylesheet">
    <link href="{{asset('css/Frontend/magnific-popup.css')}}" rel="stylesheet">
    <link href="{{asset('css/Frontend/font-awesome.min.css')}}" rel="stylesheet">

    <link href="{{asset('css/Frontend/ETmodules.css')}}" rel="stylesheet">
	<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet"/>-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <!--<link href="{{asset('css/Frontend/jquery-ui.css')}}" rel="stylesheet">-->
    <link href="{{asset('css/Frontend/custom-icon.css')}}" rel="stylesheet">
    <link href="{{asset('css/Frontend/classy-nav.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/Frontend/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/Frontend/custom.css')}}" rel="stylesheet">
    <link href="{{asset('css/Frontend/responsive.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/intlTelInput.css')}}">
    <!-- Custom style CSS -->
	<link rel="stylesheet" href="{{asset('css/custom.css')}}">

    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-timepicker.min.css')}}">



	<!--<script src="{{asset('js/Frontend/jquery-min.js')}}"></script>-->

    <script src="{{asset('js/jquery_min_latest.js')}}"></script>

    </head>
	<body>
		<div class="main_wrapper">
			<!--<div id="preloader">
				<i class="circle-preloader"></i>
			</div>-->
			<!--Header-->
				@include('../Elements/Frontend/header')
			<main>
			<!--Content-->
				@yield('content')
			</main>
			<!-- /main -->
			<!--Footer-->
				@include('../Elements/Frontend/footer')
		 <!-- page -->
		</div>

		<!-- COMMON SCRIPTS -->
		<script type="text/javascript">
			var site_url = "<?php echo URL::to('/'); ?>";
			var redirecturl = "<?php echo URL::to('/thanks'); ?>";
		</script>

		<script src="{{asset('js/Frontend/jquery-2.2.4.min.js')}}"></script>

        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>-->
		<script src="{{asset('js/Frontend/popper.min.js')}}"></script>
		<script src="{{asset('js/Frontend/bootstrap.min.js')}}"></script>
		<script src="{{asset('js/Frontend/plugins.js')}}"></script>
		<script src="{{asset('js/Frontend/active.js')}}"></script>
		<script src="{{asset('js/Frontend/jquery.min.js')}}"></script>
		<script src="{{asset('js/Frontend/jquery-migrate.min.js')}}"></script>
		<script src="{{asset('js/Frontend/bootstrap.bundle.min.js')}}"></script>

        <!--<script src="{{-- asset('js/niceCountryInput.js')--}}"></script> -->
        <script src="{{asset('js/app.min.js')}}"></script>
        <script src="{{asset('js/fullcalendar.min.js')}}"></script>

        <!--<script src="{{--asset('js/chart.min.js')--}}"></script>-->

        <script src="{{asset('js/datatables.min.js')}}"></script>

        <script src="{{asset('js/dataTables.bootstrap4.js')}}"></script>

        <!-- JS Libraies -->
        <!--<script src="{{--asset('js/apexcharts.min.js')--}}"></script>-->

        <!-- Page Specific JS File -->
        <!--<script src="{{asset('js/index.js')}}"></script> -->
        <script src="{{asset('js/summernote-bs4.js')}}"></script>
        <script src="{{asset('js/daterangepicker.js')}}"></script>
        <script src="{{asset('js/bootstrap-timepicker.min.js')}}"></script>
        <!--<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/4.15.0/lodash.min.js"></script>-->
        <script src="{{asset('js/select2.full.min.js')}}"></script>
        <!--<script src="{{asset('js/jquery.flagstrap.js')}}"></script>-->
        <script src="{{asset('js/bootstrap-formhelpers.min.js')}}"></script>
        <script src="{{asset('js/intlTelInput.js')}}"></script>
        <script src="{{asset('js/custom-form-validation.js')}}"></script>
        <script src="{{asset('js/scripts.js')}}"></script>
        <!-- Template JS File -->
        <script src="{{asset('js/iziToast.min.js')}}"></script>

        <!-- Custom JS File -->
        <script src="{{asset('js/custom.js')}}"></script>

        <script src="{{asset('js/daterangepicker.js')}}"></script>
        <script src="{{asset('js/bootstrap-timepicker.min.js')}}"></script>
        <script src="{{asset('js/select2.full.min.js')}}"></script>
        <script src="{{asset('js/intlTelInput.js')}}"></script>
	    <script src="{{asset('js/custom-form-validation.js')}}"></script>


		<script src="{{asset('js/moment.min.js')}}"></script>
		<!--<script src="{{asset('js/Frontend/jquery-ui.js')}}"></script>
		<script src="{{asset('js/bootstrap-datepicker.js')}}"></script>-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
		<script src="{{asset('js/Frontend/easing.min.js')}}"></script>
		<script src="{{asset('js/Frontend/hoverIntent.js')}}"></script>
		<script src="{{asset('js/Frontend/superfish.min.js')}}"></script>
		<script src="{{asset('js/Frontend/wow.min.js')}}"></script>
		<script src="{{asset('js/Frontend/owl.carousel.min.js')}}"></script>
		<script src="{{asset('js/Frontend/magnific-popup.min.js')}}"></script>
		<script src="{{asset('js/Frontend/sticky.js')}}"></script>
		<script src="{{asset('js/Frontend/main.js')}}"></script>

        <!--<script src='https://kit.fontawesome.com/d482948106.js' crossorigin='anonymous'></script>-->
        <script src="{{asset('js/Frontend/font_awesome5.js')}}"></script>


		<script>
		jQuery(document).ready(function($){
			$('.refresh').on('click', function(){
				$.ajax({
					url: '<?php echo URL::to('/'); ?>/refresh-captcha',
					type: 'GET',
					success: function(html){
						$('.code_verify .image').html(html);
					}
				});
			});
			/* $('.commodate').datepicker({
			   autoclose: true,
			   inline: true,
			   format: 'yyyy-mm-dd',
			   startDate: "now"
			}); */
			 /* $( "#datepicker" ).datepicker({
				  inline: true,
				showOtherMonths: true,
				selectOtherMonths: true
			}); */

		});
		</script>
		@yield('scripts')
	</body>
</html>
