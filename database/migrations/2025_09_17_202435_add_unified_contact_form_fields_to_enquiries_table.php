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
        Schema::table('enquiries', function (Blueprint $table) {
            // Add new fields for unified contact form
            $table->string('subject')->nullable()->after('phone');
            $table->string('ip_address', 45)->nullable()->after('visa_expiry');
            
            // Add indexes for better performance (only if they don't exist)
            if (!Schema::hasIndex('enquiries', 'idx_enquiries_created_at')) {
                $table->index(['created_at'], 'idx_enquiries_created_at');
            }
            if (!Schema::hasIndex('enquiries', 'idx_enquiries_email')) {
                $table->index(['email'], 'idx_enquiries_email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('idx_enquiries_created_at');
            $table->dropIndex('idx_enquiries_email');
            
            // Drop columns
            $table->dropColumn(['subject', 'ip_address']);
        });
    }
};
