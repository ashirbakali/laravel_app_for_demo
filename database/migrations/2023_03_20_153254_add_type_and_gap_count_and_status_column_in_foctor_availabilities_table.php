<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndGapCountAndStatusColumnInFoctorAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_availabilities', function (Blueprint $table) {
            $table->enum('type',['hour','minute'])->default('hour');
            $table->decimal('gap_count',10,2)->default(1);
            $table->boolean('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foctor_availabilities', function (Blueprint $table) {
            $table->dropColumn('type',['H','M']);
            $table->dropColumn('gap_count',10,2);
            $table->dropColumn('status');
        });
    }
}
