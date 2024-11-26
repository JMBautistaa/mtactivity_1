<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained('students')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('college_id')->nullable()->constrained('colleges')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('section_id')->nullable()->constrained('sections')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('advisor_id')->nullable()->constrained('instructors')->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('registrations');
    }
}
