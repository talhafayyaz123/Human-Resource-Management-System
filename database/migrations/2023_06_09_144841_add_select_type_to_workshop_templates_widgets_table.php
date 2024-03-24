<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workshop_templates_widgets', function (Blueprint $table) {
            DB::statement("ALTER TABLE workshop_templates_widgets MODIFY COLUMN type enum('heading', 'paragraph', 'checkbox', 'radio-button', 'text-input', 'number-input', 'table', 'wysiwyg', 'select')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workshop_templates_widgets', function (Blueprint $table) {
            DB::statement("ALTER TABLE workshop_templates_widgets MODIFY COLUMN type enum('heading', 'paragraph', 'checkbox', 'radio-button', 'text-input', 'number-input', 'table', 'wysiwyg')");
        });
    }
};
