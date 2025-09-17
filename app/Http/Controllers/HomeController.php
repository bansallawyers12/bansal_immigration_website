<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; 
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;

use App\WebsiteSetting;
use App\Slider;
use App\Blog;
use App\Contact;
use App\BlogCategory;
use App\OurService;
use App\Testimonial;
use App\WhyChooseus;
use App\HomeContent;
use App\CmsPage;
use App\Mail\CommonMail;

use Illuminate\Support\Facades\Session;
use Config;
use Cookie;

use Mail;
use Swift_SmtpTransport;
use Swift_Mailer;
use Helper;

use Stripe;
use App\Enquiry;

use App\VerifiedNumber;

use App\Admin;
//use App\ClientPhone;
use App\ActivitiesLog;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HomeController extends Controller
{
	public function __construct(Request $request)
    {	
		$siteData = WebsiteSetting::where('id', '!=', '')->first();
		\View::share('siteData', $siteData);
	}
	
    public function coming_soon()
    {
        return view('coming_soon');
    }
	
	
		public static function hextorgb ($hexstring){ 
			$integar = hexdec($hexstring); 
						return array( "red" => 0xFF & ($integar >> 0x10),
			"green" => 0xFF & ($integar >> 0x8),
			"blue" => 0xFF & $integar
			);
		}
	
	public function Page(Request $request, $slug= null)
    { 
		if( Blog::where('slug', '=', $slug)->exists() ) {
            $blogdetailquery 		= Blog::where('slug', '=', $slug)->where('status', '=', 1)->with(['categorydetail']);
            $blogdetailists		=  $blogdetailquery->first(); //dd($blogdetailists);
            return view('blogdetail', compact(['blogdetailists']));
        }
        else if(CmsPage::where('slug', '=', $slug)->exists()) {
          //for all data
          $pagequery 	= CmsPage::where('slug', '=', $slug);
          $pagedata 	= $pagequery->first(); //dd($pagedata);
          if($pagedata){
            return view('Frontend.cms.index', compact(['pagedata']));
          }
        }
        else {
          abort(404);
        }
    } 
	
	public function index(Request $request)
    {
		$sliderquery 		= Slider::where('id', '!=', '')->where('status', '=', 1);		
		$sliderData 	= $sliderquery->count();	//for all data
		$sliderlists		=  $sliderquery->orderby('id','ASC')->paginate(5);
	
		$blogquery 		= Blog::where('id', '!=', '')->where('status', '=', 1);		
		$blogData 	= $blogquery->count();	//for all data
		$bloglists		=  $blogquery->orderby('id','DESC')->paginate(3);	
		
		$servicequery 		= OurService::where('id', '!=', '')->where('status', '=', 1);		
		$serviceData 	= $servicequery->count();	//for all data
		$servicelists		=  $servicequery->orderby('id','ASC')->paginate(6);	

		$testimonialquery 		= Testimonial::where('id', '!=', '')->where('status', '=', 1);		
		$testimonialData 	= $testimonialquery->count();	//for all data
		$testimoniallists		=  $testimonialquery->orderby('id','ASC')->paginate(6);	
		
		$whychoosequery 		= WhyChooseus::where('id', '!=', '')->where('status', '=', 1);		
		$whychoosequeryData 	= $whychoosequery->count();	//for all data
		$whychoosequerylists		=  $whychoosequery->orderby('id','ASC')->paginate(6);	
		
	   return view('index', compact(['sliderlists', 'sliderData', 'bloglists', 'blogData', 'servicelists', 'serviceData', 'testimoniallists', 'whychoosequeryData', 'whychoosequerylists', 'testimonialData']));
    }
	
	public function myprofile(Request $request)
    {
		return view('profile');    
    }
	public function contactus(Request $request)
    {
		return view('contact');		
    }
	
  
   
    /*public function contact(Request $request){ 
	
		$this->validate($request, [
          'fullname' => 'required',
          'email' => 'required',
          'phone' => 'required',
          'subject' => 'required',
          'message' => 'required'
          
        ]);

        $set = \App\Admin::where('id',1)->first();	
		
        $obj = new Contact;
        $obj->name = $request->fullname;
        $obj->contact_email = $request->email;
        $obj->contact_phone = $request->phone;
        $obj->subject = $request->subject;
        $obj->message = $request->message;
        $saved = $obj->save();
		if($saved){
          $obj1 = new Enquiry;
          $obj1->first_name = $request->fullname;
          $obj1->email = $request->email;
          $obj1->phone = $request->phone;
          $obj1->subject = $request->subject;
          $obj1->message = $request->message;
          $obj1->save();
        }
     
        $subject = 'You have a New Query  from  '.$request->fullname;
      	
      $details = [
          'title' => 'You have a New Query  from  '.$request->fullname,
          'body' => 'This is for testing email using smtp',
          'subject'=>$subject,
          'fullname' => 'Admin',
          'from' =>$request->fullname,
          'email'=> $request->email,
          'phone' => $request->phone,
          'description' => $request->message
        ];
      
        //\Mail::to('noreply@bansalimmigration.com.au')->send(new \App\Mail\ContactUsMail($details));
      
         //mail to customer
        $subject_customer = 'You have a new query from Bansal Immigration';
		$details_customer = [
            'title' => 'You have a new query from Bansal Immigration',
            'body' => 'This is for testing email using smtp',
            'subject'=>$subject_customer,
            'fullname' => $request->fullname,
            'from' =>'Admin',
            'email'=> $request->email,
            'phone' => $request->phone,
            'description' => $request->message
        ];
        //\Mail::to($request->email)->send(new \App\Mail\ContactUsCustomerMail($details_customer));
      
        return back()->with('success', 'Thanks for sharing your interest. our team will respond to you with in 24 hours.');
	}*/
  
  
    public function contact(Request $request){ 
        $fromAddress = config('mail.from.address'); //dd($fromAddress);
	
		// Rate limiting: max 5 attempts per minute per IP
        $key = 'contact-form:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['rate_limit' => 'Too many attempts. Please try again later.']);
        }

        // Increment rate limiter
        RateLimiter::hit($key, 60);

        // Validate honeypot
        if (!empty($request->website)) {
            return back()->withErrors(['spam' => 'Spam detected']);
        }

        // Custom validation rules
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => 'required|string|max:20|regex:/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.0-9]*$/',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            ], [
                'email.email' => 'Please enter a valid email address.'
            ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
      
        // Sanitize inputs
        $data = [
            'fullname' => filter_var($request->fullname, FILTER_SANITIZE_STRING),
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'phone' => filter_var($request->phone, FILTER_SANITIZE_STRING),
            'subject' => filter_var($request->subject, FILTER_SANITIZE_STRING),
            'message' => filter_var($request->message, FILTER_SANITIZE_STRING),
        ];
      
        $set = \App\Admin::where('id',1)->first();	
		
        $contact = new Contact;
        $contact->name = $data['fullname'];
        $contact->contact_email = $data['email'];
        $contact->contact_phone = $data['phone'];
        $contact->subject = $data['subject'];
        $contact->message = $data['message'];
        $contact->ip_address = $request->ip(); // Store the IP
        $contact->save();

        $enquiry = new Enquiry;
        $enquiry->first_name = $data['fullname'];
        $enquiry->email = $data['email'];
        $enquiry->phone = $data['phone'];
        $enquiry->subject = $data['subject'];
        $enquiry->message = $data['message'];
        $enquiry->ip_address = $request->ip(); // Store the IP
        $enquiry->save();
      
        // Send email to admin
        $subject = 'New Query from ' . $data['fullname'];
        $details = [
            'title' => $subject,
            'body' => $data['message'],
            'subject' => $subject,
            'fullname' => 'Admin',
            'from' => $data['fullname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'description' => $data['message']
        ];
      
        \Mail::to($fromAddress)->send(new \App\Mail\ContactUsMail($details));
      
        // Send confirmation email to customer
        /*$subject_customer = 'Your Query to Bansal Immigration';
        $details_customer = [
            'title' => $subject_customer,
            'body' => 'Thank you for your query. We will respond within 24 hours.',
            'subject' => $subject_customer,
            'fullname' => $data['fullname'],
            'from' => 'Admin',
            'email' => $data['email'],
            'phone' => $data['phone'],
            'description' => $data['message']
        ];
		
        \Mail::to($request->email)->send(new \App\Mail\ContactUsCustomerMail($details_customer));*/
      
        return back()->with('success', 'Thanks for sharing your interest. our team will respond to you with in 24 hours.');
	}
  
	
    
	
	public function testimonial(Request $request)
    {
		$testimonialquery 		= Testimonial::where('id', '!=', '')->where('status', '=', 1);		
		$testimonialData 	= $testimonialquery->count();	//for all data
		$testimoniallists		=  $testimonialquery->orderby('id','DESC')->get();
		
	   return view('testimonial', compact(['testimoniallists', 'testimonialData']));
    }

	public function ourservices(Request $request)
    {
		$servicequery 		= OurService::where('id', '!=', '')->where('status', '=', 1);		
		$serviceData 	= 	$servicequery->count();	//for all data 
		$servicelists		=  $servicequery->orderby('id','ASC')->get();	
		
	   return view('ourservices', compact(['servicelists', 'serviceData']));
    }	
	 
	public function blogs(Request $request)
    {
		$blogquery 		= Blog::where('id', '!=', '')->where('status', '=', 1);		
		$blogData 	= $blogquery->count();	//for all data
		$bloglists		=  $blogquery->orderby('id','DESC')->get();	
				
	   return view('blogs', compact(['bloglists', 'blogData']));
    }
	public function blogdetail(Request $request, $slug = null)
    { 
		if(isset($slug) && !empty($slug)){
			if(Blog::where('slug', '=', $slug)->exists()) 
			{ 
			$blogdetailquery 		= Blog::where('slug', '=', $slug)->where('status', '=', 1)->with(['categorydetail']);				
			$blogdetailists		=  $blogdetailquery->first();	
			
			return view('blogdetail', compact(['blogdetailists']));
			} 
			else
			{	
				return Redirect::to('/blogs')->with('error', 'Blog'.Config::get('constants.not_exist'));
			}	
		} 
		else{
			return Redirect::to('/blogs')->with('error', Config::get('constants.unauthorized'));
		}		
    }
	public function servicesdetail(Request $request, $slug = null)
    {
		if(isset($slug) && !empty($slug)){
			if(OurService::where('slug', '=', $slug)->exists()) 
			{
			$servicesdetailquery 		= OurService::where('slug', '=', $slug)->where('status', '=', 1);				
			$servicesdetailists		=  $servicesdetailquery->first();	
			
			return view('servicesdetail', compact(['servicesdetailists']));
			} 
			else
			{	
				return Redirect::to('/ourservices')->with('error', 'Our Services'.Config::get('constants.not_exist'));
			}	
		} 
		else{
			return Redirect::to('/ourservices')->with('error', Config::get('constants.unauthorized'));
		}		
    }

	public function bookappointment()
    {
        return view('bookappointment');
    }
    
    public function bookappointment1()
    {
        return view('bookappointment1');
    }

	
    public function getdatetimebackend(Request $request)
    {   //dd($request->all());
        $enquiry_item = $request->enquiry_item;
        $req_service_id = $request->id;
        //echo $enquiry_item."===".$req_service_id; die;
        if( $enquiry_item != "" && $req_service_id != "")
        {
            if( $req_service_id == 1 ) { //Paid service
                $person_id = 1; //Ajay
                $service_type = $req_service_id; //Paid service
            }
            else if( $req_service_id == 2 ) { //Free service
                if( $enquiry_item == 1 || $enquiry_item == 6 || $enquiry_item == 7 ){
                    //1 => Permanent Residency Appointment
                    //6 => Complex matters: AAT, Protection visa, Federal Cas
                    //7 => Visa Cancellation/ NOICC/ Visa refusals
                    $person_id = 1; //Ajay
                    $service_type = $req_service_id; //Free service
                }
                else if( $enquiry_item == 2 || $enquiry_item == 3 ){
                    //2 => Temporary Residency Appointment
                    //3 => JRP/Skill Assessment
                    $person_id = 2; //Shubam
                    $service_type = $req_service_id; //Free service
                }
                else if( $enquiry_item == 4 ){ //Tourist Visa
                    $person_id = 3; //Tourist
                    $service_type = $req_service_id; //Free service
                }
                else if( $enquiry_item == 5 ){ //Education/Course Change/Student Visa/Student Dependent Visa (for education selection only)
                    $person_id = 4; //Education
                    $service_type = $req_service_id; //Free service
                }
            }
        }
        //echo $person_id."===".$service_type; die;
        $bookservice = \App\BookService::where('id', $req_service_id)->first();//dd($bookservice);
        $service = \App\BookServiceSlotPerPerson::where('person_id', $person_id)->where('service_type', $service_type)->first();//dd($service);
	    if( $service ){
		   $weekendd  =array();
		    if($service->weekend != ''){
				$weekend = explode(',',$service->weekend);
				foreach($weekend as $e){
					if($e == 'Sun'){
						$weekendd[] = 0;
					}else if($e == 'Mon'){
						$weekendd[] = 1;
					}else if($e == 'Tue'){
						$weekendd[] = 2;
					}else if($e == 'Wed'){
						$weekendd[] = 3;
					}else if($e == 'Thu'){
						$weekendd[] = 4;
					}else if($e == 'Fri'){
						$weekendd[] = 5;
					}else if($e == 'Sat'){
						$weekendd[] = 6;
					}
				}
			}
			$start_time = date('H:i',strtotime($service->start_time));
			$end_time = date('H:i',strtotime($service->end_time));

            /*$disabledatesarray = array();  dd($service->disabledates);
			if($service->disabledates != ''){
				$dates = json_decode($service->disabledates,true); dd($dates);
                $dates  = array_flip($dates); //var_dump($dates);
				foreach($dates as $date){
					//$datey = explode('/', $date); //08/03/2024  ["11/03/2024", "13/03/2024"];
					//$disabledatesarray[] = $datey[1].'-'.$datey[0].'-'.$datey[2];
                    $disabledatesarray[] = $date;
				}
                //dd($disabledatesarray);
			}*/
            if($service->disabledates != ''){
                $disabledatesarray =  array();
                if( strpos($service->disabledates, ',') !== false ) {
                    $disabledatesArr = explode(',',$service->disabledates);
                    $disabledatesarray = $disabledatesArr;
                } else {
                    $disabledatesarray = array($service->disabledates);
                }
            } else {
                $disabledatesarray =  array();
            }
            // Add the current date to the array
            //$disabledatesarray[] = date('d/m/Y'); //dd($disabledatesarray);
            return json_encode(array('success'=>true, 'duration' =>$bookservice->duration,'weeks' => $weekendd,'start_time' =>$start_time,'end_time'=>$end_time,'disabledatesarray'=>$disabledatesarray));
	   }else{
		 return json_encode(array('success'=>false, 'duration' =>0));
	   }
    }

	
    public function getdatetime(Request $request)
    {   //dd($request->all());
        $enquiry_item = $request->enquiry_item;
        $req_service_id = $request->id;
        //echo $enquiry_item."===".$req_service_id; die;
        if(isset($request->inperson_address) && $request->inperson_address == 1 ) { //Adelaide
            if( $enquiry_item != "" && $req_service_id != "") {
                if( $req_service_id == 1 ) { //Paid service
                    $person_id = 5; //Adelaide
                    $service_type = $req_service_id; //Paid service
                }
                else if( $req_service_id == 2 ) { //Free service
                    $person_id = 5; //Adelaide
                    $service_type = $req_service_id; //Free service
                }
            }
        }
        else { //Melbourne

            if( $enquiry_item != "" && $req_service_id != "") {
                if( $req_service_id == 1 ) { //Paid service
                    $person_id = 1; //Ajay
                    $service_type = $req_service_id; //Paid service
                }
                else if( $req_service_id == 2 ) { //Free service
                    if( $enquiry_item == 1 || $enquiry_item == 6 || $enquiry_item == 7 ){
                        //1 => Permanent Residency Appointment
                        //6 => Complex matters: AAT, Protection visa, Federal Cas
                        //7 => Visa Cancellation/ NOICC/ Visa refusals
                        $person_id = 1; //Ajay
                        $service_type = $req_service_id; //Free service
                    }
                    else if( $enquiry_item == 2 || $enquiry_item == 3 ){
                        //2 => Temporary Residency Appointment
                        //3 => JRP/Skill Assessment
                        $person_id = 2; //Shubam
                        $service_type = $req_service_id; //Free service
                    }
                    else if( $enquiry_item == 4 ){ //Tourist Visa
                        $person_id = 3; //Tourist
                        $service_type = $req_service_id; //Free service
                    }
                    else if( $enquiry_item == 5 ){ //Education/Course Change/Student Visa/Student Dependent Visa (for education selection only)
                        $person_id = 4; //Education
                        $service_type = $req_service_id; //Free service
                    }
                }
            }
        }
        //echo $person_id."===".$service_type; die;
        $bookservice = \App\BookService::where('id', $req_service_id)->first();//dd($bookservice);
        $service = \App\BookServiceSlotPerPerson::where('person_id', $person_id)->where('service_type', $service_type)->first();//dd($service);
	    if( $service ){
		   $weekendd  =array();
		    if($service->weekend != ''){
				$weekend = explode(',',$service->weekend);
				foreach($weekend as $e){
					if($e == 'Sun'){
						$weekendd[] = 0;
					}else if($e == 'Mon'){
						$weekendd[] = 1;
					}else if($e == 'Tue'){
						$weekendd[] = 2;
					}else if($e == 'Wed'){
						$weekendd[] = 3;
					}else if($e == 'Thu'){
						$weekendd[] = 4;
					}else if($e == 'Fri'){
						$weekendd[] = 5;
					}else if($e == 'Sat'){
						$weekendd[] = 6;
					}
				}
			}
			$start_time = date('H:i',strtotime($service->start_time));
			$end_time = date('H:i',strtotime($service->end_time));

            if($service->disabledates != ''){
                $disabledatesarray =  array();
                if( strpos($service->disabledates, ',') !== false ) {
                    $disabledatesArr = explode(',',$service->disabledates);
                    $disabledatesarray = $disabledatesArr;
                } else {
                    $disabledatesarray = array($service->disabledates);
                }
            } else {
                $disabledatesarray =  array();
            }

            // Add the current date to the array
            $disabledatesarray[] = date('d/m/Y'); //dd($disabledatesarray);
            if(isset($request->inperson_address) && $request->inperson_address == 1 ) { //Adelaide
                $duration = $bookservice->duration;
            } else { //Melbourne
                if( isset($req_service_id) && $req_service_id == 1){ //Paid
                    $duration = 15; //In melbourne case paid service = 15
                } else if( isset($req_service_id) && $req_service_id == 2){ //Free
                    $duration = $bookservice->duration; //In melbourne case free service = 15
                }
            }
            return json_encode(array('success'=>true, 'duration' =>$duration,'weeks' => $weekendd,'start_time' =>$start_time,'end_time'=>$end_time,'disabledatesarray'=>$disabledatesarray));
	    } else {
		 return json_encode(array('success'=>false, 'duration' =>0));
	    }
    }

    public function getdisableddatetime(Request $request)
    {
		$requestData = $request->all(); //dd($requestData);
		$date = explode('/', $requestData['sel_date']);
		$datey = $date[2].'-'.$date[1].'-'.$date[0];

        //Adelaide
        if( isset($request->inperson_address) && $request->inperson_address == 1 )
        {
            if( isset($request->service_id) && $request->service_id == 1  ){ //Adelaide Paid Service
                $book_service_slot_per_person_tbl_unique_id = 6;
            } else if( isset($request->service_id) && $request->service_id == 2  ){ //Adelaide Free Service
                $book_service_slot_per_person_tbl_unique_id = 8;
            }

            $service = \App\Appointment::select('id','date','time')
            ->where('inperson_address', '=', 1)
            ->where('status', '!=', 7)
            ->whereDate('date', $datey)
            ->exists();

            $servicelist = \App\Appointment::select('id','date','time')
            ->where('inperson_address', '=', 1)
            ->where('status', '!=', 7)
            ->whereDate('date', $datey)
            ->get();
        }

        //Melbourne
        else
        {
            if
            (
                ( isset($request->service_id) && $request->service_id == 1  )
                ||
                (
                    ( isset($request->service_id) && $request->service_id == 2 )
                    &&
                    ( isset($request->enquiry_item) && ( $request->enquiry_item == 1 || $request->enquiry_item == 6 || $request->enquiry_item == 7) )
                )
            ) { //Paid
                if( isset($request->service_id) && $request->service_id == 1  ){ //Ajay Paid Service
                    $book_service_slot_per_person_tbl_unique_id = 1;
                } else if( isset($request->service_id) && $request->service_id == 2  ){ //Ajay Free Service
                    $book_service_slot_per_person_tbl_unique_id = 2;
                }

                $service = \App\Appointment::select('id', 'date', 'time')
                ->where(function ($query) {
                    $query->whereNull('inperson_address')
                        ->orWhere('inperson_address', '')
                        ->orWhere('inperson_address', 2); //For Melbourne
                })
                ->where('status', '!=', 7)
                ->whereDate('date', $datey)
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->whereIn('noe_id', [1, 2, 3, 4, 5, 6, 7, 8])
                        ->where('service_id', 1);
                    })
                    ->orWhere(function ($q) {
                        $q->whereIn('noe_id', [1, 6, 7])
                        ->where('service_id', 2);
                    });
                })->exists();

                $servicelist = \App\Appointment::select('id', 'date', 'time')
                ->where(function ($query) {
                    $query->whereNull('inperson_address')
                        ->orWhere('inperson_address', '')
                        ->orWhere('inperson_address', 2); //For Melbourne
                })
                ->where('status', '!=', 7)
                ->whereDate('date', $datey)
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->whereIn('noe_id', [1, 2, 3, 4, 5, 6, 7, 8])
                        ->where('service_id', 1);
                    })
                    ->orWhere(function ($q) {
                        $q->whereIn('noe_id', [1, 6, 7])
                        ->where('service_id', 2);
                    });
                })->get();
            }
            else if( isset($request->service_id) && $request->service_id == 2) { //Free
                if( isset($request->enquiry_item) && ( $request->enquiry_item == 2 || $request->enquiry_item == 3 ) ) { //Temporary and JRP

                    if( isset($request->service_id) && $request->service_id == 2  ){ //Shubam Free Service
                        $book_service_slot_per_person_tbl_unique_id = 3;
                    }

                    $service = \App\Appointment::select('id','date','time')
                    ->where(function ($query) {
                        $query->whereNull('inperson_address')
                            ->orWhere('inperson_address', '')
                            ->orWhere('inperson_address', 2); //For Melbourne
                    })
                    ->where('status', '!=', 7)
                    ->whereDate('date', $datey)
                    ->where(function ($query) {
                        $query->whereIn('noe_id', [2,3])
                        ->Where('service_id', 2);
                    })->exists();

                    $servicelist = \App\Appointment::select('id','date','time')
                    ->where(function ($query) {
                        $query->whereNull('inperson_address')
                            ->orWhere('inperson_address', '')
                            ->orWhere('inperson_address', 2); //For Melbourne
                    })
                    ->where('status', '!=', 7)
                    ->whereDate('date', $datey)
                    ->where(function ($query) {
                        $query->whereIn('noe_id', [2,3])
                        ->Where('service_id', 2);
                    })->get();
                }
                else if( isset($request->enquiry_item) && ( $request->enquiry_item == 4 ) ) { //Tourist Visa

                    if( isset($request->service_id) && $request->service_id == 2  ){ //Tourist Free Service
                        $book_service_slot_per_person_tbl_unique_id = 4;
                    }

                    $service = \App\Appointment::select('id','date','time')
                    ->where(function ($query) {
                        $query->whereNull('inperson_address')
                            ->orWhere('inperson_address', '')
                            ->orWhere('inperson_address', 2); //For Melbourne
                    })
                    ->where('status', '!=', 7)
                    ->whereDate('date', $datey)
                    ->where(function ($query) {
                        $query->whereIn('noe_id', [4])
                        ->Where('service_id', 2);
                    })->exists();

                    $servicelist = \App\Appointment::select('id','date','time')
                    ->where(function ($query) {
                        $query->whereNull('inperson_address')
                            ->orWhere('inperson_address', '')
                            ->orWhere('inperson_address', 2); //For Melbourne
                    })
                    ->where('status', '!=', 7)
                    ->whereDate('date', $datey)
                    ->where(function ($query) {
                        $query->whereIn('noe_id', [4])
                        ->Where('service_id', 2);
                    })->get();
                }
                else if( isset($request->enquiry_item) && ( $request->enquiry_item == 5 ) ) { //Education/Course Change
                    if( isset($request->service_id) && $request->service_id == 2  ){ //Education Free Service
                        $book_service_slot_per_person_tbl_unique_id = 5;
                    }
                    $service = \App\Appointment::select('id','date','time')
                    ->where(function ($query) {
                        $query->whereNull('inperson_address')
                            ->orWhere('inperson_address', '')
                            ->orWhere('inperson_address', 2); //For Melbourne
                    })
                    ->where('status', '!=', 7)
                    ->whereDate('date', $datey)
                    ->where(function ($query) {
                        $query->whereIn('noe_id', [5])
                        ->Where('service_id', 2);
                    })->exists();

                    $servicelist = \App\Appointment::select('id','date','time')
                    ->where(function ($query) {
                        $query->whereNull('inperson_address')
                            ->orWhere('inperson_address', '')
                            ->orWhere('inperson_address', 2); //For Melbourne
                    })
                    ->where('status', '!=', 7)
                    ->whereDate('date', $datey)
                    ->where(function ($query) {
                        $query->whereIn('noe_id', [5])
                        ->Where('service_id', 2);
                    })->get();
                }
            }
        }
        //dd($servicelist);
        $disabledtimeslotes = array();
	    if($service){
            foreach($servicelist as $list){
                $disabledtimeslotes[] = date('g:i A', strtotime($list->time)); //'H:i A'
			}
            $disabled_slot_arr = \App\BookServiceDisableSlot::select('id','slots')->where('book_service_slot_per_person_id', $book_service_slot_per_person_tbl_unique_id)->whereDate('disabledates', $datey)->get();
            //dd($disabled_slot_arr);
            if(!empty($disabled_slot_arr) && count($disabled_slot_arr) >0 ){
                $newArray = explode(",",$disabled_slot_arr[0]->slots); //dd($newArray);
            } else {
                $newArray = array();
            }
            $disabledtimeslotes = array_merge($disabledtimeslotes, $newArray); //dd($disabledtimeslotes);
		    return json_encode(array('success'=>true, 'disabledtimeslotes' =>$disabledtimeslotes));
	    } else {
            $disabled_slot_arr = \App\BookServiceDisableSlot::select('id','slots')->where('book_service_slot_per_person_id', $book_service_slot_per_person_tbl_unique_id)->whereDate('disabledates', $datey)->get();
            //dd($disabled_slot_arr);
            if(!empty($disabled_slot_arr) && count($disabled_slot_arr) >0 ){
                $newArray = explode(",",$disabled_slot_arr[0]->slots); //dd($newArray);
            } else {
                $newArray = array();
            }
            $disabledtimeslotes = array_merge($disabledtimeslotes, $newArray); //dd($disabledtimeslotes);
		    return json_encode(array('success'=>true, 'disabledtimeslotes' =>$disabledtimeslotes));
	    }
    }
  
   
	
	
	public function stripe($appointmentId)
    {
        $appointmentInfo = \App\Appointment::find($appointmentId);
        if($appointmentInfo){
            $adminInfo = \App\Admin::find($appointmentInfo->client_id);
        } else {
            $adminInfo = array();
        }
        return view('stripe', compact(['appointmentId','appointmentInfo','adminInfo']));
    }

    public function stripePost(Request $request)
    {
        $requestData = $request->all(); //dd($requestData);
        $appointment_id = $requestData['appointment_id'];
        $email = $requestData['customerEmail'];
        $cardName = $requestData['cardName'];
        $stripeToken = $requestData['stripeToken'];
        $currency = "aud";
        $payment_type = "stripe";
        $order_date = date("Y-m-d H:i:s");
        $amount = 150;
        

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = Stripe\Customer::create(array("email" => $email,"name" => $cardName,"source" => $stripeToken));

        $payment_result = Stripe\Charge::create ([
            "amount" => $amount * 100,
            "currency" => $currency,
            "customer" => $customer->id,
            "description" => "Paid To bansalimmigration.com.au For Migration Advice By $cardName"
        ]);
        //dd($payment_result);
        //update Order status
        if ( ! empty($payment_result) && $payment_result["status"] == "succeeded")
        { //success
            //Order insertion
            $stripe_payment_intent_id = $payment_result['id'];
            $payment_status = "Paid";
            $order_status = "Completed";
            $appontment_status = 10; //Pending Appointment With Payment Success
            $ins = DB::table('tbl_paid_appointment_payment')->insert([
                'order_hash' => $stripeToken,
                'payer_email' => $email,
                'amount' => $amount,
                'currency' => $currency,
                'payment_type' => $payment_type,
                'order_date' => $order_date,
                'name' => $cardName,
                'stripe_payment_intent_id'=>$stripe_payment_intent_id,
                'payment_status'=>$payment_status,
                'order_status'=>$order_status
            ]);
            if($ins ){
                DB::table('appointments')->where('id',$appointment_id)->update( array('status'=>$appontment_status,'order_hash'=>$stripeToken));
            }
            Session::flash('success', 'Payment successful!');
        } else { //failed
            $stripe_payment_intent_id = $payment_result['id'];
            $payment_status = "Unpaid";
            $order_status = "Payement Failure";
            $appontment_status = 11; //Pending Appointment With Payment Fail
            $ins = DB::table('tbl_paid_appointment_payment')->insert([
                'order_hash' => $stripeToken,
                'payer_email' => $email,
                'amount' => $amount,
                'currency' => $currency,
                'payment_type' => $payment_type,
                'order_date' => $order_date,
                'name' => $cardName,
                'stripe_payment_intent_id'=>$stripe_payment_intent_id,
                'payment_status'=>$payment_status,
                'order_status'=>$order_status
            ]);
            if($ins ){
                DB::table('appointments')->where('id',$appointment_id)->update( array('status'=>$appontment_status,'order_hash'=>$stripeToken));
            }
            //return json_encode(array('success'=>false));
            Session::flash('error', 'Payment failed!');
        }
        return back();
    }
  
  
    public function search_result(Request $request)
    {   //dd($request->all());
        if ( isset($request->search) &&  $request->search != "" ) {
            $search_string 	= $request->search;
        } else {
            $search_string 	= 'search_string';
        }
        /*$query 	= CmsPage::where('title', 'LIKE', '%'.$search_string.'%')
        ->orWhere('slug', 'LIKE', '%' . $search_string . '%')
        ->orWhere('content', 'LIKE', '%' . $search_string . '%')
        ->orWhere('meta_title', 'LIKE', '%' . $search_string . '%')
        ->orWhere('meta_description', 'LIKE', '%' . $search_string . '%')
        ->orWhere('meta_keyward', 'LIKE', '%' . $search_string . '%');*/
      
        $query 	= CmsPage::where('title', 'LIKE', '%'.$search_string.'%')
        ->orWhere('slug', 'LIKE', '%' . $search_string . '%')
        ->orWhere('meta_title', 'LIKE', '%' . $search_string . '%')
        ->orWhere('meta_keyward', 'LIKE', '%' . $search_string . '%');
      
        $totalData 	= $query->count();//dd($totalData);
        //$lists = $query->toSql();
        $lists	= $query->sortable(['id' => 'desc'])->paginate(20); //dd($lists);
        return view('searchresults', compact(['lists', 'totalData']));
    }
  
  
  
    //Email Verfiy
    /*public function emailVerify(Request $request)
    {
        $requestData = $request->all(); //dd($requestData);
        $client_email = $requestData['client_email'];
        $client_id = $requestData['client_id'];
        $client_fname = $requestData['client_fname'];

        //Email To client
        $details = [
            'title' => 'Please click the button below to verify your email address:',
            'body' => 'This is for testing email using smtp',
            'fullname' => $client_fname,
            'email'=> $client_email,
            'client_id'=> $client_id
        ];
        if( \Mail::to($client_email)->send(new \App\Mail\ClientVerifyMail($details))) {
            $message = 'Email is successfully sent at this email';
		    return json_encode(array('success'=>true,'message'=>$message));
        } else {
            $message = 'Email is not sent.Please Try again';
            return json_encode(array('success'=>false,'message'=>$message));
        }
    }*/







	
}
