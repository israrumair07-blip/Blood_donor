<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('blood_group', 5)->index();
            $table->unsignedInteger('units_needed')->default(1);
            $table->string('hospital');
            $table->string('city')->index();
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('urgency_level')->default('Immediate');
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('open')->index(); // 'open', 'fulfilled', 'cancelled'
            $table->string('manage_token', 64)->nullable()->index();
            $table->timestamp('fulfilled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};

