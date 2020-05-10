<?php

namespace SmaaT\EstateBot\Traits;

use App\GraphQL\Mutation\Estate\Estate\CreateEstateMutation;
use App\Http\Requests\v1\Estate\EstateRequest;
use SmaaT\EstateBot\Models\CrawledLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SmaaT\EstateBot\Jobs\CrawlEstateInfo;

trait Store
{
    protected $links;

    protected $max_page = 20;

    public function store_index_page_links()
    {
        $page = 1;
        $this->links = collect();

        while ($page <= $this->max_page) {
            $this->crawler = $this->client->request('GET', $this->page_link($page++));
            $res = $this->uncrawled_links();
            $this->merge_new_links($res['links']);
            if (!$res['has_more']) break;
        }

        $this->store_all_links($this->links);
    }

    public function merge_new_links($new_links)
    {
        $this->links = $this->links->merge($new_links);
    }

    public function get_page_links(): array
    {
        return $this->crawler->filter($this->links_selector())->each(function ($node) {
            return $this->base_url . $node->attr('href');
        });
    }

    public function uncrawled_links($links = null): array
    {
        if (!$links)
            $links = $this->get_page_links();;

        return [
            'links' => $diff = array_diff($links, $this->crawled_links($links)),
            'has_more' => count($diff) !== 0
        ];
    }

    public function crawled_links($links): array
    {
        return CrawledLink::select('link')
            ->whereIn('link', $links)->get()
            ->map(function ($i) {
                return $i->link;
            })->toArray();
    }

    public function store_all_links($links)
    {
        foreach ($links as $url) {
            try {
                $this->store_from_url($url);
            } catch (\Throwable $th) {
                echo $th->getMessage() . PHP_EOL;
            }
        }

        echo 'Done :)' . PHP_EOL;
    }

    public function store_from_url($url)
    {
        CrawlEstateInfo::dispatch(
            CrawledLink::create([
                'provider'      => $this->provider,
                'link'          => $url,
                'crawled_at'    => now()
            ])
        )->onQueue('estate_bot');
    }

    public function validate($data)
    {
        if (($validator = Validator::make($data, (new EstateRequest())->rules($data, 'CREATE')))->fails())
            return $validator->errors()->messages();

        return true;
    }

    public function store($data, CrawledLink $crawledLink)
    {
        Auth::loginUsingId("robot");

        if (($validate = $this->validate($data)) !== true) {
            $crawledLink->update(['errors' => $validate]);
            return $validate;
        }

        $estate = (new CreateEstateMutation())->store($data);

        $crawledLink->update(['estate_id' => $estate->id]);

        return $estate;
    }
}
