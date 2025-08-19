<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Admin;
use App\ServiceAccount;
use Validator;

class ServiceAccountController extends BaseController
{
    /**
     * Generate a permanent service token for external applications
     * This should be called once to create a permanent token
     */
    public function generateServiceToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'admin_email' => 'required|email',
            'admin_password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Verify admin credentials
        $admin = Admin::where('email', $request->admin_email)->first();
        
        if (!$admin || !Hash::check($request->admin_password, $admin->password)) {
            return $this->sendError('Authentication Error.', ['message' => 'Invalid admin credentials']);
        }

        // Create service account
        $serviceAccount = ServiceAccount::create([
            'service_name' => $request->service_name,
            'description' => $request->description,
            'admin_id' => $admin->id,
            'token' => \Illuminate\Support\Str::random(64),
            'is_active' => true,
            'last_used_at' => now()
        ]);

        $success['service_account'] = [
            'id' => $serviceAccount->id,
            'service_name' => $serviceAccount->service_name,
            'token' => $serviceAccount->token,
            'created_at' => $serviceAccount->created_at
        ];

        return $this->sendResponse($success, 'Service account created successfully. Store this token securely.');
    }

    /**
     * Authenticate using service token
     */
    public function authenticateWithServiceToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $serviceAccount = ServiceAccount::where('token', $request->service_token)
            ->where('is_active', true)
            ->first();

        if (!$serviceAccount) {
            return $this->sendError('Authentication Error.', ['message' => 'Invalid or inactive service token']);
        }

        // Update last used timestamp
        $serviceAccount->update(['last_used_at' => now()]);

        // Get the admin user
        $admin = $serviceAccount->admin;

        // Create a temporary Sanctum token for this session
        $token = $admin->createToken('service-account-' . $serviceAccount->id)->plainTextToken;

        $success['user_data'] = $admin;
        $success['token'] = $token;
        $success['token_type'] = 'Bearer';
        $success['service_account'] = [
            'service_name' => $serviceAccount->service_name,
            'description' => $serviceAccount->description
        ];

        return $this->sendResponse($success, 'Service account authenticated successfully.');
    }

    /**
     * List all service accounts (admin only)
     */
    public function listServiceAccounts(Request $request)
    {
        $admin = $request->user();
        
        if (!$admin) {
            return $this->sendError('Authentication Error.', ['message' => 'Unauthorized']);
        }

        $serviceAccounts = ServiceAccount::where('admin_id', $admin->id)
            ->select('id', 'service_name', 'description', 'is_active', 'created_at', 'last_used_at')
            ->get();

        return $this->sendResponse($serviceAccounts, 'Service accounts retrieved successfully.');
    }

    /**
     * Deactivate a service account
     */
    public function deactivateServiceAccount(Request $request, $id)
    {
        $admin = $request->user();
        
        if (!$admin) {
            return $this->sendError('Authentication Error.', ['message' => 'Unauthorized']);
        }

        $serviceAccount = ServiceAccount::where('id', $id)
            ->where('admin_id', $admin->id)
            ->first();

        if (!$serviceAccount) {
            return $this->sendError('Error.', ['message' => 'Service account not found']);
        }

        $serviceAccount->update(['is_active' => false]);

        return $this->sendResponse([], 'Service account deactivated successfully.');
    }
} 