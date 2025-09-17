<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePartnerAgentSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove partner and agent related tables
        Schema::dropIfExists('representing_partners');
        Schema::dropIfExists('partner_branches');
        Schema::dropIfExists('partner_types');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('agents');
        
        // Remove partner/agent columns from applications table if they exist
        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                if (Schema::hasColumn('applications', 'partner_id')) {
                    $table->dropColumn('partner_id');
                }
                if (Schema::hasColumn('applications', 'super_agent')) {
                    $table->dropColumn('super_agent');
                }
                if (Schema::hasColumn('applications', 'sub_agent')) {
                    $table->dropColumn('sub_agent');
                }
                if (Schema::hasColumn('applications', 'partner_revenue')) {
                    $table->dropColumn('partner_revenue');
                }
            });
        }
        
        // Remove agent_id column from clients/admins table if it exists
        if (Schema::hasTable('admins')) {
            Schema::table('admins', function (Blueprint $table) {
                if (Schema::hasColumn('admins', 'agent_id')) {
                    $table->dropColumn('agent_id');
                }
            });
        }
        
        // Remove partner references from products table if it exists
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'partner')) {
                    $table->dropColumn('partner');
                }
                if (Schema::hasColumn('products', 'branches')) {
                    $table->dropColumn('branches');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration is not reversible as we're removing the entire partner/agent system
        throw new Exception('This migration cannot be reversed. The partner/agent system has been permanently removed.');
    }
}
