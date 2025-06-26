<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->text('description')->nullable();
        });
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
    }

    /**
     * Reverse the migrations.
     */
};
