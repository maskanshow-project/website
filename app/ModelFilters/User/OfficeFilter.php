<?php namespace App\ModelFilters\User;

use App\ModelFilters\MainFilter;
use App\ModelFilters\Query;
use App\ModelFilters\SimpleOrdering;

class OfficeFilter extends MainFilter
{
    use Query, SimpleOrdering;

    /**
     * Define the search fields of this data type filter class 
     *
     * @var array
     */
    protected $ordering_items = [
        'comments'              => 'relation',
    ];

    /**
     * Filter the Users base on it's address
     *
     * @param string $code
     * @return Builder
     */
    public function address($address)
    {
        return $this->whereLike('address', $address);
    }

    /**
     * Filter the users that locate in specific cities
     *
     * @param string $id
     * @return Builder
     */
    public function areas($ids)
    {
        return $this->filter_relation('area', $ids);
    }
}
