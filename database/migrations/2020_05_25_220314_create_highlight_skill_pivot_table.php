<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHighlightSkillPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('highlight_skill', function (Blueprint $table) {
            $table
                ->unsignedBigInteger('highlight_id')
                ->unsigned()
                ->index();
            $table
                ->foreign('highlight_id')
                ->references('id')
                ->on('highlights')
                ->onDelete('cascade');
            $table
                ->unsignedBigInteger('skill_id')
                ->unsigned()
                ->index();
            $table
                ->foreign('skill_id')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade');
            $table->primary(['highlight_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('highlight_skill');
    }
}
