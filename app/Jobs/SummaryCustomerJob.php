<?php

namespace App\Jobs;

use App\Facades\FeedCache;
use App\Facades\FeedFetch;
use App\Facades\WooCache;
use App\Helper\Tracking;
use App\Jobs\SocialShop\FetchFeed\ExecuteFetchGoogleJob;
use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerAliasName;
use App\Models\CustomerJobCategory;
use App\Models\InvoiceImport;
use App\Models\Job;
use App\Models\RevenueReport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SummaryCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $date;
    private $customerId;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($date= null)
    {
        self::onQueue('summary_cash');
        if(empty($date)) {
            $this->date = new \DateTime('now');
        } else {
            $this->date = $date;
        }



    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $customers = Customer::all();
        foreach ($customers as $customer) {
            SummaryCustomerMonthlyJob::dispatch($customer->id, $this->date);
        }
    }


}
