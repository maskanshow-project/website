<?php

namespace App\ModelFilters\Financial;

use App\ModelFilters\MainFilter;
use App\ModelFilters\Query;
use App\ModelFilters\SimpleOrdering;

class PlanFilter extends MainFilter
{
    use Query, SimpleOrdering;
    
    /**
     * Define the search fields of this data type filter class 
     *
     * @var array
     */
    protected $ordering_items = [
        'price'                 => 'field',
        'visited_estate_count'  => 'field',
        'credit_days_count'     => 'field',
    ];
       
    /**
     * Filter the Data that have active or not
     *
     * @param boolean $status
     * @return Builder
     */
    public function isActive($status)
    {
        return $this->where('is_active', $status);
    }
}
