<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('blood_exchanges')) {
            Schema::create('blood_exchanges', function (Blueprint $table) {
                $table->id();
                $table->string('offered_blood_group', 5);
                $table->string('needed_blood_group', 5);
                $table->unsignedInteger('units')->default(1);
                $table->string('patient_name');
                $table->string('hospital');
                $table->string('city');
                $table->string('contact_name');
                $table->string('contact_phone');
                $table->string('contact_email')->nullable();
                $table->string('urgency_level')->default('Urgent');
                $table->text('notes')->nullable();
                $table->string('status')->default('pending');
                $table->text('admin_notes')->nullable();
                $table->string('manage_token', 80)->unique();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_exchanges');
    }
};
