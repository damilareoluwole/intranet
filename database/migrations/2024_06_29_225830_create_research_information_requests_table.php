<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchInformationRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_information_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->unsignedBigInteger('requester_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('group');
            $table->string('email');
            $table->text('information_required');
            $table->text('justification');
            $table->text('comment')->nullable(true);
            $table->text('red_comment')->nullable(true); 
            $table->unsignedBigInteger('admin_id')->nullable(true);
            $table->timestamp('admin_at')->nullable(true);
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Completed'])->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('research_information_requests');
    }
}
