<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->date('report_date');
            $table->decimal('total_income', 12, 2);
            $table->integer('total_products_sold');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_reports');
    }
};
