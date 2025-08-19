<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'client_id' => $this->client_id,
            'client_unique_id' => $this->client_unique_id,
            'timezone' => $this->timezone,
            'email' => $this->email,
            'noe_id' => $this->noe_id,
            'service_id' => $this->service_id,
            'assignee' => $this->assignee,
            'full_name' => $this->full_name,
            'date' => $this->date,
            'time' => $this->time,
            'title' => $this->title,
            'description' => $this->description,
            'invites' => $this->invites,
            'status' => $this->status,
            'related_to' => $this->related_to,
            'preferred_language' => $this->preferred_language,
            'inperson_address' => $this->inperson_address,
            'timeslot_full' => $this->timeslot_full,
            'appointment_details' => $this->appointment_details,
            'order_hash' => $this->order_hash,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relationships
            'client' => $this->whenLoaded('clients', function() {
                return [
                    'id' => $this->clients->id,
                    'name' => $this->clients->first_name && $this->clients->last_name 
                        ? trim($this->clients->first_name . ' ' . $this->clients->last_name)
                        : ($this->clients->first_name ?: $this->clients->last_name ?: null),
                    'email' => $this->clients->email ?? null,
                ];
            }),
            
            'user' => $this->whenLoaded('user', function() {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->first_name && $this->user->last_name 
                        ? trim($this->user->first_name . ' ' . $this->user->last_name)
                        : ($this->user->first_name ?: $this->user->last_name ?: null),
                    'email' => $this->user->email ?? null,
                ];
            }),
            
            'assignee_user' => $this->whenLoaded('assignee_user', function() {
                return [
                    'id' => $this->assignee_user->id,
                    'name' => $this->assignee_user->first_name && $this->assignee_user->last_name 
                        ? trim($this->assignee_user->first_name . ' ' . $this->assignee_user->last_name)
                        : ($this->assignee_user->first_name ?: $this->assignee_user->last_name ?: null),
                    'email' => $this->assignee_user->email ?? null,
                ];
            }),
            
            'service' => $this->whenLoaded('service', function() {
                return [
                    'id' => $this->service->id,
                    'title' => $this->service->title ?? null,
					'price' => $this->service->price ?? null,
                ];
            }),
            
            'nature_of_enquiry' => $this->whenLoaded('natureOfEnquiry', function() {
                return [
                    'id' => $this->natureOfEnquiry->id,
                    'title' => $this->natureOfEnquiry->title ?? null,
                ];
            }),
        ];
    }
}
