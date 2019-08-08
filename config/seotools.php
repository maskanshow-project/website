<?php

return [
    'meta'      => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'        => "مسکن شو", // set false to total remove
            'description'  => '«مسکن شو» سامانه هوشمندی است که در حوزه «املاک و مستغلات» و «مشاغل ساختمانی» و «اشتغال زایی» وخدمات وابسته به آن، فعالیت می‌کند. مسکن شو به شما امکان فروش، اجاره و خرید ملک مورد نظرتان را می‌دهد.', // set false to total remove
            'separator'    => ' | ',
            'keywords'     => [],
            'canonical'    => false, // Set null for using Url::current(), set false to total remove
            'robots'       => false, // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
        ],
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => 'مسکن شو', // set false to total remove
            'description' => '«مسکن شو» سامانه هوشمندی است که در حوزه «املاک و مستغلات» و «مشاغل ساختمانی» و «اشتغال زایی» وخدمات وابسته به آن، فعالیت می‌کند. مسکن شو به شما امکان فروش، اجاره و خرید ملک مورد نظرتان را می‌دهد.', // set false to total remove
            'url'         => null, // Set null for using Url::current(), set false to total remove
            'type'        => 'website',
            'site_name'   => 'مسکن شو',
            'images'      => ['http://maskanshow.ir/images/site-logo.jpg'],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
          //'card'        => 'summary',
          //'site'        => '@LuizVinicius73',
        ],
    ],
];
