<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Http;

class FleetCarsUnitTest extends TestCase
{

    private $token;

    public function setUp(): void
    {
        parent::setUp();

        // Authenticate the user and obtain the token
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(env('VITE_AUTH_SERVICE_URL') . '/login', [
            'mail' => 'admin@docshero.de',
            'password' => '12345',
        ]);
        $this->token = $response->json('token');
    }

    public function testStoreFleetCar()
    {
        $faker = Faker::create();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get('https://h2972847.stratoserver.net/o/public/index.php/list-users', [
            'limit_start' => 0,
            'limit_count' => 25,
            'type' => 'employee'
        ]);

        $apiResponse = $response->json();
        // Extracting random driverId from the API response
        $randomEmployeeIndex = random_int(0, 4);
        $driverId = $apiResponse['data'][$randomEmployeeIndex]['id'];
        $data = [
            'licenceNumber' => $faker->bothify('??###'),
            'vim' => $faker->bothify('??????'),
            'brand' => $faker->word,
            'model' => $faker->word,
            'fuelType' => $faker->randomElement(['diesel', 'electronic', 'gasoline']),
            'leasingRate' => $faker->randomFloat(2, 500, 2000),
            'leasingStartDate' => $faker->date(),
            'leasingEndDate' => $faker->date(),
            'miles' => $faker->randomFloat(2, 0, 10000),
            'totalMileage' => $faker->randomFloat(2, 0, 10000),
            'status' => $faker->randomElement(['ready', 'out_of_service']),
            'driverId' =>  $driverId,
            'color' => $faker->colorName,
            'kilowatt' => $faker->bothify('###KW'),
            'modelKey' => $faker->word,
            'modelDetail' => $faker->word,
            'extraEquipment' => $faker->sentence,
            'contactNumber' => $faker->phoneNumber,
            'deliveryDate' => $faker->date(),
            'contractPeriodMonths' => $faker->numberBetween(6, 24),
            'leasing' => [
                'perAdditionalKilometers' => $faker->randomFloat(2, 1, 10),
                'freeExtraKilometers' => $faker->randomNumber(),
                'perLessKilometers' => $faker->randomFloat(2, 1, 10),
                'freeLessKilometers' => $faker->randomNumber(),
            ],
            'damageProtection' => [
                'perAdditionalKilometers' => $faker->randomFloat(2, 1, 10),
                'freeExtraKilometers' => $faker->randomNumber(),
                'perLessKilometers' => $faker->randomFloat(2, 1, 10),
                'freeLessKilometers' => $faker->randomNumber(),
            ],
            'maintenanceAndAbstraction' => [
                'perAdditionalKilometers' => $faker->randomFloat(2, 1, 10),
                'freeExtraKilometers' => $faker->randomNumber(),
                'perLessKilometers' => $faker->randomFloat(2, 1, 10),
                'freeLessKilometers' => $faker->randomNumber(),
            ],
            'updatedAt' => null
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/fleet-cars', $data);

        // Check if the data exists in the database
        $this->assertDatabaseHas('fleet_cars', [
            'licence_number' => $data['licenceNumber'],
            'vim' => $data['vim'],
            // Add more fields as needed to match your database schema
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => trans('messages.record_saved_success', ['name' => 'Fleet Car'])
        ]);
    }
}
