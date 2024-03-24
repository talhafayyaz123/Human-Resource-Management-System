<?php

use App\Models\ContactReport;
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
        // Retrieve existing records
        $records = ContactReport::all();

        // Update reportNumber for each record
        foreach ($records as $record) {
            $newReportNumber = str_replace('LN-', 'KB-', $record->report_number);
            $record->update(['report_number' => $newReportNumber]);
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
