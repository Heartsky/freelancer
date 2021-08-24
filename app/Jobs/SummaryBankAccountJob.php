<?php

namespace App\Jobs;

use App\Facades\FeedCache;
use App\Facades\FeedFetch;
use App\Facades\WooCache;
use App\Helper\Tracking;
use App\Jobs\SocialShop\FetchFeed\ExecuteFetchGoogleJob;
use App\Models\BalanceHistory;
use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\ExpenseTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SummaryBankAccountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $accountId;
    private $date ;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($accountId, $date )
    {
        self::onQueue('summary_cash');
        $this->accountId = $accountId;
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
        $account = BankAccount::find($this->accountId);
        $currentBalance = $account->balance;
        $cycle =  $account->cycle;
        $newCycle = strtotime($start);
        if($cycle >= $newCycle) {
           return;
        }

        $newBalance = $currentBalance;

        $expenses = ExpenseTransaction::where('expense_group',1)->where("bank_account_id",$this->accountId)
           ->whereBetween('expense_date', [$start, $end])
           ->get();
        foreach ($expenses as $exp) {
           if($exp->type == "debited") {
               $newBalance -= floatval($exp->amount);
           } else {
               $newBalance += floatval($exp->amount);
           }

        }
        $account->update([
            'balance' => $newBalance,
            'cycle' => $newCycle
        ]);
        BalanceHistory::create([
            'object_id' => $this->accountId,
            'cycle' => $newCycle,
            'type' => 'bank',
            'amount' => $newBalance
        ]);

    }


}
