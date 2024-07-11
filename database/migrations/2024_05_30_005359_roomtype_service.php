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
        Schema::create('roomtype_service', function (Blueprint $table) {
            $table->id();
                $table->foreignID('roomtype_id')->constrained('room_types','id')->onDelete('cascade');
                $table->foreignID('service_id')->constrained('services','id')->onDelete('cascade');
            $table->timestamps();

            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roomtype_service');
    }
};
