<?php

use App\Helpers\CustomSeeder;

use App\Models\Spec\Spec;
use App\User;
use App\Models\Estate\Estate;
use App\Models\Estate\Feature;

class EstateTablesSeeder extends CustomSeeder
{
    public $estates;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        auth()->loginUsingId( User::first()->id );

        $spec = Spec::all()->random();

        $this->createEstates($spec->id);

        $estate = Estate::latest()->first();

        $estate->features()->attach( Feature::all()->take( rand(3, 10) ) );

        $spec->headers->each( function($header) use($estate) {
            
            $header->rows->each( function( $spec_row ) use($estate) {

                $values = $spec_row->defaults;

                $data = $estate->spec_data()->save(
                    factory(\App\Models\Spec\SpecData::class)->make([
                        'spec_row_id'   => $spec_row->id
                    ])
                );

                if ( $values->isNotEmpty() )
                    $data->values()->saveMany( $values->take( rand(1, 5) ) );
            });
        });
    }

    public function createEstates($spec_id)
    {
        return $this->estates = $this->createTable(
            Estate::class,
            [ 'id', 'title', 'code', 'label_id', 'jalali_created_at' ],
            [
                'spec_id'       => $spec_id,
            ],
            'estate', null, 1
        );
    }
}