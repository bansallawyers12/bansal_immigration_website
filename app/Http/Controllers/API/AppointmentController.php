<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AppointmentController extends BaseController
{
    /**
     * Display a listing of appointments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $query = Appointment::query();

            // Search functionality
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhere('client_unique_id', 'like', "%{$search}%")
                      ->orWhere('full_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filter by appointment_date_field (dd/mm/yyyy format)
            if ($request->has('appointment_date_field') && !empty($request->appointment_date_field)) {
                try {
                    // Convert dd/mm/yyyy to yyyy-mm-dd format
                    $date = \DateTime::createFromFormat('d/m/Y', $request->appointment_date_field);
                    if ($date) {
                        $formattedDate = $date->format('Y-m-d');
                        $query->whereDate('date', $formattedDate);
                    }
                } catch (\Exception $e) {
                    // If date format is invalid, ignore the filter
                    \Log::warning('Invalid appointment_date_field format', [
                        'date' => $request->appointment_date_field,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Filter by other_field (client_reference or description)
            if ($request->has('other_field') && !empty($request->other_field)) {
                $otherField = $request->other_field;
                $query->where(function($q) use ($otherField) {
                    $q->where('client_unique_id', 'like', "%{$otherField}%")
                      ->orWhere('description', 'like', "%{$otherField}%");
                });
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }

            // Filter by client
            if ($request->has('client_id')) {
                $query->where('client_id', $request->client_id);
            }

            // Filter by service
            if ($request->has('service_id')) {
                $query->where('service_id', $request->service_id);
            }

            // Include relationships
            $query->with(['user', 'clients', 'service', 'natureOfEnquiry', 'assignee_user']);

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 20);
            $appointments = $query->paginate($perPage);
            //dd($appointments);

            // Get pagination metadata
            $pagination = [
                'current_page' => $appointments->currentPage(),
                'last_page' => $appointments->lastPage(),
                'per_page' => $appointments->perPage(),
                'total' => $appointments->total(),
                'from' => $appointments->firstItem(),
                'to' => $appointments->lastItem(),
                'has_more_pages' => $appointments->hasMorePages(),
                'next_page_url' => $appointments->nextPageUrl(),
                'prev_page_url' => $appointments->previousPageUrl(),
            ];

            return $this->sendResponse([
                'data' => AppointmentResource::collection($appointments),
                'pagination' => $pagination
            ], 'Appointments retrieved successfully.');

        } catch (\Exception $e) {
            return $this->sendError('Error retrieving appointments.', $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppointmentRequest $request)
    {
        try {
            DB::beginTransaction();

            $appointment = Appointment::create($request->validated());

            // Load relationships for response
            $appointment->load(['user', 'clients', 'service', 'natureOfEnquiry', 'assignee_user']);

            DB::commit();

            return $this->sendResponse(
                new AppointmentResource($appointment),
                'Appointment created successfully.'
            );

        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Error creating appointment.', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $appointment = Appointment::with(['user', 'clients', 'service', 'natureOfEnquiry', 'assignee_user'])->find($id);
            //dd($appointment);

            if (is_null($appointment)) {
                return $this->sendError('Appointment not found.');
            }

            return $this->sendResponse(
                new AppointmentResource($appointment),
                'Appointment retrieved successfully.'
            );

        } catch (\Exception $e) {
            return $this->sendError('Error retrieving appointment.', $e->getMessage(), 500);
        }
    }


      //Appointment calendar
     public function calendar(Request $request)
    {
        $type = $request->query('type'); //dd(Carbon::today());
        
        if (!$type) {
            return $this->sendError('Type parameter is required.', [], 400);
        }
        
        try {

            if($type == "Adelaide"){ //Location - ADELAIDE (Type - Free Or Paid)
                $appointments = Appointment::with(['user', 'clients', 'service', 'natureOfEnquiry', 'assignee_user'])
                  ->where('inperson_address','=', 1)
                  ->whereDate('date', '>=', Carbon::today())
                  ->get();
            } 
            else if($type == "Tourist") { //(Melbourne Address) 4=>Tourist Visa and 2=>Free
                $appointments = Appointment::with(['user', 'clients', 'service', 'natureOfEnquiry', 'assignee_user'])
                    ->where(function ($query) {
                    $query->whereNull('inperson_address')
                        ->orWhere('inperson_address', '')
                        ->orWhere('inperson_address', 2); //For Melbourne
                    })
                    ->where('noe_id','=', 4)
                  ->where('service_id','=', 2)
                  ->whereDate('date', '>=', Carbon::today())
                  ->get();
            } 
            else if($type == "Education"){ //(Melbourne Address) 5=>Education/Course Change/Student Visa/Student Depen and 2=>Free
                $appointments = Appointment::with(['user', 'clients', 'service', 'natureOfEnquiry', 'assignee_user'])
                ->where(function ($query) {
                     $query->whereNull('inperson_address')
                         ->orWhere('inperson_address', '')
                         ->orWhere('inperson_address', 2); //For Melbourne
                     })
                  ->where('noe_id','=', 5)
                  ->where('service_id','=', 2)
                  ->whereDate('date', '>=', Carbon::today())
                  ->get();
            }
            else if($type=="Jrp"){ //shubam/Yadwinder (Melbourne Address)
                //2- Temporary Residency Appointment and service_id = 2=>Free
                //3- JRP/Skill Assessment and service_id = 2=>Free
                $appointments = Appointment::with(['user', 'clients', 'service', 'natureOfEnquiry', 'assignee_user'])
                    ->where(function ($query) {
                    $query->whereNull('inperson_address')
                        ->orWhere('inperson_address', '')
                        ->orWhere('inperson_address', 2); //For Melbourne
                    })->whereIn('noe_id', [2,3])
                  ->where('service_id','=', 2)
                  ->whereDate('date', '>=', Carbon::today())
                  ->get();
            } 
            else if( $type == "Others" ){ //Arun (Melbourne Address)
                $appointments = Appointment::with(['user', 'clients', 'service', 'natureOfEnquiry', 'assignee_user'])
                    ->where(function ($query) {
                    $query->whereNull('inperson_address')
                        ->orWhere('inperson_address', '')
                        ->orWhere('inperson_address', 2); //For Melbourne
                    })->where(function ($query) {
                        $query->where(function ($q) {
                            $q->whereIn('noe_id', [1, 2, 3, 4, 5, 6, 7, 8])
                                ->where('service_id', 1);
                        })
                        ->orWhere(function ($q) {
                            $q->whereIn('noe_id', [1, 6, 7])
                                ->where('service_id', 2);
                        });
                    })
                    ->whereDate('date', '>=', Carbon::today())
                    ->get();
            }
            

            return $this->sendResponse(
                AppointmentResource::collection($appointments),
                'Calendar appointments retrieved successfully.'
            );

        } catch (\Exception $e) {
            return $this->sendError('Error retrieving calendar appointments.', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AppointmentRequest $request, $id)
    {
        \Log::info('Update method called', [
            'id' => $id,
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        try {
            // Test database connection
            try {
                $appointment = Appointment::find($id);
            } catch (\Exception $dbError) {
                \Log::error('Database connection error', [
                ]);
                return $this->sendError('Database connection error.', $dbError->getMessage(), 500);
            }

            if (is_null($appointment)) {
                return $this->sendError('Appointment not found.');
            }

            DB::beginTransaction();

            // Get all request data - handle both JSON and form-data
            $requestData = $request->all();

            // If request data is empty, try alternative methods
            if (empty($requestData)) {
                $requestData = $request->input();
            }

            // If still empty, try getting raw content
            if (empty($requestData)) {
                $rawContent = $request->getContent();
                if (!empty($rawContent)) {
                    $requestData = json_decode($rawContent, true) ?: [];
                }
            }

            // Debug the request data
            /*dd([
                'requestData' => $requestData,
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'is_json' => $request->isJson(),
                'raw_content' => $request->getContent(),
                'input' => $request->input(),
                'all' => $request->all()
            ]);*/

            // Debug request information
            \Log::info('Request debugging info', [
                'method' => $request->method(),
                'url' => $request->url(),
                'headers' => $request->headers->all(),
                'content_type' => $request->header('Content-Type'),
                'request_data' => $requestData,
                'input' => $request->input(),
                'json' => $request->json(),
                'body' => $request->getContent(),
                'has_json' => $request->isJson(),
                'has_form_data' => $request->hasFile('file')
            ]);

            // Get validated data with error handling
            try {
                $validatedData = $request->validated(); //dd( $validatedData);
                \Log::info('Validation successful', [
                    'validated_data' => $validatedData
                ]);
            } catch (ValidationException $e) {
                \Log::warning('Validation failed', [
                    'request_data' => $requestData,
                    'validation_errors' => $e->errors()
                ]);

                // Try to get data directly from request
                $validatedData = $request->only([
                    'description', 'status', 'full_name', 'email', 'date', 'time', 'title'
                ]);

                \Log::info('Using direct request data after validation failure', [
                    'direct_data' => $validatedData
                ]);
            }

            // If still no data, use direct request data
            if (empty($validatedData)) {
                \Log::warning('No validated data received, using direct request data', [
                    'request_data' => $requestData
                ]);

                $validatedData = $request->only([
                    'description', 'status', 'full_name', 'email', 'date', 'time', 'title'
                ]);
            }

            // Log the data being updated for debugging
            \Log::info('Updating appointment', [
                'appointment_id' => $id,
                'request_data' => $requestData,
                'validated_data' => $validatedData,
                'original_data' => $appointment->toArray(),
                'content_type' => $request->header('Content-Type')
            ]);

            // Manually update each field to ensure it works
            foreach ($validatedData as $field => $value) {
                if ($appointment->isFillable($field)) {
                    $appointment->$field = $value;
                    \Log::info("Setting field: {$field} = {$value}");
                } else {
                    \Log::warning("Field {$field} is not fillable");
                }
            }

            // Save the appointment
            $saved = $appointment->save();

            if (!$saved) {
                throw new \Exception('Failed to save appointment');
            }

            // Refresh the model to get updated data
            $appointment->refresh();

            // Load relationships for response
            $appointment->load(['user', 'clients', 'service', 'natureOfEnquiry', 'assignee_user']);

            DB::commit();

            \Log::info('Appointment updated successfully', [
                'appointment_id' => $id,
                'updated_data' => $appointment->toArray()
            ]);

            return $this->sendResponse(
                new AppointmentResource($appointment),
                'Appointment updated successfully.'
            );

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error updating appointment', [
                'appointment_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->sendError('Error updating appointment.', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified appointment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $appointment = Appointment::find($id);

            if (is_null($appointment)) {
                return $this->sendError('Appointment not found.');
            }

            $appointment->delete();

            return $this->sendResponse([], 'Appointment deleted successfully.');

        } catch (\Exception $e) {
            return $this->sendError('Error deleting appointment.', $e->getMessage(), 500);
        }
    }

    /**
     * Get appointment statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        try {
            $stats = [
                'total' => Appointment::count(),
                'pending' => Appointment::where('status', 'pending')->count(),
                'confirmed' => Appointment::where('status', 'confirmed')->count(),
                'cancelled' => Appointment::where('status', 'cancelled')->count(),
                'completed' => Appointment::where('status', 'completed')->count(),
                'rescheduled' => Appointment::where('status', 'rescheduled')->count(),
                'today' => Appointment::whereDate('date', Carbon::today())->count(),
                'this_week' => Appointment::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
                'this_month' => Appointment::whereMonth('date', Carbon::now()->month)->count(),
            ];

            return $this->sendResponse($stats, 'Appointment statistics retrieved successfully.');

        } catch (\Exception $e) {
            return $this->sendError('Error retrieving statistics.', $e->getMessage(), 500);
        }
    }

    /**
     * Bulk update appointment status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'appointment_ids' => 'required|array',
                'appointment_ids.*' => 'exists:appointments,id',
                'status' => 'required|string|in:pending,confirmed,cancelled,completed,rescheduled',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }

            DB::beginTransaction();

            $updated = Appointment::whereIn('id', $request->appointment_ids)
                                ->update(['status' => $request->status]);

            DB::commit();

            return $this->sendResponse(
                ['updated_count' => $updated],
                'Appointments status updated successfully.'
            );

        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Error updating appointments status.', $e->getMessage(), 500);
        }
    }

    /**
     * Get appointments by date range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByDateRange(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }

            $appointments = Appointment::with(['user', 'clients', 'service', 'natureOfEnquiry', 'assignee_user'])
                                    ->whereBetween('date', [$request->start_date, $request->end_date])
                                    ->orderBy('date', 'asc')
                                    ->orderBy('time', 'asc')
                                    ->get();

            return $this->sendResponse(
                AppointmentResource::collection($appointments),
                'Appointments retrieved successfully.'
            );

        } catch (\Exception $e) {
            return $this->sendError('Error retrieving appointments.', $e->getMessage(), 500);
        }
    }

    /**
     * Test update method for debugging.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function testUpdate(Request $request, $id)
    {
        \Log::info('Test update method called', [
            'id' => $id,
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        try {
            $appointment = Appointment::find($id);

            if (is_null($appointment)) {
                return $this->sendError('Appointment not found.');
            }

            // Get all request data with detailed debugging
            $requestData = $request->all();

            \Log::info('Test update - Detailed request info', [
                'appointment_id' => $id,
                'request_data' => $requestData,
                'input' => $request->input(),
                'json' => $request->json(),
                'body' => $request->getContent(),
                'content_type' => $request->header('Content-Type'),
                'headers' => $request->headers->all(),
                'original_appointment' => $appointment->toArray()
            ]);

            // Update specific fields
            if (isset($requestData['description'])) {
                $appointment->description = $requestData['description'];
                \Log::info("Updated description: {$requestData['description']}");
            }
            if (isset($requestData['status'])) {
                $appointment->status = $requestData['status'];
                \Log::info("Updated status: {$requestData['status']}");
            }
            if (isset($requestData['full_name'])) {
                $appointment->full_name = $requestData['full_name'];
                \Log::info("Updated full_name: {$requestData['full_name']}");
            }

            $saved = $appointment->save();

            if (!$saved) {
                throw new \Exception('Failed to save appointment');
            }

            $appointment->refresh();

            \Log::info('Test update - Success', [
                'appointment_id' => $id,
                'updated_appointment' => $appointment->toArray()
            ]);

            return $this->sendResponse(
                new AppointmentResource($appointment),
                'Test update completed successfully.'
            );

        } catch (\Exception $e) {
            \Log::error('Test update - Error', [
                'appointment_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->sendError('Error in test update.', $e->getMessage(), 500);
        }
    }

    /**
     * Raw test update method for debugging request data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rawTestUpdate(Request $request, $id)
    {
        \Log::info('Raw test update method called', [
            'id' => $id,
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        try {
            // Get all possible request data
            $allData = [
                'all()' => $request->all(),
                'input()' => $request->input(),
                'json()' => $request->json(),
                'getContent()' => $request->getContent(),
                'headers' => $request->headers->all(),
                'content_type' => $request->header('Content-Type'),
                'isJson()' => $request->isJson(),
                'hasFile()' => $request->hasFile('file'),
                'method()' => $request->method(),
                'url()' => $request->url()
            ];

            \Log::info('Raw test - All request data', $allData);

            return $this->sendResponse($allData, 'Raw test completed. Check logs for details.');

        } catch (\Exception $e) {
            \Log::error('Raw test - Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->sendError('Error in raw test.', $e->getMessage(), 500);
        }
    }
}
