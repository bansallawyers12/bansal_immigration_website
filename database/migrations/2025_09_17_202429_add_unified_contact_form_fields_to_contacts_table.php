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
        Schema::table('contacts', function (Blueprint $table) {
            // Add new fields for unified contact form (name, subject, message already exist)
            $table->enum('status', ['unread', 'read', 'resolved', 'archived'])->default('unread')->after('message');
            $table->string('forwarded_to')->nullable()->after('status');
            $table->timestamp('forwarded_at')->nullable()->after('forwarded_to');
            $table->string('form_source', 50)->nullable()->after('forwarded_at');
            $table->string('form_variant', 50)->nullable()->after('form_source');
            $table->string('ip_address', 45)->nullable()->after('form_variant');
            
            // Add indexes for better performance
            $table->index(['created_at'], 'idx_contacts_created_at');
            $table->index(['contact_email'], 'idx_contacts_email');
            $table->index(['status'], 'idx_contacts_status');
            $table->index(['forwarded_at'], 'idx_contacts_forwarded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('idx_contacts_created_at');
            $table->dropIndex('idx_contacts_email');
            $table->dropIndex('idx_contacts_status');
            $table->dropIndex('idx_contacts_forwarded_at');
            
            // Drop columns (excluding name, subject, message which existed before)
            $table->dropColumn([
                'status', 'forwarded_to', 'forwarded_at', 'form_source', 'form_variant', 'ip_address'
            ]);
        });
    }
};
