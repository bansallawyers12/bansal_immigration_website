<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Contact;
use App\Enquiry;
use App\Mail\ContactForwardMail;
use Carbon\Carbon;

class ContactManagementController extends Controller
{
    /**
     * Display a listing of contact queries
     */
    public function index(Request $request)
    {
        $query = Contact::query();
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('contact_email', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('form_source')) {
            $query->where('form_source', $request->form_source);
        }
        
        // Sort by latest first
        $contacts = $query->orderBy('created_at', 'desc')
                         ->paginate(20)
                         ->appends($request->all());
        
        // Get statistics
        $stats = [
            'total' => Contact::count(),
            'unread' => Contact::where('status', 'unread')->count(),
            'read' => Contact::where('status', 'read')->count(),
            'resolved' => Contact::where('status', 'resolved')->count(),
            'archived' => Contact::where('status', 'archived')->count(),
            'today' => Contact::whereDate('created_at', today())->count(),
            'this_week' => Contact::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Contact::whereMonth('created_at', now()->month)->count(),
        ];
        
        // Get unique form sources for filter
        $formSources = Contact::whereNotNull('form_source')
                             ->distinct()
                             ->pluck('form_source')
                             ->filter()
                             ->sort();
        
        return view('admin.contact-management.index', compact('contacts', 'stats', 'formSources'));
    }
    
    /**
     * Display the specified contact query
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        
        // Mark as read if it's unread
        if ($contact->status === 'unread') {
            $contact->update(['status' => 'read']);
        }
        
        // Get related enquiry record
        $enquiry = Enquiry::where('email', $contact->contact_email)
                          ->where('created_at', $contact->created_at)
                          ->first();
        
        return view('admin.contact-management.show', compact('contact', 'enquiry'));
    }
    
    /**
     * Update the status of a contact query
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:unread,read,resolved,archived'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status value'
            ], 422);
        }
        
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => $request->status]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }
    
    /**
     * Forward a contact query to an email address
     */
    public function forward(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'forward_to' => 'required|email|max:255',
            'forward_message' => 'nullable|string|max:1000',
            'include_original' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return back()->withErrors($validator)->withInput();
        }
        
        $contact = Contact::findOrFail($id);
        
        try {
            // Prepare email data
            $emailData = [
                'contact' => $contact,
                'forward_message' => $request->forward_message,
                'include_original' => $request->boolean('include_original', true),
                'forwarded_by' => auth()->user()->name ?? 'Admin',
                'forwarded_at' => now()
            ];
            
            // Send forwarded email
            Mail::to($request->forward_to)->send(new ContactForwardMail($emailData));
            
            // Update contact record
            $contact->update([
                'forwarded_to' => $request->forward_to,
                'forwarded_at' => now(),
                'status' => $contact->status === 'unread' ? 'read' : $contact->status
            ]);
            
            $message = "Query forwarded successfully to {$request->forward_to}";
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Contact forward error: ' . $e->getMessage());
            
            $errorMessage = 'Failed to forward the query. Please try again.';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return back()->with('error', $errorMessage);
        }
    }
    
    /**
     * Delete a contact query
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        
        // Also delete related enquiry if exists
        Enquiry::where('email', $contact->contact_email)
                ->where('created_at', $contact->created_at)
                ->delete();
        
        $contact->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Query deleted successfully'
        ]);
    }
    
    /**
     * Bulk actions for contact queries
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:delete,mark_read,mark_resolved,mark_archived',
            'contact_ids' => 'required|array|min:1',
            'contact_ids.*' => 'exists:contacts,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $contactIds = $request->contact_ids;
        $action = $request->action;
        $count = count($contactIds);
        
        try {
            switch ($action) {
                case 'delete':
                    // Delete related enquiries first
                    $contacts = Contact::whereIn('id', $contactIds)->get();
                    foreach ($contacts as $contact) {
                        Enquiry::where('email', $contact->contact_email)
                               ->where('created_at', $contact->created_at)
                               ->delete();
                    }
                    
                    Contact::whereIn('id', $contactIds)->delete();
                    $message = "{$count} queries deleted successfully";
                    break;
                    
                case 'mark_read':
                    Contact::whereIn('id', $contactIds)->update(['status' => 'read']);
                    $message = "{$count} queries marked as read";
                    break;
                    
                case 'mark_resolved':
                    Contact::whereIn('id', $contactIds)->update(['status' => 'resolved']);
                    $message = "{$count} queries marked as resolved";
                    break;
                    
                case 'mark_archived':
                    Contact::whereIn('id', $contactIds)->update(['status' => 'archived']);
                    $message = "{$count} queries archived";
                    break;
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Bulk action error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Export contact queries to CSV
     */
    public function export(Request $request)
    {
        $query = Contact::query();
        
        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('contact_email', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }
        
        $contacts = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'contact_queries_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($contacts) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 
                'Status', 'Form Source', 'Form Variant', 'IP Address',
                'Forwarded To', 'Forwarded At', 'Created At'
            ]);
            
            // Add data rows
            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->id,
                    $contact->name,
                    $contact->contact_email,
                    $contact->contact_phone,
                    $contact->subject,
                    $contact->message,
                    $contact->status,
                    $contact->form_source,
                    $contact->form_variant,
                    $contact->ip_address,
                    $contact->forwarded_to,
                    $contact->forwarded_at ? $contact->forwarded_at->format('Y-m-d H:i:s') : '',
                    $contact->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Get dashboard statistics
     */
    public function dashboard()
    {
        $stats = [
            'total_queries' => Contact::count(),
            'unread_queries' => Contact::where('status', 'unread')->count(),
            'queries_today' => Contact::whereDate('created_at', today())->count(),
            'queries_this_week' => Contact::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'queries_this_month' => Contact::whereMonth('created_at', now()->month)->count(),
            'forwarded_queries' => Contact::whereNotNull('forwarded_to')->count(),
        ];
        
        // Recent queries
        $recentQueries = Contact::orderBy('created_at', 'desc')
                               ->limit(10)
                               ->get();
        
        // Queries by status
        $statusStats = Contact::selectRaw('status, COUNT(*) as count')
                             ->groupBy('status')
                             ->pluck('count', 'status')
                             ->toArray();
        
        // Queries by form source
        $sourceStats = Contact::whereNotNull('form_source')
                             ->selectRaw('form_source, COUNT(*) as count')
                             ->groupBy('form_source')
                             ->pluck('count', 'form_source')
                             ->toArray();
        
        return view('admin.contact-management.dashboard', compact(
            'stats', 'recentQueries', 'statusStats', 'sourceStats'
        ));
    }
}
