<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservation_status_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations','id')->onDelete('cascade');
            $table->foreignId('reservation_status_catlog_id')->constrained('reservation_status_catlogs','id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_status_events');
    }
};
