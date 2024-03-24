<?php

use App\Models\Country;
use App\Models\TravelExpense;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $travel_expenses = TravelExpense::get();

        foreach ($travel_expenses as $travel_expense) {
            if (isset($travel_expense->departure_country)) {
                $country = Country::whereRaw('LOWER(name) = ?', [strtolower($travel_expense->departure_country)])->first(); //using raw to deal with case insensitive
                if (!empty($country)) {
                    $travel_expense->departure_country_id = $country->id;
                }
            }
            if (isset($travel_expense->arrival_country)) {
                $country = Country::whereRaw('LOWER(name) = ?', [strtolower($travel_expense->arrival_country)])->first(); //using raw to deal with case insensitive
                if (!empty($country)) {
                    $travel_expense->arrival_country_id = $country->id;
                }
            }
            if ($travel_expense->isDirty()) {
                $travel_expense->save();
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
    }
};
