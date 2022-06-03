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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('plate_no');
            $table->date('start_date');
            $table->time('start_time');
            $table->date('end_date')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedInteger('station_id')->nullable();

            $table->string('bound');

            // -1 invalid, 0 no action, 1 valid
            $table->tinyInteger('is_validated')->default(0); // default to false
            $table->unsignedInteger('user_id')
                ->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('station_id')
                ->references('id')
                ->on('stations')
                ->nullOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
};
