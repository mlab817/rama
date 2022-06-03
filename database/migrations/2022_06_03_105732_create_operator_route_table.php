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
        Schema::create('operator_route', function (Blueprint $table) {
            $table->foreignId('operator_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('route_id');
            $table->foreign('route_id')
                ->references('id')
                ->on('routes')
                ->cascadeOnDelete();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operator_route');
    }
};
