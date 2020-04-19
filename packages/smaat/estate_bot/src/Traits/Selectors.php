<?php

namespace SmaaT\EstateBot\Traits;

trait Selectors
{
    abstract public function links_selector(): String;
    abstract public function assignment_selector(): String;
    abstract public function estate_type_selector(): String;
    abstract public function features_selector(): String;
    abstract public function sales_price_selector(): String;
    abstract public function mortgage_price_selector(): String;
    abstract public function rental_price_selector(): String;
    abstract public function address_selector(): String;
    abstract public function area_selector(): String;
    abstract public function description_selector(): String;
    abstract public function landlord_fullname_selector(): String;
    abstract public function landlord_phone_number_selector(): String;
    abstract public function plaque_selector(): String;
    abstract public function specification_selector(): String;
    abstract public function coordinates_selector(): String;
    abstract public function street_selector(): String;
}
