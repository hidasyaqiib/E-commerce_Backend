<?php

// ============================================
// 1. DATABASE MIGRATION
// ============================================
// File: database/migrations/xxxx_xx_xx_create_customers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        // Seharusnya seperti ini:
Schema::create('customers', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('phone');
    $table->text('address');
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}