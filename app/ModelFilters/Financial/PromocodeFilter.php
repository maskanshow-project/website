<?php

namespace App\ModelFilters\Financial;

use App\ModelFilters\MainFilter;
use App\ModelFilters\Query;
use App\ModelFilters\SimpleOrdering;

class PromocodeFilter extends MainFilter
{
    use Query, SimpleOrdering;
    
    /**
     * Define the search fields of this data type filter class 
     *
     * @var array
     */
    protected $ordering_items = [
        'cost'              => 'field',
        'count'             => 'field',
        'remainig_count'    => 'field',
        'expired_at'        => 'field',
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
