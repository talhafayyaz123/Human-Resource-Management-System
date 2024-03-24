<?php

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
        if (Schema::hasTable('workshop_templates_widgets') == false) {
            Schema::create('workshop_templates_widgets', function (Blueprint $table) {
                $table->id();
                $table->enum("type", ["heading", "paragraph", "checkbox", "radio-button", "text-input", "number-input", "table", "wysiwyg"]);
                $table->json("configuration")->nullable();
                $table->unsignedBigInteger("workshop_templates_column_id");
                $table->foreign("workshop_templates_column_id")->references("id")->on("workshop_templates_columns")->onDelete("cascade");
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workshop_templates_widgets');
    }
};
