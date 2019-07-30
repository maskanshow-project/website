<?php

use Illuminate\Database\Seeder;
use function GuzzleHttp\json_encode;

class OptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ( \App\Models\Option\SiteSetting::all()->isEmpty() )
        {
            $data = [
                [
                    'name' => 'title',
                    'value' => 'مسکن شو',
                ], [
                    'name' => 'description',
                    'value' => '«مسکن شو» سامانه هوشمندی است که در حوزه «املاک و مستغلات» و «مشاغل ساختمانی» و «اشتغال زایی» وخدمات وابسته به آن، فعالیت می‌کند. مسکن شو به شما امکان فروش، اجاره و خرید ملک مورد نظرتان را می‌دهد.',
                ], [
                    'name' => 'phone',
                    'value' => '09158916794',
                ], [
                    'name' => 'address',
                    'value' => 'خراسان رضوی ، مشهد ، بین امامت ۶ و ۸',
                ], [
                    'name' => 'posters',
                    'value' => json_encode([
                        [
                            'title' => 'عنوان پوستر شماره ۱',
                            'description' => 'توضیحاتی کوتاه درباره ی پوستر شماره ۱',
                            'button' => 'متن دکمه شماره ۱',
                            'link' => null
                        ],
                        [
                            'title' => 'عنوان پوستر شماره ۲',
                            'description' => 'توضیحاتی کوتاه درباره ی پوستر شماره ۲',
                            'button' => 'متن دکمه شماره ۲',
                            'link' => null
                        ],
                        [
                            'title' => 'عنوان پوستر شماره ۳',
                            'description' => 'توضیحاتی کوتاه درباره ی پوستر شماره ۳',
                            'button' => 'متن دکمه شماره ۳',
                            'link' => null
                        ],
                        [
                            'title' => 'عنوان پوستر شماره ۴',
                            'description' => 'توضیحاتی کوتاه درباره ی پوستر شماره ۴',
                            'button' => 'متن دکمه شماره ۴',
                            'link' => null
                        ],
                    ])
                ], [
                    'name' => 'theme_color',
                    'value' => '#29B6F6'
                ],
            ];

            foreach ( $data as $item )
                \App\Models\Option\SiteSetting::create( $item );

            echo "\e[31mWebsite options \e[39mwas \e[32mcreated\n";
        }
    }
}
