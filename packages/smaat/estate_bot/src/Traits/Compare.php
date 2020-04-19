<?php

namespace SmaaT\EstateBot\Traits;

trait Compare
{
    public function parse_collection($collection, $value, String $field = 'title', bool $has_similar = true)
    {
        if (!$value) return collect();

        return $collection->filter(function ($item) use ($value, $field, $has_similar) {
            return $this->compare_value($item, $value, $field, $has_similar);
        });
    }

    public function parse_item($collection, $value, String $field = 'title', bool $has_similar = true)
    {
        if (!$value) return null;

        return $collection->first(function ($item) use ($value, $field, $has_similar) {
            return $this->compare_value($item, $value, $field, $has_similar);
        });
    }

    public function compare_value($item, $value, String $field = 'title', bool $has_similar = true): bool
    {
        if ($has_similar && $item->similar_titles && $this->has_similar_value($item, $value))
            return true;

        return strpos($this->remove_ws($value), $this->remove_ws($item->$field)) !== false;
    }

    public function has_similar_value($item, $value): bool
    {
        return count(array_filter($item->similar_titles, function ($i) use ($value) {
            return strpos($this->remove_ws($value), $this->remove_ws($i)) !== false;
        })) !== 0;
    }

    public function remove_ws($value): String
    {
        return preg_replace('/\s+/', '', $value);
    }
}
