<?php

namespace Database\Seeders;

use App\Constants;
use App\Models\ReportCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ReportCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $report_categories = Constants::REPORT_CATEGORIES;
        foreach ($report_categories as $report) {
            $model = new ReportCategory;
            $model->name = $report['name'];
            $model->save();
        }
    }
}
