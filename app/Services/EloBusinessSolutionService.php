<?php

namespace App\Services;

use App\Models\CreditorInvoice;
use App\Models\EloBusinessSolutions;
use Carbon\Carbon;
use App\Traits\CustomHelper;

class EloBusinessSolutionService
{
    use CustomHelper;
    /**
     * Create a new elo_business_solution.
     *
     * @param  array  $data
     * @return \App\Models\EloBusinessSolutions
     */
    public function createEloBusinessSolution(array $data)
    {
        $elo_business_solution = EloBusinessSolutions::create([
            'name' => @$data['name'],
            'install_name' => @$data['installName'],
            'type' => @$data['type'],
        ]);
        return $elo_business_solution;
    }

    /**
     * Update the specified elo_business_solution.
     *
     * @param  \App\Models\EloBusinessSolutions  $elo_business_solution
     * @param  array  $data
     * @return \App\Models\EloBusinessSolutions
     */
    public function updateEloBusinessSolution(EloBusinessSolutions $elo_business_solution, array $data)
    {
        $elo_business_solution->update([
            'name' => @$data['name'],
            'install_name' => @$data['installName'],
            'type' => @$data['type'],
        ]);
        return $elo_business_solution;
    }

    /**
     * Delete the specified elo_business_solution.
     *
     * @param  \App\Models\EloBusinessSolutions  $elo_business_solution
     * @return void
     */
    public function deleteEloBusinessSolution(EloBusinessSolutions $elo_business_solution)
    {
        $elo_business_solution->delete();
    }
}
