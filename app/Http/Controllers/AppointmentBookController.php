<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Route;

use App\Appointment;
use App\Admin;
use Helper;
use Auth;
use Config;
use Stripe;
use DB;


//use App\Services\TwilioService;
use App\Services\SmsService;
use App\VerifiedNumber;

class AppointmentBookController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //protected $twilioService;
    protected $smsService;

    /*public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }*/
  
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
  
    public function getNextCounter($currentCounter) {
        // Convert current counter to an integer
        $counter = intval($currentCounter);

        // Increment the counter
        $counter++;

        // If the counter exceeds 99999, reset it to 1
        if ($counter > 99999) {
            $counter = 1;
        }

        // Format the counter as a 5-digit number with leading zeros
        return str_pad($counter, 5, '0', STR_PAD_LEFT);
    }
  
    /**
     * Book An Free Appointment.
     *
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $requestData = $request->all(); //dd($requestData);
        $preferred_language = $requestData['preferred_language'];
        $service_id = $requestData['service_id'];
		$noe_id = $requestData['noe_id'];
		$fullname = $requestData['fullname'];
		//$title = $requestData['title'];
		$description = $requestData['description'];
		$email = $requestData['email'];
		$phone = $requestData['phone'];
		$date = explode('/', $requestData['date']);
		$datey = $date[2].'-'.$date[1].'-'.$date[0];
        $service = \App\BookService::find($requestData['service_id']);

		$user = \App\Admin::where(function ($query) use($requestData){
			$query->where('email',$requestData['email'])
				  ->orWhere('phone',$requestData['phone']);
		})->first();
			/*$parts = explode(" ", $fullname);
			$last_name = array_pop($parts);
			$first_name = implode(" ", $parts);*/

            $first_name = $fullname;
            $last_name = "";

            if( isset($fullname) && strlen($fullname) >=4 ){
                $first_name_val = trim(substr($fullname, 0, 4));
            } else {
                $first_name_val = trim($fullname);
            }
            //dd($first_name_val);
		if(empty($user)){
			$objs	= 	new Admin;
          
            // Generate client_counter and client_id
            $clientCntExist = DB::table('admins')->select('id')->where('role', 7)->count();
            if ($clientCntExist > 0) {
                $clientLatestArr = DB::table('admins')->select('client_counter')->where('role', 7)->latest()->first();
                $client_latest_counter = $clientLatestArr ? $clientLatestArr->client_counter : "00000";
            } else {
                $client_latest_counter = "00000";
            }

            $client_current_counter = $this->getNextCounter($client_latest_counter);
            $objs->client_counter =	$client_current_counter;
          
            $objs->type =	'lead';
          
			$objs->client_id =	strtoupper($first_name_val).date('his');
			$objs->role	=	7;
			$objs->last_name	=	$last_name;
			$objs->first_name	=	$first_name;
			$objs->email	=	$email;
			$objs->phone	=	$phone;
			$objs->save();
			$client_id = $objs->id;
            $client_unique_id = $objs->client_id;
		}else{
			if(empty($user->client_id)){
				Admin::where('id', $user->id)->update(['client_id' => strtoupper($first_name_val).date('his')]);
			}
			$client_id = $user->id;
            $client_unique_id = $user->client_id;
		}

		$obj = new Appointment;
        $obj->preferred_language = $preferred_language;
        $obj->inperson_address = $requestData['inperson_address'];
        $obj->client_id = $client_id;
        $obj->client_unique_id = $client_unique_id;
		$obj->service_id = $service_id;
		$obj->noe_id = $noe_id;
		$obj->full_name = $fullname;
		//$obj->title = $title;
		$obj->description = $description;
		$obj->email =	$email;
		$obj->phone = $phone;
		$obj->date = $datey;
		if($requestData['time'] != ""){
			$time = explode('-', $requestData['time']);
			//echo "@@".date("H:i", strtotime($time[0])); die;
			$obj->time = date("H:i", strtotime($time[0]));
		}
		//$obj->time = $requestData['time'];
		$obj->timeslot_full = $requestData['time'];
		$obj->invites=0;
		$obj->appointment_details = $requestData['appointment_details'];
		$saved = $obj->save();

        //Get Nature of Enquiry
        $nature_of_enquiry_data = DB::table('nature_of_enquiry')->where('id', $request->noe_id)->first();
        if($nature_of_enquiry_data){
            $nature_of_enquiry_title = $nature_of_enquiry_data->title;
        } else {
            $nature_of_enquiry_title = "";
        }

        //Get book_services
        $service_data = DB::table('book_services')->where('id', $request->service_id)->first();
        if($service_data){
            $service_title = $service_data->title;
            if( $request->service_id == 1) { //Paid
                $service_type = 'Paid';
            } else {
                $service_type = 'Free';
            }
            $service_title_text = $service_title.'-'.$service_type;
        } else {
            $service_title = "";
            $service_title_text = "";
        }

		if($saved)
        {
            $note = new \App\Note;
            $note->client_id =  $client_id;
            $note->user_id = 1;
            $note->title = $requestData['appointment_details'];
            $note->description = $description;
            $note->mail_id = 0;
            $note->type = 'client';
            $saved = $note->save();

            if( isset($service_id) && $service_id == 1 ){ //1=>Paid
                $subject = 'scheduled an paid appointment';
            } else if( isset($service_id) && $service_id == 2 ){ //2=>Free
                $subject = 'scheduled an appointment';
            }
            $objs = new \App\ActivitiesLog;
            $objs->client_id = $client_id;
            $objs->created_by = 1;
            //$objs->description = '<span class="text-semi-bold">You have an appointment on '.$requestData['date'].' at '.$requestData['time'].'</span>';

            $objs->description = '<div style="display: -webkit-inline-box;">
						<span style="height: 60px; width: 60px; border: 1px solid rgb(3, 169, 244); border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2px;overflow: hidden;">
							<span  style="flex: 1 1 0%; width: 100%; text-align: center; background: rgb(237, 237, 237); border-top-left-radius: 120px; border-top-right-radius: 120px; font-size: 12px;line-height: 24px;">
							  '.date('d M', strtotime($datey)).'
							</span>
							<span style="background: rgb(84, 178, 75); color: rgb(255, 255, 255); flex: 1 1 0%; width: 100%; border-bottom-left-radius: 120px; border-bottom-right-radius: 120px; text-align: center;font-size: 12px; line-height: 21px;">
							   '.date('Y', strtotime($datey)).'
							</span>
						</span>
					</div>
					<div style="display:inline-grid;"><span class="text-semi-bold">'.$nature_of_enquiry_title.'</span> <span class="text-semi-bold">'.$service_title_text.'</span>  <span class="text-semi-bold">'.$request->appointment_details.'</span> <span class="text-semi-bold">'.$request->fullname.'</span> <span class="text-semi-bold">'.$request->email.'</span> <span class="text-semi-bold">'.$request->phone.'</span> <span class="text-semi-bold">'.$request->description.'</span> <p class="text-semi-light-grey col-v-1">@ '.$request->time.'</p></div>';

            $objs->subject = $subject;
            $objs->save();


            if( isset($requestData['appointment_details']) && $requestData['appointment_details'] != ""){
                if( $requestData['appointment_details'] == "in_person" ){
                    $appointment_details = "In Person";
                } else if( $requestData['appointment_details'] == "phone" ){
                    $appointment_details = "Phone";
                } else if( $requestData['appointment_details'] == "zoom_google_meeting" ){
                    $appointment_details = "Zoom / Google Meeting";
                }
            } else {
                $appointment_details = "";
            }

            if(isset($requestData['inperson_address']) && $requestData['inperson_address'] != ""){
                if($requestData['inperson_address'] == 1){
                    $inperson_address = "ADELAIDE (Unit 5 5/55 Gawler Pl, Adelaide SA 5000)";
                } else if($requestData['inperson_address'] == 2){
                    $inperson_address = "MELBOURNE (Next to flight Center, Level 8/278 Collins St, Melbourne VIC 3000, Australia)";
                }
            } else {
                $inperson_address = "";
            }

            //Email To Admin
            $details1 = [
                'title' => 'You have received a new appointment from '.$fullname.' for '.$service->title,
                'body' => 'This is for testing email using smtp',
                'fullname' => 'Admin',
                'date' => $requestData['date'],
                'time' => $requestData['time'],
                'email'=> $email,
                'phone' => $phone,
                'description' => $description,
                'service'=> $service->title,
                'appointment_details'=> $appointment_details,
                'inperson_address'=> $inperson_address,
                'client_id'=> $client_id,
                'preferred_language'=> $preferred_language
            ];
		    \Mail::to('info@bansalimmigration.com.au')->send(new \App\Mail\AppointmentMail($details1));

            //Email To Customer
            $details = [
                'title' => 'You have booked an Appointment on '.$requestData['date'].'  at '.$requestData['time'],
                'body' => 'This is for testing email using smtp',
                'fullname' => $fullname,
                'date' => $requestData['date'],
                'time' => $requestData['time'],
                'email'=> $email,
                'phone' => $phone,
                'description' => $description,
                'service'=> $service->title,
                'appointment_details'=> $appointment_details,
                'inperson_address'=> $inperson_address,
                'client_id'=> $client_id,
                'preferred_language'=> $preferred_language
            ];

		    \Mail::to($email)->send(new \App\Mail\AppointmentMail($details));

            //SMS to admin
            /*$receiver_number="+610422905860";
            // $receiver_number="+61476857122"; testing number
            $smsMessage="An appointment has been booked for $fullname on ".$requestData['date'].' at '.$requestData['time'];
            Helper::sendSms($receiver_number,$smsMessage);*/

			$message = 'Your appointment booked successfully on '.$requestData['date'].' '.$requestData['time'];

            //send sms message
            $verifiedNumber = VerifiedNumber::where('phone_number',$phone)->where('is_verified', true)->first();
            if ( $verifiedNumber) {
                //$this->twilioService->sendSMS($phone,$message);
                $this->smsService->sendSms($phone,$message);
            }
			return json_encode(array('success'=>true,'message'=>$message));
		} else {
			return json_encode(array('success'=>false));
		}
	}

    /**
     * Book An Paid Appointment.
     *
     * @return \Illuminate\Http\Response
    */
    public function storepaid(Request $request)
    {
        $requestData = $request->all(); //dd($requestData);
        $preferred_language = $requestData['preferred_language'];
        $service_id = $requestData['service_id'];
		$noe_id = $requestData['noe_id'];
		$fullname = $requestData['fullname'];
        //$title = $requestData['title'];
		$description = $requestData['description'];
		$email = $requestData['email'];
		$phone = $requestData['phone'];
		$date = explode('/', $requestData['date']);
		$datey = $date[2].'-'.$date[1].'-'.$date[0];
		$service = \App\BookService::find($requestData['service_id']); //dd($service);
        if(!empty($service)){
            $amount =  str_replace("$", "", $service['price']);
        } else {
            $amount = 0;
        }  //dd($amount);

        //Order insertion
        $cardName = $requestData['cardName'];
        $stripeToken = $requestData['stripeToken'];
        $currency = "aud";
        $payment_type = "stripe";
        $order_date = date("Y-m-d H:i:s");
        $order_status = "Pending";
        $ins = DB::table('tbl_paid_appointment_payment')->insert(
            [
                'order_hash' => $stripeToken,
                'payer_email' => $email,
                'amount' => $amount,
                'currency' => $currency,
                'payment_type' => $payment_type,
                'order_date' => $order_date,
                'order_status' => $order_status,
                'name' => $cardName
            ]
        );

        //stripe payment
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer = Stripe\Customer::create(array("email" => $email,"name" => $cardName,"source" => $stripeToken));

        $payment_result = Stripe\Charge::create ([
            "amount" => $amount * 100,
            "currency" => $currency,
            "customer" => $customer->id,
            "description" => "Paid To bansalimmigration.com.au For Migration Advice By $cardName",
        ]);
        //dd($payment_result);
        //update Order status
        if ( ! empty($payment_result) && $payment_result["status"] == "succeeded") { //success
            $stripe_payment_intent_id = $payment_result['id'];
            $payment_status = "Paid";
            $order_status = "Completed";
            $appontment_status = 10; //Pending Appointment With Payment Success

            DB::table('tbl_paid_appointment_payment')
            ->where('order_hash',$stripeToken)
            ->update(
                array(
                    'stripe_payment_intent_id'=>$stripe_payment_intent_id,
                    'payment_status'=>$payment_status,
                    'order_status'=>$order_status
                )
            );
        } else { //failed
            $stripe_payment_intent_id = $payment_result['id'];
            $payment_status = "Unpaid";
            $order_status = "Payement Failure";
            $appontment_status = 11; //Pending Appointment With Payment Fail

            DB::table('tbl_paid_appointment_payment')
            ->where('order_hash',$stripeToken)
            ->update(
                array(
                    'stripe_payment_intent_id'=>$stripe_payment_intent_id,
                    'payment_status'=>$payment_status,
                    'order_status'=>$order_status
                )
            );
            return json_encode(array('success'=>false));
        }
        $user = \App\Admin::where(function ($query) use($requestData){
			$query->where('email',$requestData['email'])
			->orWhere('phone',$requestData['phone']);
		})->first();
        /*$parts = explode(" ", $fullname);
        $last_name = array_pop($parts);
        $first_name = implode(" ", $parts);*/
        $first_name = $fullname;
        $last_name = "";

        if( isset($fullname) && strlen($fullname) >=4 ){
            $first_name_val = trim(substr($fullname, 0, 4));
        } else {
            $first_name_val = trim($fullname);
        }
        //dd($first_name_val);

        if(empty($user)){
			$objs				= 	new Admin;
            // Generate client_counter and client_id
            $clientCntExist = DB::table('admins')->select('id')->where('role', 7)->count();
            if ($clientCntExist > 0) {
                $clientLatestArr = DB::table('admins')->select('client_counter')->where('role', 7)->latest()->first();
                $client_latest_counter = $clientLatestArr ? $clientLatestArr->client_counter : "00000";
            } else {
                $client_latest_counter = "00000";
            }

            $client_current_counter = $this->getNextCounter($client_latest_counter);
            $objs->client_counter =	$client_current_counter;
  			$objs->type =	'lead';
          
			$objs->client_id	=	strtoupper($first_name_val).date('his');
			$objs->role	        =	7;
			$objs->last_name	=	$last_name;
			$objs->first_name	=	$first_name;
			$objs->email	    =	$email;
			$objs->phone	    =	$phone;
			$objs->save();
			$client_id          = $objs->id;
            $client_unique_id   = $objs->client_id;
		} else {
			if(empty($user->client_id)){
				Admin::where('id', $user->id)->update(['client_id' => strtoupper($first_name_val).date('his')]);
			}
			$client_id = $user->id;
            $client_unique_id = $user->client_id;
		}

         //Get Nature of Enquiry
        $nature_of_enquiry_data = DB::table('nature_of_enquiry')->where('id', $request->noe_id)->first();
        if($nature_of_enquiry_data){
            $nature_of_enquiry_title = $nature_of_enquiry_data->title;
        } else {
            $nature_of_enquiry_title = "";
        }

        //Get book_services
        $service_data = DB::table('book_services')->where('id', $request->service_id)->first();
        if($service_data){
            $service_title = $service_data->title;
            if( $request->service_id == 1) { //Paid
                $service_type = 'Paid';
            } else {
                $service_type = 'Free';
            }
            $service_title_text = $service_title.'-'.$service_type;
        } else {
            $service_title = "";
            $service_title_text = "";
        }

		$obj = new Appointment;
         $obj->preferred_language = $preferred_language;
        $obj->inperson_address = $requestData['inperson_address'];
		$obj->client_id = $client_id;
        $obj->client_unique_id = $client_unique_id;
		$obj->service_id = $service_id;
		$obj->noe_id = $noe_id;
		$obj->full_name = $fullname;
		//$obj->title = $title;
		$obj->description = $description;
		$obj->email =	$email;
		$obj->phone = $phone;
		$obj->date = $datey;
        $obj->status = $appontment_status;
		if($requestData['time'] != ""){
			$time = explode('-', $requestData['time']);
			//echo "@@".date("H:i", strtotime($time[0])); die;
			$obj->time = date("H:i", strtotime($time[0]));
		}
		//$obj->time = $requestData['time'];
		$obj->timeslot_full = $requestData['time'];
		$obj->invites=0;
		$obj->appointment_details=$requestData['appointment_details'];
        $obj->order_hash = $stripeToken;
		$saved = $obj->save();
        if($saved)
        {
            $note = new \App\Note;
            $note->client_id =  $client_id;
            $note->user_id = 1;
            $note->title = $requestData['appointment_details'];
            $note->description = $description;
            $note->mail_id = 0;
            $note->type = 'client';
            $saved = $note->save();

            if( isset($service_id) && $service_id == 1 ){ //1=>Paid
                $subject = 'scheduled an paid appointment';
            } else if( isset($service_id) && $service_id == 2 ){ //2=>Free
                $subject = 'scheduled an appointment';
            }
            $objs = new \App\ActivitiesLog;
            $objs->client_id = $client_id;
            $objs->created_by = 1;
            /*if( isset($service_id) && $service_id == 1 ){ //1=>Paid
                $objs->description = '<span class="text-semi-bold">You have an paid appointment on '.$requestData['date'].' at '.$requestData['time'].'</span>';
            } else if( isset($service_id) && $service_id == 2 ){ //2=>Free
                $objs->description = '<span class="text-semi-bold">You have an appointment on '.$requestData['date'].' at '.$requestData['time'].'</span>';
            }*/

          	$objs->description = '<div style="display: -webkit-inline-box;">
						<span style="height: 60px; width: 60px; border: 1px solid rgb(3, 169, 244); border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2px;overflow: hidden;">
							<span  style="flex: 1 1 0%; width: 100%; text-align: center; background: rgb(237, 237, 237); border-top-left-radius: 120px; border-top-right-radius: 120px; font-size: 12px;line-height: 24px;">
							  '.date('d M', strtotime($datey)).'
							</span>
							<span style="background: rgb(84, 178, 75); color: rgb(255, 255, 255); flex: 1 1 0%; width: 100%; border-bottom-left-radius: 120px; border-bottom-right-radius: 120px; text-align: center;font-size: 12px; line-height: 21px;">
							   '.date('Y', strtotime($datey)).'
							</span>
						</span>
					</div>
					<div style="display:inline-grid;"><span class="text-semi-bold">'.$nature_of_enquiry_title.'</span> <span class="text-semi-bold">'.$service_title_text.'</span>  <span class="text-semi-bold">'.$request->appointment_details.'</span> <span class="text-semi-bold">'.$request->fullname.'</span> <span class="text-semi-bold">'.$request->email.'</span> <span class="text-semi-bold">'.$request->phone.'</span> <span class="text-semi-bold">'.$request->description.'</span> <p class="text-semi-light-grey col-v-1">@ '.$request->time.'</p></div>';

            $objs->subject = $subject;
            $objs->save();

            if( isset($requestData['appointment_details']) && $requestData['appointment_details'] != ""){
                if( $requestData['appointment_details'] == "in_person" ){
                    $appointment_details = "In Person";
                } else if( $requestData['appointment_details'] == "phone" ){
                    $appointment_details = "Phone";
                } else if( $requestData['appointment_details'] == "zoom_google_meeting" ){
                    $appointment_details = "Zoom / Google Meeting";
                }
            } else {
                $appointment_details = "";
            }

            if(isset($requestData['inperson_address']) && $requestData['inperson_address'] != ""){
                if($requestData['inperson_address'] == 1){
                    $inperson_address = "ADELAIDE (Unit 5 5/55 Gawler Pl, Adelaide SA 5000)";
                } else if($requestData['inperson_address'] == 2){
                    $inperson_address = "MELBOURNE (Next to flight Center, Level 8/278 Collins St, Melbourne VIC 3000, Australia)";
                }
            } else {
                $inperson_address = "";
            }

            //Email To Admin
            $details1 = [
                'title' => 'You have received a new appointment from '.$fullname.' for '.$service->title,
                'body' => 'This is for testing email using smtp',
                'fullname' => 'Admin',
                'date' => $requestData['date'],
                'time' => $requestData['time'],
                'email'=> $email,
                'phone' => $phone,
                'description' => $description,
                'service'=> $service->title,
                 'appointment_details'=> $appointment_details,
                'inperson_address'=> $inperson_address,
                'client_id'=> $client_id,
                'preferred_language'=> $preferred_language
            ];
		    \Mail::to('info@bansalimmigration.com.au')->send(new \App\Mail\AppointmentMail($details1));

            //Email To customer
            $details = [
                'title' => 'You have booked an Appointment on '.$requestData['date'].'  at '.$requestData['time'],
                'body' => 'This is for testing email using smtp',
                'fullname' => $fullname,
                'date' => $requestData['date'],
                'time' => $requestData['time'],
                'email'=> $email,
                'phone' => $phone,
                'description' => $description,
                'service'=> $service->title,
                 'appointment_details'=> $appointment_details,
                'inperson_address'=> $inperson_address,
                'client_id'=> $client_id,
                'preferred_language'=> $preferred_language
            ];

            \Mail::to($email)->send(new \App\Mail\AppointmentMail($details));

            //SMS to admin
            /*$receiver_number="+610422905860";
            // $receiver_number="+61476857122"; testing number
            $smsMessage="An appointment has been booked for $fullname on ".$requestData['date'].' at '.$requestData['time'];
            Helper::sendSms($receiver_number,$smsMessage);*/

			$message = 'Your appointment booked successfully on '.$requestData['date'].' '.$requestData['time'];

            //send sms message
            $verifiedNumber = VerifiedNumber::where('phone_number',$phone)->where('is_verified', true)->first();
            if ( $verifiedNumber) {
                //$this->twilioService->sendSMS($phone,$message);
                $this->smsService->sendSms($phone,$message);
            }
			return json_encode(array('success'=>true,'message'=>$message));

		} else {
			return json_encode(array('success'=>false));
		}
	}
	
}
