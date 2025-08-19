<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ServiceTokenController extends Controller
{
    /**
     * Show the token generation form
     */
    public function showGenerateForm()
    {
        return view('admin.generate-token');
    }

    /**
     * Generate service token
     */
    public function generateToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_email' => 'required|email',
            'admin_password' => 'required|string',
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $response = Http::timeout(30)->post(config('app.url') . '/api/service-account/generate-token', [
                'admin_email' => $request->admin_email,
                'admin_password' => $request->admin_password,
                'service_name' => $request->service_name,
                'description' => $request->description
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success']) {
                    return back()->with([
                        'success' => true,
                        'service_name' => $data['data']['service_account']['service_name'],
                        'token' => $data['data']['service_account']['token'],
                        'created_at' => $data['data']['service_account']['created_at']
                    ]);
                } else {
                    return back()->with('error', $data['message'] ?? 'Failed to generate token');
                }
            } else {
                return back()->with('error', 'HTTP Error: ' . $response->status());
            }
            
        } catch (\Exception $e) {
            return back()->with('error', 'Exception: ' . $e->getMessage());
        }
    }
} 