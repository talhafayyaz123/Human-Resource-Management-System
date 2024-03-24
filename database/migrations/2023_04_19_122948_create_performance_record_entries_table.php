<?php

use App\Helpers\OrderHelper;
use App\Models\PerformanceRecordEntry;
use App\Models\TimeTrackerCompany;
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
        Schema::create('performance_record_entries', function (Blueprint $table) {
            if(Schema::hasTable('performance_record_entries')) return;
            $table->id();
            $table->date('date');
            $table->text('description');
            $table->boolean('is_goodwill');
            $table->enum('status', ['pr', 'billed'])->default('pr');
            $table->string('time');
            $table->string('user_id')->nullable();
            $table->bigInteger('entry_order')->nullable();
            /*  $table->boolean('is_edited')->default(false);
            $table->string('edited_user_id')->nullable(); */
            $table->unsignedBigInteger('company_timetracker_id')->nullable()->references('id')->on('time_tracker_companies');
            $table->unsignedBigInteger('performance_record_id')->nullable()->references('id')->on('performance_records');
            $table->timestamps();
        });
        $time_tracker_companies = TimeTrackerCompany::whereNotNull('performance_record_id')->get();
        $previous_entry_orders = [];
        foreach ($time_tracker_companies as $time_tracker_company) {
            $performanceRecordEntry = new PerformanceRecordEntry();
            if (!isset($previous_entry_orders[$time_tracker_company->performance_record_id])) {
                $entry_order = OrderHelper::order(null, null);
            }
            // Otherwise, calculate the entry order based on the previous entry order for this performance record id
            else {
                $entry_order = OrderHelper::order($previous_entry_orders[$time_tracker_company->performance_record_id] ?? null, null) / 2;
            }
            $performanceRecordEntry->date = $time_tracker_company->date; // Set the date to the current date
            $performanceRecordEntry->description = $time_tracker_company->description; // Use the description from the TimeTrackerCompany
            $performanceRecordEntry->is_goodwill = $time_tracker_company->is_goodwill; // Use the is_goodwill value from the TimeTrackerCompany
            $performanceRecordEntry->status = $time_tracker_company->status; // Set the default status to previous status
            $performanceRecordEntry->time = $time_tracker_company->time; // Use the time from the TimeTrackerCompany
            $performanceRecordEntry->user_id = $time_tracker_company->user_id; // Replace with the user ID you want to associate with the record
            $performanceRecordEntry->company_timetracker_id = $time_tracker_company->id;
            $performanceRecordEntry->performance_record_id = $time_tracker_company->performance_record_id;
            $previous_entry_orders[$time_tracker_company->performance_record_id] = $entry_order;
            $performanceRecordEntry->entry_order = $entry_order;
            $performanceRecordEntry->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_record_entries');
    }
};
