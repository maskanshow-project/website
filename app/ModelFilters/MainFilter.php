<?php namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class MainFilter extends ModelFilter
{
    /**
     * Add an condition to query for checking that
     * the specific feild is null or not
     *
     * @param string $field
     * @param bool $status
     * @return Query
     */
    public function has_field_or_not($field, $status)
    {
        if ( $status )
            return $this->whereNotNull( $field );
        else
            return $this->whereNull( $field );
    }

    /**
     * Add an condition to query for checking that
     * the result data has specific relation or not
     *
     * @param string $field
     * @param bool $status
     * @return Query
     */
    public function has_relation_or_not($relation, $status)
    {
        if ( $status )
            return $this->has($relation);
        else
            return $this->has($relation, '=', 0);
    }

    /**
     * Filter the Data that have specific relation
     *
     * @param string        $relation
     * @param array         $ids
     * @param string|id     $field
     * @return Query
     */
    public function filter_relation($relation, $ids, $field = 'id')
    {
        if ( !$ids ) return;

        return $this->whereHas($relation, function($query) use ($ids, $field)
        {
            $query->whereIn($field, $ids);
        });
    }

    /**
     * Filter the data has field value between min and max
     */
    public function range_filter($field, $values)
    {
        $values = collect($values);

        if ( $values->get('min', false) && $values->get('max', false) )
            return $this->whereBetween($field, [ $values->get('min'), $values->get('max') ]);

        elseif ( $values->get('min', false) )
            return $this->where($field, '>=', $values->get('min') );

        elseif ( $values->get('max', false) )
            return $this->where($field, '<=', $values->get('max') );
    }
}
