<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectFlagToJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table
                ->boolean('project')
                ->default(false)
                ->after('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
}
