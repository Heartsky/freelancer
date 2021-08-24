<?php

namespace App\Jobs;

use App\Facades\FeedCache;
use App\Facades\FeedFetch;
use App\Facades\WooCache;
use App\Helper\Tracking;
use App\Jobs\SocialShop\FetchFeed\ExecuteFetchGoogleJob;
use App\Models\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SummaryCashJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private  $date;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($date)
    {
        self::onQueue('summary_cash');
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


       $branches = Branch::all();
       foreach ($branches as $branch) {
           SummaryCashBranchJob::dispatch($branch->id, new \DateTime($this->date));
       }

    }


}
