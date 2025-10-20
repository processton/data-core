<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:generate 
                            {--role=user : The role for the token (user, admin)} 
                            {--expires=3600 : Token expiration time in seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a development access token for API testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $role = $this->option('role');
        $expires = $this->option('expires');
        
        // Generate a random token
        $token = base64_encode(Str::random(64));
        
        // Create token payload
        $payload = [
            'token' => $token,
            'role' => $role,
            'expires_at' => now()->addSeconds($expires)->toIso8601String(),
            'created_at' => now()->toIso8601String(),
        ];
        
        $this->info('Development Access Token Generated');
        $this->newLine();
        $this->line('Token: ' . $token);
        $this->line('Role: ' . $role);
        $this->line('Expires: ' . $payload['expires_at']);
        $this->newLine();
        $this->warn('⚠️  This token is for DEVELOPMENT USE ONLY');
        $this->warn('⚠️  In production, use Keycloak for authentication');
        $this->newLine();
        $this->info('Use this token in your API requests:');
        $this->line('Authorization: Bearer ' . $token);
        
        return Command::SUCCESS;
    }
}
