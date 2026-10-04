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
    Schema::table('donors', function (Blueprint $table) {
        if (!Schema::hasColumn('donors', 'gender')) {
            $table->string('gender', 10)->nullable()->after('phone');
        }
        if (!Schema::hasColumn('donors', 'donor_token')) {
            $table->string('donor_token', 64)->nullable()->unique();
        }
        if (!Schema::hasColumn('donors', 'guardian_name')) {
            $table->string('guardian_name')->nullable();
        }
        if (!Schema::hasColumn('donors', 'guardian_relation')) {
            $table->string('guardian_relation')->nullable();
        }
        if (!Schema::hasColumn('donors', 'guardian_phone')) {
            $table->string('guardian_phone')->nullable();
        }
        if (!Schema::hasColumn('donors', 'donation_pending')) {
            $table->boolean('donation_pending')->default(false);
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            //
        });
    }
};
