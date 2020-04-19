<?php

namespace SmaaT\EstateBot\Traits;

use App\Models\Estate\{Assignment, Estate, EstateType, Feature};
use App\Models\Places\Street;
use Cache;

trait Loader
{
    protected $assignments;

    protected $estate_types;

    protected $features;

    protected $streets;

    private function load_data()
    {
        $this->load_assignments();
        $this->load_estate_types();
        $this->load_features();
        $this->load_streets();
    }

    private function load_assignments()
    {
        $this->assignments = Cache::remember('estate_bot.assignments', 86400, function () {
            return Assignment::all([
                'id', 'has_sales_price', 'has_mortgage_price', 'has_rental_price',
            ]);
        });
    }

    private function load_estate_types()
    {
        $this->estate_types = Cache::remember('estate_bot.estate_types', 86400, function () {
            return EstateType::with([
                'spec:id,estate_type_id',
                'spec.rows' => function ($query) {
                    return $query->select('spec_rows.id');
                },
                'spec.rows.defaults:id,spec_row_id'
            ])->get(['id']);
        });
    }

    private function load_features()
    {
        $this->features = Cache::remember('estate_bot.features', 86400, function () {
            return Feature::all(['id']);
        });
    }

    private function load_streets()
    {
        $this->streets = Cache::remember('estate_bot.streets', 86400, function () {
            return Street::all(['id', 'name', 'regex']);
        });
    }
}
