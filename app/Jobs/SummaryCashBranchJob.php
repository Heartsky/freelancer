<?php

namespace App\Jobs;

use App\Facades\FeedCache;
use App\Facades\FeedFetch;
use App\Facades\WooCache;
use App\Helper\Tracking;
use App\Jobs\SocialShop\FetchFeed\ExecuteFetchGoogleJob;
use App\Models\BalanceHistory;
use App\Models\Branch;
use App\Models\ExpenseTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SummaryCashBranchJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $branchId;
    private $date ;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($branchId, $date )
    {
        self::onQueue('summary_cash');
        $this->branchId = $branchId;
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $today = $this->date;
        $end = $today->format("Y-m-01");
        $today->modify("-1 month");
        $start = $today->format("Y-m-01");
        $branch = Branch::find($this->branchId);
        $currentBalance = $branch->current_cash_balance;
        $cycle =  $branch->current_cycle;
        $newCycle = strtotime($start);
        if($cycle >= $newCycle) {
           return;
        }

        $newBalanceVnd = $currentBalance;
        $newBalanceUsd = $branch->current_usd_balance;
        $newBalanceJpy = $branch->current_jpy_balance;
        $expenses = ExpenseTransaction::where('expense_group',2)->where("branch_id",$this->branchId)
           ->whereBetween('expense_date', [$start, $end])
           ->get();
        foreach ($expenses as $exp) {
           if($exp->type == "debited") {
               if($exp->currency == 'VND') {
                   $newBalanceVnd -= floatval($exp->amount);
               }
               if($exp->currency == 'USD') {
                   $newBalanceUsd -= floatval($exp->amount);
               }
               if($exp->currency == 'JPY') {
                   $newBalanceJpy -= floatval($exp->amount);
               }

           } else {
               if($exp->currency == 'VND') {
                   $newBalanceVnd += floatval($exp->amount);
               }
               if($exp->currency == 'USD') {
                   $newBalanceUsd += floatval($exp->amount);
               }
               if($exp->currency == 'JPY') {
                   $newBalanceJpy += floatval($exp->amount);
               }
           }

        }
        $branch->update([
            'current_cash_balance' => $newBalanceVnd,
            'current_usd_balance' => $newBalanceUsd,
            'current_jpy_balance' => $newBalanceJpy,
            'current_cycle' => $newCycle]);
        BalanceHistory::create([
            'object_id' => $this->branchId,
            'cycle' => $newCycle,
            'type' => 'cash_vnd',
            'amount' => $newBalanceVnd
        ]);
        BalanceHistory::create([
            'object_id' => $this->branchId,
            'cycle' => $newCycle,
            'type' => 'cash_usd',
            'amount' => $newBalanceUsd
        ]);
        BalanceHistory::create([
            'object_id' => $this->branchId,
            'cycle' => $newCycle,
            'type' => 'cash_jpy',
            'amount' => $newBalanceJpy
        ]);
    }


}
