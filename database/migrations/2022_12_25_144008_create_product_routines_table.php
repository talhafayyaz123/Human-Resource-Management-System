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
        Schema::create('product_routines', function (Blueprint $table) {
            $table->id();
            //On Premise
            $table->text('premise_windows_install_routine')->nullable();
            $table->text('premise_windows_uninstall_routine')->nullable();
            $table->text('premise_linux_install_routine')->nullable();
            $table->text('premise_linux_uninstall_routine')->nullable();
            $table->text('premise_mac_install_routine')->nullable();
            $table->text('premise_mac_uninstall_routine')->nullable();

            //Private Cloud
            $table->text('private_windows_install_routine')->nullable();
            $table->text('private_windows_uninstall_routine')->nullable();
            $table->text('private_linux_install_routine')->nullable();
            $table->text('private_linux_uninstall_routine')->nullable();
            $table->text('private_mac_install_routine')->nullable();
            $table->text('private_mac_uninstall_routine')->nullable();


            //Public Cloud
            $table->text('public_windows_install_routine')->nullable();
            $table->text('public_windows_uninstall_routine')->nullable();
            $table->text('public_linux_install_routine')->nullable();
            $table->text('public_linux_uninstall_routine')->nullable();
            $table->text('public_mac_install_routine')->nullable();
            $table->text('public_mac_uninstall_routine')->nullable();
            $table->unsignedBigInteger('product_version_id');

            $table->foreign('product_version_id')->references('id')->on('product_versions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_routines');
    }
};
