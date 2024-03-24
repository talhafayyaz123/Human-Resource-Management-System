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
        Schema::table('sale_offers', function (Blueprint $table) {
            if (Schema::hasColumn("sale_offers", "offer_comments")) {
                $table->renameColumn("offer_comments", "cover_letter_text");
            }
            if (!Schema::hasColumn("sale_offers", "offer_description_text")) {
                $table->text("offer_description_text")->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_offers', function (Blueprint $table) {
            if (Schema::hasColumn("sale_offers", "cover_letter_text")) {
                $table->renameColumn("cover_letter_text", "offer_comments");
            }
            if (Schema::hasColumn("sale_offers", "offer_description_text")) {
                $table->dropColumn("offer_description_text");
            }
        });
    }
};
