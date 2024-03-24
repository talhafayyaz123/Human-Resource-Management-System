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
        Schema::create('outbounded_contracts', function (Blueprint $table) {
            if (!Schema::hasTable('outbounded_contracts')) {
                $table->id();
                $table->string('contract_number')->nullable();
                $table->enum("receiver_type", ['lead', 'customer']);
                $table->foreignUuid("receiver_id")->references("id")->on("companies")->cascadeOnDelete();
                $table->enum("status", ['versendet', 'beauftragt', 'abgelehnt', 'entwurf'])->default("entwurf");
                $table->foreignId("contract_type_id")->references("id")->on("contract_types")->cascadeOnDelete();
                $table->dateTime('sign_date')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('outbounded_contracts');
    }
};
