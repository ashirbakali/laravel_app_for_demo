<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOnAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_service_id')->nullable();
            $table->foreign('vendor_service_id')->references('id')->on('vendor_services');
            $table->unsignedBigInteger('time_slot_id')->nullable();
            $table->foreign('time_slot_id')->references('id')->on('time_slots');
            $table->unsignedBigInteger('card_id')->nullable();
            $table->foreign('card_id')->references('id')->on('cards');
            $table->date('appointment_datetime')->change();
            $table->decimal('amount',10,2)->default(0);
        });
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
}
