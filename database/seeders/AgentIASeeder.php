<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentIASeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        // vérifier si l'agent existe déjà
        $agent = User::where('email', 'agentia@system.local')->first();

        if (!$agent) {

            $agent = User::create([
                'firstname' => 'Agent',
                'lastname' => 'IA',
                'email' => 'agentia@system.local',
                'phone' => null,
                'password' => Hash::make('SuperSecurePassword123'),

                // 🔥 nouveau système de rôles
                'is_admin' => false,
                'is_agentia' => true,
            ]);

            // création token Sanctum
            $token = $agent->createToken('agentia-token');

            // afficher le token dans le terminal
            $this->command->info('Agent IA Token:');
            $this->command->info($token->plainTextToken);
        }
    }
}