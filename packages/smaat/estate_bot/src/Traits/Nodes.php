<?php

namespace SmaaT\EstateBot\Traits;

trait Nodes
{
    protected function filter_first_valid_node($nodes)
    {
        return array_values(
            array_filter($nodes, function ($i) {
                return $i;
            })
        )[0] ?? null;
    }

    public function node_text($node): String
    {
        return $node && $node->count() !== 0 ? $node->text() : "";
    }

    public function fa_nums_to_en($string)
    {
        return strtr($string, array('۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
    }

    protected function replace_price_labels($text)
    {
        return str_replace('میلیارد', '000000000', str_replace('میلیون', '000000', str_replace('هزار', '000', $text)));
    }

    protected function remove_space_comma($text)
    {
        return str_replace(' ', '', str_replace(',', '', ($text)));
    }

    protected function price_node_text($node): int
    {
        preg_match('/\d+/', $this->replace_price_labels($this->fa_nums_to_en($this->remove_space_comma($this->node_text($node)))), $res);

        return (int) $res[0] ?? false;
    }

    protected function street_node()
    {
        return $this->crawler->filter($this->street_selector())->first();
    }

    protected function address_node()
    {
        return $this->crawler->filter($this->address_selector())->first();
    }

    protected function area_node()
    {
        return $this->crawler->filter($this->area_selector())->first();
    }

    protected function description_node()
    {
        return $this->crawler->filter($this->description_selector())->first();
    }

    protected function landlord_fullname_node()
    {
        return $this->crawler->filter($this->landlord_fullname_selector())->first();
    }

    protected function landlord_phone_number_node()
    {
        return $this->crawler->filter($this->landlord_phone_number_selector())->first();
    }

    protected function plaque_node()
    {
        return $this->crawler->filter($this->plaque_selector())->first();
    }

    protected function assignment_node()
    {
        return $this->crawler->filter($this->assignment_selector())->first();
    }

    protected function estate_type_node()
    {
        return $this->crawler->filter($this->estate_type_selector())->first();
    }

    protected function features_nodes()
    {
        return $this->crawler->filter($this->features_selector());
    }

    protected function coordinates_node()
    {
        return $this->crawler->filter($this->coordinates_selector())->first();
    }

    protected function specification_nodes()
    {
        return $this->crawler->filter($this->specification_selector());
    }

    protected function sales_price_node()
    {
        return $this->crawler->filter($this->sales_price_selector())->first();
    }

    protected function mortgage_price_node()
    {
        return $this->crawler->filter($this->mortgage_price_selector())->first();
    }

    protected function rental_price_node()
    {
        return $this->crawler->filter($this->rental_price_selector())->first();
    }
}
