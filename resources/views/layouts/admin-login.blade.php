<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="description" content="">
	<meta name="author" content="Bansal Immigration">
	<link rel="shortcut icon" type="image/png" href="{{asset('img/favicon.png')}}"/>
	<title>Bansal Immigration | @yield('title')</title>
	<!-- Favicons-->
	<link rel="shortcut icon" href="{{asset('img/Frontend/img/bansal-favicon.png')}}" type="image/x-icon">

	 <!-- BASE CSS -->
	<link href="{{asset('css/app.min.css')}}" rel="stylesheet">
	<link href="{{asset('css/bootstrap-social.css')}}" rel="stylesheet">
	<link href="{{asset('css/style.css')}}" rel="stylesheet">
	<link href="{{asset('css/components.css')}}" rel="stylesheet">
	<link href="{{asset('css/custom.css')}}" rel="stylesheet">

</head>
<style>
.bg{
    background-image: url('/img/bansal_crm_background_image.jpg');
    height: 100%;
    margin: 0;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}
</style>
<body class="bg">
	<div class="loader"></div>
	<div id="app">
		@yield('content')
	</div>
	<!-- COMMON SCRIPTS -->
	<script type="text/javascript">
		var site_url = "<?php echo URL::to('/'); ?>";
	</script>
	<script src="{{asset('js/app.min.js')}}"></script>
	<script src="{{asset('js/scripts.js')}}"></script>
	<script src="{{asset('js/custom.js')}}"></script>
</body>
</html>
