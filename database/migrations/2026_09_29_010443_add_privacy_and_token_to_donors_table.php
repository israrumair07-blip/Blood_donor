<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->string('donor_token', 64)->nullable()->unique()->after('id');
            $table->string('guardian_name')->nullable()->after('phone');
            $table->string('guardian_relation')->nullable()->after('guardian_name'); // Father, Brother, Husband
            $table->string('guardian_phone')->nullable()->after('guardian_relation');
            $table->boolean('donation_pending')->default(false)->after('is_available');
        });
    }

    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn([
                'donor_token',
                'guardian_name',
                'guardian_relation',
                'guardian_phone',
                'donation_pending'
            ]);
        });
    }
};