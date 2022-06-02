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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('weekly_reports');

        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_report_batch_id')
                ->constrained();
            $table->foreignId('operator_id')
                ->constrained('operators')
                ->cascadeOnDelete();
            $table->text('filepath')
                ->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weekly_reports');
    }
};
