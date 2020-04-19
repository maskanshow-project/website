<?php

namespace SmaaT\EstateBot\Traits;

use App\Models\Estate\Assignment;
use App\Models\Estate\EstateType;
use App\Models\Places\Street;

trait Parser
{
    public function get_crawler($url)
    {
        $this->crawler = $this->client->request('GET', $url);
    }

    public function prase_url_to_json(String $url)
    {
        $this->get_crawler($url);

        return [
            'landlord_fullname'         => $this->parse_landlord_fullname(),
            'landlord_phone_number'     => $this->parse_landlord_phone_number(),
            'plaque'                    => $this->parse_plaque(),

            'assignment_id'             => ($assignment = $this->parse_assignment())->id ?? null,
            'estate_type_id'            => ($estate_type = $this->parse_estate_type())->id ?? null,
            'street_id'                 => $this->parse_street()->id ?? null,

            'area'                      => $this->parse_area(),
            'description'               => $this->parse_description(),
            'coordinates'               => $coordinates = $this->parse_coordinates(),
            'lat'                       => $coordinates['lat'] ?? null,
            'lng'                       => $coordinates['lng'] ?? null,
            'features'                  => $this->parse_features(),
            'sales_price'               => $this->parse_sales_price($assignment),
            'mortgage_price'            => $this->parse_mortgage_price($assignment),
            'rental_price'              => $this->parse_rental_price($assignment),
            'address'                   => $this->parse_address(),
            'specs'                     => $this->parse_specifications($estate_type)
        ];
    }

    protected function parse_address(): ?String
    {
        return trim(str_replace('پلاک', '', preg_replace('/پلاک \d+/', '', $this->node_text($this->address_node()))));
    }

    protected function parse_area(): int
    {
        preg_match('/\d+/', $this->fa_nums_to_en($this->node_text($this->area_node())), $res);

        return (int) ($res[0] ?? 0);
    }

    protected function parse_description(): String
    {
        return $this->node_text($this->description_node());
    }

    protected function parse_landlord_fullname(): String
    {
        return $this->node_text($this->landlord_fullname_node());
    }

    protected function parse_landlord_phone_number(): String
    {
        return $this->fa_nums_to_en($this->node_text($this->landlord_phone_number_node()));
    }

    protected function parse_plaque(): String
    {
        preg_match('/پلاک (\d+)/', $this->node_text($this->address_node()), $res);

        return ($res[1] ?? 0);
    }

    protected function parse_street(): ?Street
    {
        return $this->parse_street_item($this->streets, $this->node_text($this->street_node()), 'name', false);
    }

    public function parse_street_item($collection, $value, String $field = 'title', bool $has_similar = true)
    {
        if (!$value) return null;

        return $collection->first(function ($item) use ($value, $field, $has_similar) {
            return $item->regex ? preg_match($item->regex, $value) : $this->compare_value($item, $value, $field, $has_similar);
        });
    }

    protected function parse_assignment(): ?Assignment
    {
        return $this->parse_item($this->assignments, $this->node_text($this->assignment_node()));
    }

    protected function parse_estate_type(): ?EstateType
    {
        return $this->parse_item($this->estate_types, $this->node_text($this->estate_type_node()));
    }

    protected function parse_features(): array
    {
        return collect(
            $this->features_nodes()->each(function ($node) {
                return $this->parse_item($this->features, $this->node_text($node));
            })
        )->filter(function ($i) {
            return $i;
        })->map(function ($i) {
            return $i->id;
        })->values()->toArray();
    }

    protected function parse_coordinates(): ?array
    {
        preg_match('/([0-9]{2}.[0-9]{2,}), ([0-9]{2}.[0-9]{2,})/', $this->node_text($this->coordinates_node()), $res);

        return isset($res[1]) && isset($res[2]) ? [
            'lat' => $res[1],
            'lng' => $res[2],
        ] : null;
    }

    protected function parse_sales_price($assignment): ?int
    {
        return $assignment->has_sales_price ? $this->price_node_text($this->sales_price_node()) : null;
    }

    protected function parse_mortgage_price($assignment): ?int
    {
        return $assignment->has_mortgage_price ? $this->price_node_text($this->mortgage_price_node()) : null;
    }

    protected function parse_rental_price($assignment): ?int
    {
        return $assignment->has_rental_price ? $this->price_node_text($this->rental_price_node()) : null;
    }

    protected function parse_specifications(EstateType $estate_type)
    {
        $result = collect();

        if (!$estate_type->spec || !$estate_type->spec->rows) return $result;

        $this->specification_nodes()->each(function ($node) use ($estate_type, &$result) {
            if ($res = $this->compare_specification_row($estate_type->spec->rows, $node))
                $result->add($res);
        });

        return $result->toArray();
    }

    protected function parse_specification_item($node): array
    {
        preg_match_all('/(.+):(.+)/', $this->node_text($node), $res, PREG_PATTERN_ORDER);

        return [
            'title' => $res[1][0] ?? "",
            'value' => $res[2][0] ?? null
        ];
    }

    protected function format_specification_value($item, $value)
    {
        if ($item->prefix)
            $value = str_replace($item->prefix, '', $value);

        if ($item->postfix)
            $value = str_replace($item->postfix, '', $value);

        return trim($this->fa_nums_to_en($value));
    }

    protected function get_specification_value($item, $value)
    {
        $value = $this->format_specification_value($item, $value);

        return $item->defaults->isNotEmpty()
            ? [
                'id' => $item->id,
                'value' => $this->parse_item($item->defaults, $value, 'value')->id ?? null,
            ]
            : [
                'id' => $item->id,
                'data' => $value
            ];
    }

    protected function compare_specification_row($rows, $node)
    {
        return $rows->map(function ($item) use ($node, &$result) {
            $node = $this->parse_specification_item($node);

            if ($this->compare_value($item, $node['title'])) {
                $value = $this->get_specification_value($item, $node['value']);
                return (($value['value'] ?? false) || ($value['data'] ?? false)) ? $value : null;
            }
        })->first(function ($item) {
            return $item;
        });
    }
}
