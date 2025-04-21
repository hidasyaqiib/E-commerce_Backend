<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\SalesReport;
use Carbon\Carbon;

class GenerateSalesReport extends Command
{
    protected $signature = 'report:generate-sales';
    protected $description = 'Generate daily sales report';

    public function handle()
    {
        $date = Carbon::yesterday()->toDateString();

        $reportData = Transaction::whereDate('created_at', $date)
            ->where('status', 'paid')
            ->selectRaw('COUNT(id) as total_transactions, SUM(grand_total) as total_sales')
            ->first();

        SalesReport::updateOrCreate(
            ['report_date' => $date],
            [
                'total_transactions' => $reportData->total_transactions ?? 0,
                'total_sales' => $reportData->total_sales ?? 0,
            ]
        );

        $this->info("Sales report for {$date} generated successfully!");
    }
}
