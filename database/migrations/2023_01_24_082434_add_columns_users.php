<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('is_admin_approve', ['1', '0'])->default('0');
            $table->string('license')->nullable();
            $table->string('banner_img')->nullable();
            $table->string('insurance_coverage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin_approve');
            $table->dropColumn('license')->nullable(true);
            $table->dropColumn('banner_img')->nullable(true);
            $table->dropColumn('insurance_coverage')->nullable(true);
        });
    }
}
