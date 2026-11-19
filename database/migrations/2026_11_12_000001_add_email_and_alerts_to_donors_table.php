<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            if (!Schema::hasColumn('donors', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('donors', 'notify_urgent_requests')) {
                $table->boolean('notify_urgent_requests')->default(true)->after('is_available');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            if (Schema::hasColumn('donors', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('donors', 'notify_urgent_requests')) {
                $table->dropColumn('notify_urgent_requests');
            }
        });
    }
};

