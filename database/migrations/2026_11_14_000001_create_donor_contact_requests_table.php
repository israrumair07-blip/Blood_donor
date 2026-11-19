<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donor_contact_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('donors')->onDelete('cascade');
            $table->string('requester_name');
            $table->string('requester_phone');
            $table->string('requester_email');
            $table->string('patient_name');
            $table->string('hospital');
            $table->string('city')->index();
            $table->string('urgency_level')->default('Immediate');
            $table->text('message');
            $table->string('status', 20)->default('pending')->index(); // pending, approved, rejected
            $table->text('admin_notes')->nullable();
            $table->string('tracking_token', 64)->unique()->index();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donor_contact_requests');
    }
};

