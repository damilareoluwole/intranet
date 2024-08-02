<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->string('category')->comment('TECHNOLOGY GUIDE, NEWSPAPER, ETC.');
            $table->unsignedBigInteger('folder_id');
            $table->string('name')->nullable();
            $table->string('file_name')->nullable(); 
            $table->string('type');
            $table->string('path');
            $table->string('size');
            $table->unsignedBigInteger('modifier_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('folder_id')->references('id')->on('hr_folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_files');
    }
}
