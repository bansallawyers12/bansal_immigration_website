<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('admin_id');
            $table->string('token', 64)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->index(['token', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_accounts');
    }
} 