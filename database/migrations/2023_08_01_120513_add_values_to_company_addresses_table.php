<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CompanyLocation;
use App\Models\Country;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $company_locations = CompanyLocation::get();
        foreach ($company_locations as $company_location) {
            $country_to_find = '';
            switch ($company_location->country) {
                case 'Deutschland':
                    $country_to_find = 'Germany';
                    break;
                case 'USA':
                    $country_to_find = 'United States of America';
                    break;
                default:
                    $country_to_find = $company_location->country;
                    break;
            }

            if (!empty($country_to_find)) {
                $country = Country::whereRaw('LOWER(name) = ?', [strtolower($country_to_find)])->first(); //using raw to deal with case insensitive

                if ($country) {
                    // Update the company location's country_id
                    $company_location->country_id = $country->id;
                    $company_location->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            //
        });
    }
};
