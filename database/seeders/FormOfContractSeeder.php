<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormOfContract;
use App\Constants;

class FormOfContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $form_of_contracts = Constants::FORM_OF_CONTRACT;

        foreach ($form_of_contracts as $contract) {
            $form_of_contract = new FormOfContract();
            $form_of_contract->name = $contract['name'];
            $form_of_contract->save();
        }
    }
}
