<?php

namespace SmaaT\EstateBot;

use Goutte\Client;
use SmaaT\EstateBot\Providers\{MaskanFile, MelkeIrani};
use SmaaT\EstateBot\Traits\{Compare, Loader, Nodes, Parser, Selectors, Store};

abstract class EstateBot
{
    use Compare, Parser, Nodes, Store, Loader, Selectors;

    public $base_url;

    public $provider;

    public static $providers = [
        Enum::MelkeIrani => MelkeIrani::class,
        // Enum::MaskanFile => MaskanFile::class
    ];

    protected $client;

    protected $crawler;

    public function __construct()
    {
        $this->client = new Client();
        $this->load_data();
    }

    abstract public function page_link(int $page = 1): String;
}
