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
        $data = [
            [
                'title' => 'Off topic',
                'description' => 'Not about the product',
            ],
            [
                'title' => 'Inappropriate',
                'description' => 'Disrespectful, hateful, obscene',
            ],
            [
                'title' => 'Fake',
                'description' => 'Paid for, inauthentic',
            ],
            [
                'title' => 'Other',
                'description' => 'Something else',
            ],
        ];

        \App\Models\ReviewReport::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
