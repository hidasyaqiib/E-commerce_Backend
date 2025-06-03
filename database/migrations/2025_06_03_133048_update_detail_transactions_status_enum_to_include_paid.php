<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    DB::statement("ALTER TABLE detail_transactions MODIFY status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending'");
}

public function down()
{
    DB::statement("ALTER TABLE detail_transactions MODIFY status ENUM('pending', 'success', 'cancelled') DEFAULT 'pending'");
}
};
