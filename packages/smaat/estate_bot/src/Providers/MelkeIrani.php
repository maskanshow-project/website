<?php

namespace SmaaT\EstateBot\Providers;

use App\Models\Estate\EstateType;
use Cache;
use Exception;
use SmaaT\EstateBot\Enum;
use SmaaT\EstateBot\EstateBot;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use SmaaT\EstateBot\Models\EstateBotProvider;
use Symfony\Component\DomCrawler\Crawler;
use thiagoalessio\TesseractOCR\TesseractOCR;

class MelkeIrani extends EstateBot
{
    public $base_url = "http://www.melkeirani.com/";

    public $provider = Enum::MelkeIrani;

    public function get_crawler($url)
    {
        $client = new Client();
        $cookieJar = CookieJar::fromArray([
            'PHPSESSID' => $this->get_auth_token()
        ], 'www.melkeirani.com');

        $res = $client->request('get', $url, ['cookies' => $cookieJar]);

        $this->crawler = new Crawler($res->getBody()->__toString());
    }

    public function get_auth_token()
    {
        return Cache::rememberForever("estate_bot." . $this->provider . ".token", function () {
            $provider = EstateBotProvider::whereProvider($this->provider)->first(['username', 'password', 'base_url']);
            $captcha = $this->get_captcha_code();

            $res = (new Client())->request('POST', "http://www.melkeirani.com/login.php", [
                'allow_redirects' => false,
                'form_params' => [
                    'mob'       => $provider->username,
                    'pass'      => $provider->password,
                    'cpt1'      => $captcha,
                    'cpt2'      => $captcha,
                    'sendlogin' => "ورود"
                ]
            ]);

            return substr($res->getHeader('Set-cookie')[0], 10, 26);
        });
    }

    public function get_captcha_url()
    {
        $client = new Client();
        $cookieJar = CookieJar::fromArray([
            'PHPSESSID' => "q6lekbod7l6hkb11nnnf66oe36"
        ], 'www.melkeirani.com');

        $res = $client->request('get', "http://www.melkeirani.com/login.php", ['cookies' => $cookieJar]);
        $crawler = new Crawler($res->getBody()->__toString(), $this->base_url);

        return $this->base_url . $crawler->filter("img.cptimg")->first()->attr("src");
    }

    public function get_captcha_code()
    {
        $client = new Client();
        $cookieJar = CookieJar::fromArray([
            'PHPSESSID' => "q6lekbod7l6hkb11nnnf66oe36"
        ], 'www.melkeirani.com');

        $res = $client->request('get', $this->get_captcha_url(), ['cookies' => $cookieJar]);
        file_put_contents(__DIR__ . "\\captcha.png", $res->getBody());
        return (new TesseractOCR(__DIR__ . "\\captcha.png"))->run();
    }

    public function merge_new_links($new_links)
    {
        $this->links = $new_links;
    }

    public function page_link(int $page = 1): String
    {
        return $this->base_url . "cont.php?page=$page";
    }

    public function links_selector(): String
    {
        return 'a.melklink';
    }

    public function assignment_selector(): String
    {
        return '.sitem .sitemtitr .stitr:nth-of-type(2)';
    }

    public function estate_type_selector(): String
    {
        return $this->assignment_selector();
    }

    public function features_selector(): String
    {
        return '.sitem .addr .onv3';
    }

    public function sales_price_selector(): String
    {
        return '.sitem .sprice .sfee';
    }

    public function mortgage_price_selector(): String
    {
        return '.sitem .sprice .srahn';
    }

    public function rental_price_selector(): String
    {
        return '.sitem .sprice .shire';
    }

    public function address_selector(): String
    {
        return '.sitem .addr .address';
    }

    public function street_selector(): String
    {
        return $this->address_selector();
    }

    public function area_selector(): String
    {
        return '.sitem .sinfo .infotitr';
    }

    public function description_selector(): String
    {
        return '.sitem .addr .onv3';
    }

    public function landlord_fullname_selector(): String
    {
        return '.sitem .contact .malek';
    }

    public function landlord_phone_number_selector(): String
    {
        return '.sitem .contact .malek';
    }

    public function plaque_selector(): String
    {
        return $this->address_selector();
    }

    public function specification_selector(): String
    {
        return '.sitem .infotitr';
    }

    public function coordinates_selector(): String
    {
        return 'script';
    }

    protected function features_nodes()
    {
        return $this->filter_first_valid_node(
            $this->crawler->filter($this->features_selector())->each(function ($node) {
                if (strpos($node->text(), "امکانات") !== false)
                    return $node->parents();
            })
        );
    }

    protected function coordinates_node()
    {
        return $this->filter_first_valid_node(
            $this->crawler->filter($this->coordinates_selector())->each(function ($node) {
                if (strpos($node->text(), "googleMap") !== false)
                    return $node;
            })
        );
    }

    protected function description_node()
    {
        return $this->filter_first_valid_node(
            $this->crawler->filter($this->description_selector())->each(function ($node) {
                if (strpos($node->text(), "توضیحات") !== false)
                    return $node->parents();
            })
        );
    }

    protected function landlord_phone_number_node()
    {
        return $this->crawler->filter($this->landlord_phone_number_selector())->eq(1);
    }

    protected function parse_description(): String
    {
        return str_replace("توضیحات:", '', parent::parse_description());
    }

    protected function parse_landlord_fullname(): String
    {
        $fullname = parent::parse_landlord_fullname();

        if (!$fullname) {
            Cache::pull("estate_bot." . $this->provider . ".token");
            throw new Exception("The Melke Irani Website token has expired !");
        }

        return str_replace("نام:", '', parent::parse_landlord_fullname());
    }

    protected function parse_landlord_phone_number(): String
    {
        return str_replace("موبایل:", '', parent::parse_landlord_phone_number());
    }

    protected function parse_area(): int
    {
        return (int) str_replace("مساحت:", '', $this->node_text($this->area_node()));
    }

    protected function parse_address(): ?String
    {
        return str_replace("آدرس ملک:", '', parent::parse_address());
    }

    protected function parse_specifications(EstateType $estate_type)
    {
        $specs = parent::parse_specifications($estate_type);

        $this->handle_heating_field_in_features($estate_type, $specs);

        return $specs;
    }

    protected function handle_heating_field_in_features(EstateType $estate_type, &$specs)
    {
        if ($estate_type->spec->rows ?? false) {
            $row = $estate_type->spec->rows->first(function ($i) {
                return $this->compare_value($i, 'گرمایش');
            });

            if ($row->defaults ?? false) {
                $res = $this->filter_first_valid_node($this->features_nodes()->each(function ($i) use ($row) {
                    return ($v = $this->parse_item($row->defaults, $this->node_text($i), 'value')) ? [
                        'id' => $row->id,
                        'value' => $v->id
                    ] : null;
                }));

                if ($res) $specs[] = $res;
            }
        }
    }

    protected function parse_features(): array
    {
        return $this->parse_collection($this->features, $this->node_text($this->features_nodes()->first()))->map(function ($item) {
            return $item->id;
        })->values()->toArray();
    }
}
