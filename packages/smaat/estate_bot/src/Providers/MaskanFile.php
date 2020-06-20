<?php

namespace SmaaT\EstateBot\Providers;

use App\Models\Estate\EstateType;
use Cache;
use Exception;
use Illuminate\Support\Facades\Http;
use SmaaT\EstateBot\Enum;
use SmaaT\EstateBot\EstateBot;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use SmaaT\EstateBot\Models\EstateBotProvider;
use Symfony\Component\DomCrawler\Crawler;

class MaskanFile extends EstateBot
{
	public $base_url = "http://www.maskan-file.ir/";

	public $provider = Enum::MaskanFile;

	public function get_crawler($url)
	{
		$client = new Client();
		$cookieJar = CookieJar::fromArray([
			'.ASPXAUTH' => $this->get_auth_token()
		], 'www.maskan-file.ir');

		$res = $client->request('get', $url, ['cookies' => $cookieJar]);

		$this->crawler = new Crawler($res->getBody()->__toString());
	}

	public function get_auth_token()
	{
		return Cache::rememberForever("estate_bot." . $this->provider . ".token", function () {
			$provider = EstateBotProvider::whereProvider($this->provider)->first(['username', 'password', 'base_url']);

			$res = (new Client())->request('POST', "http://www.maskan-file.ir/Pages/login.aspx", [
				'allow_redirects' => false,
				'form_params' => [
					'__VIEWSTATE'           => '/wEPDwUKMjAwODU2MzAwM2RkFCI7DuaNHZ0v9HXqJke5f2X0qyt3KQcXNLudq3/7LNE=',
					'__VIEWSTATEGENERATOR'  => 'D44F3332',
					'__EVENTVALIDATION'     => '/wEdAASR+5CNRqRoOM3ys17W2l96uVErpQ5Xp8qDQVOWvhT9J4fylL8cKseIu5MoiqpFsSU6L5U6WCMidpWPP9q/bjKeIj1xhI+kPkLk3aKsSh3D2Xv3OXAtnpQ4KTbhxw4aEpA=',
					'Txt_UN'                => $provider->username,
					'Txt_Pas'               => $provider->password,
					'BtnLog'                => "ورود"
				]
			]);

			preg_match('/ASPXAUTH=([^;]+)/', $res->getHeader('Set-cookie')[1], $res);

			return $res[1] ?? "";
		});
	}

	public function page_link(int $page = 1): String
	{
		return $this->base_url . "cont.php?page=1";
	}

	public function links_selector(): String
	{
		return 'a.melklink';
	}

	public function assignment_selector(): String
	{
		return '.container .card-header .type span';
	}

	public function estate_type_selector(): String
	{
		return $this->assignment_selector();
	}

	public function features_selector(): String
	{
		return '.container .card .Facilities .Tik';
	}

	public function sales_price_selector(): String
	{
		return '.container .card-body h4';
	}

	public function mortgage_price_selector(): String
	{
		return '.container .card-body h6';
	}

	public function rental_price_selector(): String
	{
		return '.container .card-body h5';
	}

	public function address_selector(): String
	{
		return '.container .card-body p';
	}

	public function street_selector(): String
	{
		return $this->address_selector();
	}

	public function area_selector(): String
	{
		return '.container .card-body .Metrazh';
	}

	public function description_selector(): String
	{
		return '.container .Div_Grv .DescMelk';
	}

	public function landlord_fullname_selector(): String
	{
		return '.container .card #ContentPlaceHolder1_MalekConfig p';
	}

	public function landlord_phone_number_selector(): String
	{
		return $this->landlord_fullname_selector();
	}

	public function plaque_selector(): String
	{
		return $this->address_selector();
	}

	public function specification_selector(): String
	{
		return '.container .card .ConfigMelk ul li';
	}

	public function coordinates_selector(): String
	{
		return '';
	}

	protected function landlord_phone_number_node()
	{
		return $this->crawler->filter($this->landlord_phone_number_selector())->eq(1);
	}

	protected function parse_description(): String
	{
		return trim(str_replace("توضیحات ملک :", '', parent::parse_description()));
	}

	protected function parse_landlord_phone_number(): String
	{
		return trim(str_replace("تلفن تماس : ", '', parent::parse_landlord_phone_number()));
	}

	protected function parse_landlord_fullname(): String
	{
		$fullname = parent::parse_landlord_fullname();

		if (!$fullname) {
			Cache::pull("estate_bot." . $this->provider . ".token");
			throw new Exception("The Maskan File Website token has expired !");
		}

		return str_replace("نام مالک : ", '', parent::parse_landlord_fullname());
	}

	protected function parse_coordinates(): ?array
	{
		return null;
	}

	protected function parse_address(): ?String
	{
		return str_replace("آدرس ملک:", '', parent::parse_address());
	}

	protected function parse_specifications(EstateType $estate_type)
	{
		$specs = parent::parse_specifications($estate_type);

		$this->handle_document_field_in_features($estate_type, $specs);

		return $specs;
	}

	protected function handle_document_field_in_features(EstateType $estate_type, &$specs)
	{
		if ($estate_type->spec->rows ?? false) {
			$row = $estate_type->spec->rows->first(function ($i) {
				return $this->compare_value($i, 'سند');
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

	public function store_index_page_links()
	{
		$page = 1;
		$this->links = collect();

		while ($page <= $this->max_page) {
			$res = $this->uncrawled_links($this->get_certain_page_links($page++));
			$this->merge_new_links($res['links']);
			if (!$res['has_more']) break;
		}

		$this->store_all_links($this->links);
	}

	public function get_certain_page_links(int $page = 1): array
	{
		$res = Http::withHeaders([
			'content-type' => 'application/json'
		])->post("http://www.maskan-file.ir/pages/homes.aspx/GetAllMelkForUser", [
			'NoeMelk' => '', 'NoeVagozari' => '', 'MinRahn' => '', 'MaxRahn' => '', 'MinEjareh' => '', 'MaxEjareh' => '',
			'MinForosh' => '', 'MaxForosh' => '', 'MinMetrazh' => '', 'MaxMetrazh' => '', 'startPageIndex' => $page * 20, 'MinRoom' => '',
			'MaxRoom' => '', 'Mantaghe' => '', 'Mantaghe2' => '', 'Mantaghe3' => '', 'Mantaghe4' => '', 'Mantaghe5' => '',
			'Mantaghe6' => '', 'Mantaghe7' => '', 'Mantaghe8' => '', 'Mantaghe9' => '', 'MinFloor' => '', 'MaxFloor' => '',
			'MinFloors' => '', 'MaxFloors' => '', 'MinUnit' => '', 'MaxUnit' => '', 'MinAge' => '', 'MaxAge' => '', 'JahateMelk' => '',
			'North' => '', 'South' => '', 'TwoCorners' => '', 'TowWay' => '', 'Oriental' => '', 'Western' => '', 'ThreeCorners' => '',
			'Document' => '', 'Total' => '', 'Half' => '', 'Proxy' => '', 'Promise' => '', 'Asansor' => '', 'Parking' => '',
			'HozoreMalek' => '', 'DarBarghi' => '', 'Teras' => '', 'Anbari' => '', 'malek' => '', 'tel' => '',
			'addressWord' => '', 'date' => '', 'RegisterType' => '', 'Darbast' => '', 'EmtiazatMostaghel' => ''
		]);

		$links = collect(explode('[&]', $res->json()['d']))->map(function ($i) {
			if (!$i) return;
			return $this->base_url . "home-" . explode('[#]', $i)[0];
		});
		$links->pop();

		return $links->toArray();
	}
}
