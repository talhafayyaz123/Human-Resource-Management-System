<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\ContractField;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_fields', function (Blueprint $table) {
            if (Schema::hasColumn('contract_fields', 'type'))
                DB::statement("ALTER TABLE contract_fields MODIFY COLUMN type enum('text', 'number', 'date', 'time')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_fields', function (Blueprint $table) {
            if (Schema::hasColumn('contract_fields', 'type')) {
                $contract_fields = ContractField::all();
                foreach ($contract_fields as $contract_field) {
                    if ($contract_field->type != 'text' || $contract_field->type != 'number') {
                        $contract_field->type = 'text';
                        $contract_field->save();
                    }
                }
                DB::statement("ALTER TABLE contract_fields MODIFY COLUMN type enum('text', 'number')");
            }
        });
    }
};
