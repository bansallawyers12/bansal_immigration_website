<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Base rules for all operations
        $baseRules = [
            'user_id' => 'nullable|exists:admins,id',
            'client_id' => 'nullable|exists:admins,id',
            'client_unique_id' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'noe_id' => 'nullable|exists:nature_of_enquiry,id',
            'service_id' => 'nullable|exists:book_services,id',
            'assignee' => 'nullable|exists:admins,id',
            'full_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'invites' => 'nullable|integer|min:0',
            'related_to' => 'nullable|string|max:255',
            'preferred_language' => 'nullable|string|max:100',
            'inperson_address' => 'nullable|string',
            'timeslot_full' => 'nullable|string',
            'order_hash' => 'nullable|string|max:255',
        ];

        // Rules for create operation
        if ($this->isMethod('POST')) {
            return array_merge($baseRules, [
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'description' => 'required|string',
                'status' => 'required|in:pending,confirmed,cancelled,completed,rescheduled,1,2,3,4,5',
                'appointment_details' => 'required|string',
            ]);
        }

        // Rules for update operation
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return array_merge($baseRules, [
                'date' => 'nullable|date',
                'time' => 'nullable|date_format:H:i',
                'description' => 'nullable|string',
                'status' => 'nullable|in:pending,confirmed,cancelled,completed,rescheduled,1,2,3,4,5',
                'appointment_details' => 'nullable|string',
            ]);
        }

        return $baseRules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'date.required' => 'The appointment date is required.',
            'time.required' => 'The appointment time is required.',
            'time.date_format' => 'The time must be in HH:MM format.',
            'description.required' => 'The appointment description is required.',
            'status.required' => 'The appointment status is required.',
            'status.in' => 'The status must be one of: pending, confirmed, cancelled, completed, rescheduled, 1, 2, 3, 4, 5.',
            'appointment_details.required' => 'The appointment details are required.',
            'email.email' => 'Please provide a valid email address.',
            'user_id.exists' => 'The selected user does not exist.',
            'client_id.exists' => 'The selected client does not exist.',
            'noe_id.exists' => 'The selected nature of enquiry does not exist.',
            'service_id.exists' => 'The selected service does not exist.',
            'assignee.exists' => 'The selected assignee does not exist.',
        ];
    }
}
