<?php

namespace Database\Seeders;

use App\Constants;
use Exception;
use Facades\App\Http\Controllers\CompanyController;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Constants::COMPANY;
        foreach ($companies as $company) {
            $request = new Request;
            $request->merge(array_merge($company, ['isSeeder' => true]));
            $controller = CompanyController::store($request);
            if ($request->customerType == 'lead') {
                try {
                    Http::withHeaders([
                        'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJyb2xlcyI6WyJhZG1pbiIsIlRlc3RSb2xlIl0sInR5cGVzIjp7ImVtcGxveWVlIjowLCJjdXN0b21lcl9lbXBsb3llZSI6MCwicGFydG5lcl9lbXBsb3llZSI6MH0sImNvbXBhbnlfaWQiOiIxODU2NTljZC0zMzhiLWZkNjUtMDIxMC1kYWEwZThlMTBiZjYiLCJsb2NhdGlvbl9pZCI6IiIsInBlcm1pc3Npb25zIjpbMSwyLDMsNCw1LDYsNyw4LDksMTAsMTEsMTIsMTMsMTQsMTUsMTYsMTcsMTgsMTksMjAsMjEsMjIsMjMsMjQsMjUsMjYsMjcsMjgsMjksMzAsMzEsMzIsMzMsMzQsMzUsMzYsMzcsMzgsMzksNDAsNDEsNDIsNDMsNDQsNDUsNDYsNDcsNDgsNDksNTAsNTEsNTIsNTMsNTQsNTUsNTYsNTcsNTgsNTksNjAsNjEsNjIsNjMsNjQsNjUsNjYsNjcsNjgsNzEsNzIsNzMsNzQsNzUsNzYsNzcsNzgsNzksODAsODEsODMsODQsODUsODYsODcsODgsODksOTAsOTEsOTIsOTNdLCJ1c2VyX2lkIjoiMTg1NjU5Y2QtMzM4Yi1mZDY1LTAyMTAtZGFhMGU4ZTEwYmY2IiwiaXNzIjoiZG9jc2hlcm8uZGUiLCJhdWQiOiJkb2NzaGVyby5kZSIsImV4cCI6MTY4NDMyMTQ2N30.EDumdwe1Nyn4oeoCuF8jB2qRWAMHbAZXv9cemav1Cbg'
                    ])->post('https://h2972847.stratoserver.net/o/public/index.php/create-user', [
                        'mail' => 'dominik.kilch@ipsen.de',
                        'first_name' => 'Dominik',
                        'last_name' => 'Kilch',
                        'password' => '12345678',
                        'city' => null,
                        'zip' => null,
                        'street' => null,
                        'street_number' => null,
                        'mobile' => '+492821804391',
                        'fax' => '',
                        'department' => 'IT',
                        'position' => 'Head of IT Department',
                        'location_id' => null,
                        'company_id' => $controller->getData()->company_id,
                        'roles' => [],
                        'creation_date' => null,
                        'types' => [
                            'customer'
                        ]
                    ]);
                } catch (Exception $e) {
                }
            }
            if (!$controller) {
                Log::info('company seeder not saved');
            }
        }
    }
}
