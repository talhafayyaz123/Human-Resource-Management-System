<?php

use App\Models\ContractAttachment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $attachments = ContractAttachment::all();

        foreach ($attachments as $attachment) {
            $new_attachment_number = preg_replace('/-/', '', $attachment->attachment_number, 1);

            $attachment->update(['attachment_number' => $new_attachment_number]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_attachment', function (Blueprint $table) {
            //
        });
    }
};
