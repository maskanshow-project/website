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

        return $collection->sortByDesc(function ($i) use ($field) {
            return strlen($i->$field);
        })->first(function ($item) use ($value, $field, $has_similar) {
            return $this->compare_value($item, $value, $field, $has_similar);
        });
    }

    public function compare_value($item, $value, String $field = 'title', bool $has_similar = true): bool
    {
        if ($has_similar && $item->similar_titles && $this->has_similar_value($item, $value))
            return true;

        return strpos($this->optimize_value($value), $this->optimize_value($item->$field)) !== false;
    }

    public function has_similar_value($item, $value): bool
    {
        return count(array_filter($item->similar_titles, function ($i) use ($value) {
            return strpos($this->optimize_value($value), $this->optimize_value($i)) !== false;
        })) !== 0;
    }

    public function optimize_value($value): String
    {
        return $this->fa_nums_to_en($this->remove_ws($value));
    }

    public function remove_ws($value): String
    {
        return preg_replace('/\s+/', '', $value);
    }
}
