<?php

namespace SmaaT\EstateBot\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SmaaT\EstateBot\Models\CrawledLink;
use SmaaT\EstateBot\EstateBot;

class CrawlEstateInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    public $crawledLink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CrawledLink $crawledLink)
    {
        $this->crawledLink = $crawledLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bot = new EstateBot::$providers[$this->crawledLink->provider]();

        return $bot->store(
            $bot->prase_url_to_json($this->crawledLink->link),
            $this->crawledLink
        );
    }
}
