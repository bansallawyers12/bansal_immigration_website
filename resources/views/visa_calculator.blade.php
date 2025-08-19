@extends('layouts.frontend')
@section('seoinfo')
	<title>Student visa financial calculator - Bansal Immigration</title>
	<meta name="description" content="ViTuition fee for one year: Living expenses of the main applicant: AUD20,290Living expenses of additional applicant aged 18+: AUD7,100" />
	<link rel="canonical" href="https://www.bansalimmigration.com.au/student-visa-financial-calculator" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="Student visa financial calculator - Bansal Immigration" />
	<meta property="og:description" content="ViTuition fee for one year: Living expenses of the main applicant: AUD20,290Living expenses of additional applicant aged 18+: AUD7,100" />
	<meta property="og:url" content="https://www.bansalimmigration.com.au/student-visa-financial-calculator" />
	<meta property="og:site_name" content="<?php echo @\App\ThemeOption::where('meta_key','site_name')->first()->meta_value; ?>" />
	<meta property="article:publisher" content="https://www.facebook.com/BANSALImmigration/" />
	<meta property="article:modified_time" content="2023-04-04T21:06:24+00:00" />
	<meta property="og:image" content="<?php echo URL::to('/'); ?>/public/img/bansal-immigration-icon.jpg" />
	<meta property="og:image:width" content="200" />
	<meta property="og:image:height" content="200" />
	<meta property="og:image:type" content="image/jpeg" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="Student visa financial calculator - Bansal Immigration" />
	<meta name="twitter:description" content="ViTuition fee for one year: Living expenses of the main applicant: AUD20,290Living expenses of additional applicant aged 18+: AUD7,100" />
	<meta name="twitter:image" content="<?php echo URL::to('/'); ?>/public/img/bansal-immigration-icon.jpg" />
	<meta name="twitter:site" content="@Bansalimmi" />
	<meta name="twitter:label1" content="Est. reading time" />
	<meta name="twitter:data1" content="6 minutes" />
@endsection
@section('content')
    <style>
    .page_image{border-radius: 20px;margin-right: 20px;margin-bottom: 20px;width: 400px;float: left;}
    /* ul li, ol li { list-style:none !important;}*/
    ul {
        list-style-type: disc !important;
        }
    h1{
        color:#1C174B;
    }
    p strong{
        /*color:#1C174B;*/
        color:#000;
    }
    a{ font-weight: bold;}

    /*li{
        margin-left:35px;  }
    nav ul li {
        margin-left:0px;
    }*/
    p::first-letter {
        text-transform: capitalize;
    }
    li::first-letter {
        text-transform: capitalize; /* or uppercase */
    }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .card-flip {
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }
        .card-flip.flipped {
            transform: rotateY(180deg);
        }
        .card-front, .card-back {
            backface-visibility: hidden;
        }
        .card-back {
            transform: rotateY(180deg);
        }
    </style>

    <section class="custom_breadcrumb bg-img bg-overlay" style="background-image: url(https://www.bansalimmigration.com.au/public/img/Frontend/bg-2.jpg);">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-heading text-center mx-auto">
					<div class="section-header">
						<h3>Student Visa financial Calculator</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="custom_inner_page" section-padding-5-0="" style="background-color:#F7F7F7;">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-12 col-md-12">
				<div class="cus_inner_content">
                    <!--<h1></h1>-->
					<h1>Calculate Your Financial Requirements for an Australian Student Visa</h1>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">As of <strong>July 1, 2024</strong>, the Australian Government has updated the financial requirements for the <strong>Student Visa (Subclass 500)</strong> to ensure that international students have sufficient funds to support themselves and any accompanying family members during their stay.</span></span></span></span></p>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Updated Financial Requirements:</span></span></strong></span></span></p>

					<ul>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Tuition Fees:</span></span></strong></span></span>

						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Applicants must demonstrate the ability to cover <strong>tuition fees</strong> for <strong>one academic year</strong>.</span></span></span></span></li>
						</ul>
						</li>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Living Expenses:</span></span></strong></span></span>
						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Primary Applicant:</span></span></strong></span></span>
							<ul style="list-style-type:square">
								<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Required to have at least <strong>AUD 29,710</strong> to cover living costs for one year.</span></span></span></span></li>
							</ul>
							</li>
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Additional Family Members:</span></span></strong></span></span>
							<ul style="list-style-type:square">
								<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Spouse or Partner:</span></span></strong></span></span>
								<ul style="list-style-type:square">
									<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">An additional <strong>AUD 10,000</strong> per year.</span></span></span></span></li>
								</ul>
								</li>
								<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Child (Under 18 years):</span></span></strong></span></span>
								<ul style="list-style-type:square">
									<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">An additional <strong>AUD 5,000</strong> per year, per child.</span></span></span></span></li>
								</ul>
								</li>
							</ul>
							</li>
						</ul>
						</li>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Travel Expenses:</span></span></strong></span></span>
						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">An estimated <strong>AUD 2,000</strong> per person to cover return airfare to Australia.</span></span></span></span></li>
						</ul>
						</li>
					</ul>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Calculation Formula:</span></span></strong></span></span></p>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">To determine the total funds required, use the following formula:</span></span></span></span></p>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Total Funds Required = Tuition Fees for One Year + Living Expenses (Primary Applicant + Additional Family Members) + Travel Expenses (All Applicants)</span></span></strong></span></span></p>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Example Calculation:</span></span></strong></span></span></p>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">For a primary applicant with a spouse and one child:</span></span></span></span></p>

					<ul>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Tuition Fees:</span></span></strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;"> AUD 30,000</span></span></span></span></li>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Living Expenses:</span></span></strong></span></span>
						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Primary Applicant: AUD 29,710</span></span></span></span></li>
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Spouse: AUD 10,000</span></span></span></span></li>
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Child: AUD 5,000</span></span></span></span></li>
						</ul>
						</li>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Travel Expenses:</span></span></strong></span></span>
						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">3 individuals x AUD 2,000 = AUD 6,000</span></span></span></span></li>
						</ul>
						</li>
					</ul>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Total Funds Required = AUD 30,000 + AUD 29,710 + AUD 10,000 + AUD 5,000 + AUD 6,000 = AUD 80,710</span></span></strong></span></span></p>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Important Considerations:</span></span></strong></span></span></p>

					<ul>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Evidence of Funds:</span></span></strong></span></span>

						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Applicants must provide <strong>documentary evidence</strong> of their financial capacity, such as bank statements, loan documents, or scholarship letters.</span></span></span></span></li>
						</ul>
						</li>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Genuine Access to Funds:</span></span></strong></span></span>
						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">It&#39;s essential to demonstrate that the funds are <strong>genuinely available</strong> for use during your stay in Australia.</span></span></span></span></li>
						</ul>
						</li>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Currency Fluctuations:</span></span></strong></span></span>
						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Ensure that the funds meet the required amounts in <strong>Australian Dollars (AUD)</strong>, considering any currency exchange fluctuations.</span></span></span></span></li>
						</ul>
						</li>
					</ul>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Additional Updates:</span></span></strong></span></span></p>

					<ul>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Visa Application Fee Increase:</span></span></strong></span></span>

						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Effective <strong>July 1, 2024</strong>, the <strong>Student Visa application fee</strong> has increased from <strong>AUD 710</strong> to <strong>AUD 1,600</strong>. </span></span></span></span></li>
						</ul>
						</li>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Genuine Student Requirement:</span></span></strong></span></span>
						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Introduced on <strong>March 23, 2024</strong>, all Student Visa applicants must demonstrate they are a <strong>genuine student</strong> with a primary intention to study in Australia. </span></span></span></span></li>
						</ul>
						</li>
					</ul>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">How Bansal Immigration Can Assist You:</span></span></strong></span></span></p>

					<ul>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Personalized Financial Assessment:</span></span></strong></span></span>

						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">We provide tailored assessments to help you understand and meet the financial requirements for your Student Visa application.</span></span></span></span></li>
						</ul>
						</li>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Comprehensive Application Support:</span></span></strong></span></span>
						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Our experienced consultants guide you through the entire visa application process, ensuring all financial evidence meets the latest standards.</span></span></span></span></li>
						</ul>
						</li>
						<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Up-to-Date Information:</span></span></strong></span></span>
						<ul style="list-style-type:circle">
							<li><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">We stay informed about the latest immigration policies to provide you with accurate and current advice.</span></span></span></span></li>
						</ul>
						</li>
					</ul>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><em><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">Plan your educational journey in Australia with confidence. Contact Bansal Immigration today for expert guidance and support.</span></span></em></span></span></p>

					<p><span style="font-size:11pt"><span style="font-family:Calibri,&quot;sans-serif&quot;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,&quot;serif&quot;">DISCLAIMER: The content provided is for informational purposes only and does not constitute immigration or legal advice. It is subject to change. Consult an Australian MARA registered agent or lawyer for professional advice before making any application.</span></span></span></span></p>

					<p>&nbsp;</p>

				</div>
			</div>
		</div>
	</div>

    <div class="container mx-auto p-6 max-w-3xl bg-white/90 backdrop-blur-sm rounded-xl shadow-2xl mb-5 mt-5">
        <h1 class="text-4xl font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-8">Australian Student Visa Funds Calculator 📐</h1>
        <p class="text-center text-gray-600 mb-8">Estimate your funds for a Subclass 500 visa (updated May 10, 2024).</p>

        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-4 rounded-lg mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <form action="{{ route('visa.calculate') }}" method="POST">
                @csrf
                <!-- Course Duration -->
                <div class="mb-6">
                    <label for="course_duration" class="block text-sm font-medium text-gray-700">Course Duration (months, max 12)</label>
                    <input type="number" id="course_duration" name="course_duration" value="{{ old('course_duration', 12) }}" min="1" max="12" step="1" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-300">
                </div>

                <!-- Tuition Fees -->
                <div class="mb-6">
                    <label for="tuition_fees" class="block text-sm font-medium text-gray-700">Annual Tuition Fees (AUD)</label>
                    <input type="number" id="tuition_fees" name="tuition_fees" value="{{ old('tuition_fees', 0) }}" min="0" step="100" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-300">
                </div>

                <!-- Primary Applicant -->
                <div class="mb-6">
                    <label for="primary_applicant" class="block text-sm font-medium text-gray-700">Primary Applicant (AUD 29,710/year)</label>
                    <input type="number" id="primary_applicant" name="primary_applicant" value="{{ old('primary_applicant', 1) }}" min="1" max="1" step="1" readonly
                           class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed">
                </div>

                <!-- Secondary Applicant (Spouse/Partner) -->
                <div class="mb-6">
                    <label for="spouse" class="block text-sm font-medium text-gray-700">Secondary Applicant (Spouse/Partner) (AUD 10,394/year)</label>
                    <input type="number" id="spouse" name="spouse" value="{{ old('spouse', 0) }}" min="0" step="1" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-300">
                </div>

                <!-- Accompanying Children -->
                <div class="mb-6">
                    <label for="children" class="block text-sm font-medium text-gray-700">Number of Accompanying Children (under 18)</label>
                    <input type="number" id="children" name="children" value="{{ old('children', 0) }}" min="0" step="1" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-300">
                </div>

                <!-- School-Aged Children -->
                <div class="mb-6">
                    <label for="school_children" class="block text-sm font-medium text-gray-700">Number of School-Aged Children</label>
                    <input type="number" id="school_children" name="school_children" value="{{ old('school_children', 0) }}" min="0" step="1" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-300">
                </div>

                <!-- Calculate Button -->
                <div>
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-full hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 flex items-center justify-center">
                        Calculate Funds 📊
                    </button>
                </div>
            </form>

            <!-- Results Section -->
            @if (session('result'))
                <div class="card-flip mt-6 p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl shadow-lg transform transition-transform duration-700 flipped">
                    <div class="card-back">
                        <h2 class="text-2xl font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Required Funds (AUD)</h2>
                        <div class="mt-4 space-y-3">
                            <p class="text-lg"><span class="font-medium">Tuition Fees:</span> ${{ number_format(session('result.tuition')) }}</p>
                            <p class="text-lg"><span class="font-medium">Living Expenses (Primary Applicant):</span> ${{ number_format(session('result.living_main')) }}</p>
                            <p class="text-lg"><span class="font-medium">Living Expenses (Spouse/Partner):</span> ${{ number_format(session('result.living_spouse')) }}</p>
                            <p class="text-lg"><span class="font-medium">Living Expenses (Children):</span> ${{ number_format(session('result.living_children')) }}</p>
                            <p class="text-lg"><span class="font-medium">Schooling Costs:</span> ${{ number_format(session('result.schooling')) }}</p>
                            <p class="text-lg"><span class="font-medium">Travel Costs:</span> ${{ number_format(session('result.travel')) }}</p>
                            <p class="text-xl font-bold"><span class="font-medium">Total Funds Required:</span> ${{ number_format(session('result.total')) }}</p>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">Note: You may also meet requirements by showing an annual income of AUD 87,856 (no family) or AUD 102,500 (with family). Contact a migration agent for details.</p>
                    </div>
                </div>
            @endif
        </div>


    </div>
</section>
@endsection
