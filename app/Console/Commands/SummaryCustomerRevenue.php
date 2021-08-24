<?php

namespace App\Console\Commands;

use App\Helper\Common;
use App\Helper\Tracking;
use App\Jobs\SocialShop\BulkOperation\SsCreateBulkOperationJob;
use App\Jobs\SocialShop\Facebook\CreateFeed\GetFacebookSubmitFeed;
use App\Jobs\SocialShop\Facebook\Submit\SsFacebookSubmitProductJob;
use App\Jobs\SocialShop\Google\CreateFeed\GetGoogleSubmitFeed;
use App\Jobs\SocialShop\Google\Submit\SsGoogleSubmitProductJob;
use App\Jobs\SocialShop\SubmitFeed\CheckFeedProductSubmitJob;
use App\Jobs\SocialShop\SubmitFeed\GetScheduleSubmitFeed;
use App\Jobs\SocialShop\Sync\Shopify\SsHandleSaveProductSourceJob;
use App\Jobs\SocialShop\UpdateFeed\CheckFeedProductUpdateJob;
use App\Jobs\SocialShop\UpdateFeed\GetScheduleUpdateFeed;
use App\Jobs\SummaryBankJob;
use App\Jobs\SummaryCashJob;
use App\Jobs\SummaryCustomerJob;
use App\Jobs\WooCommerce\CheckFeedProductRunningJob;
use App\Jobs\WooCommerce\CheckProductChannelJob;
use App\Repository\SsFeedRepository;
use App\Repository\SsShopSettingRepository;
use Exception;
use Illuminate\Console\Command;

class SummaryCustomerRevenue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary:customer-revenue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'summary balance ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        SummaryCustomerJob::dispatch(date("Y-m-d"));
    }



}
