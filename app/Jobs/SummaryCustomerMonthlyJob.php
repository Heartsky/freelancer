<?php

namespace App\Jobs;

use App\Facades\FeedCache;
use App\Facades\FeedFetch;
use App\Facades\WooCache;
use App\Helper\Tracking;
use App\Jobs\SocialShop\FetchFeed\ExecuteFetchGoogleJob;
use App\Models\BankAccount;
use App\Models\Branch;
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

class SummaryCustomerMonthlyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $date;
    private $customerId;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($customerId,$date)
    {
        self::onQueue('summary_cash');
        $this->date = $date;
        $this->customerId = $customerId;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $today = $this->date;
            $endDate = $today->format("Y-m-01");
            $today->modify("-1 month");
            $startDate = $today->format("Y-m-01");
            $alias = CustomerAliasName::where('customer_id', $this->customerId)->get()->toArray();
            $listName = array_column($alias, 'alias_name');
            $jobs = Job::whereIn('tokuisakimei',$listName)->where([['henkyaku_hi', '>=', $startDate], ['henkyaku_hi', '<=', $endDate]])->orderBy('hacchyuu_naiyou')->get();
            if($jobs->count() ==  0) {
                return;
            }
            $allprice = CustomerJobCategory::where('customer_id', $this->customerId)->get()->load("jobCategory")->toArray();
            $listPrice = [];
            foreach ($allprice as $item){
                $listPrice[$item['job_category']['job_category_name']] = ['price_count' => floatval($item['price_count']),'price_sqm' => floatval($item['price_sqm']) ];
            }
            $total = [];
            foreach ($jobs  as $job) {
                $jobcategory = $job['hacchyuu_naiyou'];
                if(isset($listPrice[$jobcategory])) {
                    $currentCate = $listPrice[$jobcategory];
                    $priceTsubo =  $currentCate['price_sqm'];
                    $price =$currentCate['price_count'];
                    $totalValue = ($priceTsubo* (float)$job['tsubosuu'] + $price);
                    $company = $job['company_id'];
                    if(isset($total[$company])) {
                        $total[$company] += $totalValue;
                    } else {
                        $total[$company] = $totalValue;
                    }
                }
            }
            $invoices = InvoiceImport::where([['customer_id' , $this->customerId],['created_at', '>=', $startDate], ['created_at', '<=', $endDate]])->get();
            foreach ($invoices as $item) {
                $company = $item['company_id'];
                if(isset($total[$company])) {
                    $total[$company] += $totalValue;
                } else {
                    $total[$company] = $totalValue;
                }
            }


            foreach ($total as $key => $value) {
                RevenueReport::UpdateOrCreate(['customer_id' => $this->customerId, 'company_id' => $key, 'cycle' => $startDate],['customer_id' => $this->customerId, 'company_id' => $key, 'total' => $value,'cycle' => $startDate]);
            }
        } catch (\Exception $ex ){
            dd($ex->getMessage());
        }


    }


}
