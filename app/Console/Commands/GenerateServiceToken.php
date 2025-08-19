<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GenerateServiceToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:generate-token 
                            {--service-name=My Other Website : Name of the service}
                            {--description=Integration for external Laravel website : Description of the service}
                            {--email= : Admin email (required)}
                            {--password= : Admin password (required)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a service token for external appointment API access';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if required options are provided
        if (!$this->option('email') || !$this->option('password')) {
            $this->error('❌ Email and password are required!');
            $this->line('Usage: php artisan appointment:generate-token --email=your@email.com --password=yourpassword');
            $this->line('Example: php artisan appointment:generate-token --email=admin1@gmail.com --password=123456 --service-name="My Website"');
            return 1;
        }

        $this->info('🔑 Generating Service Token...');
        $this->line('Email: ' . $this->option('email'));
        $this->line('Service Name: ' . $this->option('service-name'));
        $this->line('Description: ' . $this->option('description'));
        $this->newLine();
        
        try {
            $response = Http::timeout(30)->post(config('app.url') . '/api/service-account/generate-token', [
                'service_name' => $this->option('service-name'),
                'description' => $this->option('description'),
                'admin_email' => $this->option('email'),
                'admin_password' => $this->option('password')
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success']) {
                    $this->info('✅ Service token generated successfully!');
                    $this->newLine();
                    
                    $this->table(
                        ['Field', 'Value'],
                        [
                            ['Service Name', $data['data']['service_account']['service_name']],
                            ['Token', $data['data']['service_account']['token']],
                            ['Created', $data['data']['service_account']['created_at']],
                        ]
                    );
                    
                    $this->newLine();
                    $this->warn('⚠️  IMPORTANT: Save this token securely in your other Laravel project\'s .env file:');
                    $this->line('APPOINTMENT_API_SERVICE_TOKEN=' . $data['data']['service_account']['token']);
                    
                } else {
                    $this->error('❌ Error: ' . ($data['message'] ?? 'Unknown error'));
                }
            } else {
                $this->error('❌ HTTP Error: ' . $response->status());
                $this->error('Response: ' . $response->body());
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
        }
    }
} 