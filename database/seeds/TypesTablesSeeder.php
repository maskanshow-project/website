<?php

use Illuminate\Database\Seeder;
use App\Models\Estate\Estate;
use App\Models\Estate\Assignment;
use App\Models\Estate\EstateType;
use App\User;
use App\Models\Estate\Feature;

class TypesTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        auth()->login( User::first() );

        Assignment::whereNotNull('id')->delete();
        EstateType::whereNotNull('id')->delete();
        Feature::whereNotNull('id')->delete();

        Assignment::create([
            'title' => 'رهن کامل',
            'color' => '#ff3d3d',
            'has_sales_price' => false,
            'has_mortgage_price' => true,
            'has_rental_price' => false,
        ]);
            
        Assignment::create([
            'title' => 'رهن و اجاره',
            'color' => '#fd7e14',
            'has_sales_price' => false,
            'has_mortgage_price' => true,
            'has_rental_price' => true,
        ]);

        Assignment::create([
            'title' => 'اجاره',
            'color' => '#20c997',
            'has_sales_price' => false,
            'has_mortgage_price' => false,
            'has_rental_price' => true,
        ]);

        Assignment::create([
            'title' => 'خرید',
            'color' => '#007bff',
            'has_sales_price' => true,
            'has_mortgage_price' => false,
            'has_rental_price' => false,
        ]);

        $assigment_types = Assignment::all()->pluck('id');

        $estate_types = [
            'آپارتمان',
            'دفتر کار',
            'زمین',
            'ویلایی',
            'مغازه'
        ];

        foreach($estate_types as $type)
        {
            $estate_type = EstateType::create([ 'title' => $type ]);

            $estate_type->assignments()->attach( $assigment_types );
        }

        $features = [
            [
                'title' => 'پارکینگ',
                'icon' => 'announcement'
            ],
            [
                'title' => 'کولر گازی',
                'icon' => 'airplay'
            ],
            [
                'title' => 'آسانسور',
                'icon' => 'accessibility_new'
            ],
            [
                'title' => 'نوساز',
                'icon' => 'find_in_page'
            ],
            [
                'title' => 'سند شش دانگ',
                'icon' => 'extension'
            ],
            [
                'title' => 'انباری',
                'icon' => 'flight_land'
            ],
            [
                'title' => 'گاز رومیزی',
                'icon' => 'important_devices'
            ],
            [
                'title' => 'نقاشی',
                'icon' => 'record_voice_over'
            ],
            [
                'title' => 'کاغذ دیواری',
                'icon' => 'speaker_notes_off'
            ],
            [
                'title' => 'کف پارکت',
                'icon' => 'star_rate'
            ],
            [
                'title' => 'پنجره دوجداره',
                'icon' => 'thumbs_up_down'
            ],
        ];

        $estate_types = EstateType::all();

        foreach($features as $item)
        {
            $feature = Feature::create([
                'title' => $item['title'],
                'icon' => $item['icon'],
                'description' => \Faker::sentence()
            ]);

            $feature->estate_types()->attach( $estate_types->take( rand(1, 5) )->pluck('id') );
        }
    }
}
