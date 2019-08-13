<?php

namespace App\GraphQL\Type\Estate;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Estate\Estate;

class EstateType extends BaseType
{
    private $can_see_detail = null;

    private $selected_estate = null;
    
    protected $attributes = [
        'name' => 'EstateType',
        'description' => 'A type',
        'model' => Estate::class
    ];

    public function handleCredit($args)
    {
        if ( !isset($args['id']) ) return false;

        if ( $this->selected_estate !== $args['id'] )
        {
            $this->can_see_detail = null;
            $this->selected_estate = $args['id'];
        }

        if ( $this->can_see_detail !== null )
            return $this->can_see_detail;    

        if ( auth()->guest() )
            return $this->can_see_detail = false;
        

        $estate = Estate::select('user_id', 'role_id')->findOrFail( $args['id'] );

        if ( $estate->user_id === auth()->id() )
            $this->can_see_detail = true;
            
        elseif ( $estate->role_id === 2 )
            $this->can_see_detail = false;

        elseif ( auth()->user()->visitedEstates()->where('id', $args['id'] ?? false)->count() )
            $this->can_see_detail = true;

        elseif( auth()->user()->remaining_hits_count > 0 && !auth()->user()->validity_end_at->isPast() )
        {
            auth()->user()->increment('visited_estate_count');
            auth()->user()->decrement('remaining_hits_count');

            auth()->user()->visitedEstates()->attach($args['id'], [
                'visited_at' => jdate()
            ]);

            $this->can_see_detail = true;
        }
        else
            $this->can_see_detail = false;

        return $this->can_see_detail;
    }

    public function get_fields()
    {
        return [
            'is_mine' => $this->isMineField(),
            'creator' => [
                'type' => \GraphQL::type('user'),
            ],
            'office' => [
                'type' => \GraphQL::type('office'),
            ],
            'code' => [
                'type' => Type::string()
            ],
            'title' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'description' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'aparat_video' => [
                'type' => Type::string()
            ],
            'sales_price' => [
                'type' => Type::string()
            ],
            'mortgage_price' => [
                'type' => Type::string()
            ],
            'rental_price' => [
                'type' => Type::string()
            ],
            'area' => [
                'type' => Type::int()
            ],
            'landlord_fullname' => [
                'type' => Type::string(),
                'privacy' => function($args) {
                    return $this->checkPermission("see-details-estate") || $this->handleCredit($args);
                }
            ],
            'landlord_phone_number' => [
                'type' => Type::string(),
                'privacy' => function($args) {
                    return $this->checkPermission("see-details-estate") || $this->handleCredit($args);
                }
            ],
            'address' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'plaque' => [
                'type' => Type::float(),
                'selectable' => false,
                'privacy' => function($args) {
                    return $this->checkPermission("see-details-estate") || $this->handleCredit($args);
                }
            ],
            'coordinates' => [
                'type' => \GraphQL::type('coordinate'),
                'is_relation' => false,
                'privacy' => function($args) {
                    return $this->checkPermission("see-details-estate") || $this->handleCredit($args);
                }
            ],
            'advantages' => [
                'type' => Type::listOf( Type::string() ),
                'selectable' => false
            ],
            'disadvantages' => [
                'type' => Type::listOf( Type::string() ),
                'selectable' => false
            ],
            'photos' => [
                'type' => Type::listOf( \GraphQL::type('single_media') ),
            ],
            'views_count' => [
                'type' => Type::int()
            ],
            'tags' => [
                'type' => Type::listOf( \GraphQl::type('tag') ),
            ],
            'assignment_count' => [
                'type' => Type::int(),
            ],
            'assignmented_at' => [
                'type' => Type::string(),
            ],
            'spec_values' => [
                'type' => \GraphQL::type('spec_default')
            ],
            'want_cooperation' => [
                'type' => Type::boolean()
            ],
            'votes' => $this->votes(),
            'specifications' => [
                'type'  => Type::listOf( \GraphQL::type('spec_data') ),
                'query' => function(array $args, $query) {

                    return $query->whereHas('row', function($query) {
                        return $query->where('is_detailable', true);
                    });
                }
            ],
            'spec' => $this->relationItemField('spec'),
            'street' => $this->relationItemField('street'),
            'label' => $this->relationItemField('label'),
            'registrar_type' => $this->relationItemField('role'),
            'assignment' => $this->relationItemField('assignment'),
            'features' => $this->relationListField('feature'),
            'detailable_features' => $this->relationListField('feature'),
            'estate_type' => $this->relationItemField('estate_type'),
            'audits' => $this->audits('estate'),
            'is_active' => [
                'type' => Type::boolean(),
            ],
            'is_favorite' => [
                'type' => Type::boolean(),
                'selectable' => false,
                'resolve' => function($data) {

                    return auth()->check() ? auth()->user()->favorites()->where('id', $data['id'] ?? false)->count() : false;
                }
            ],
            'reject_reason' => [
                'type' => Type::string(),
                'privacy' => function($args) {

                    return $this->checkPermission("active-estate") || ( $args['registered_by_me'] ?? false );
                }
            ]
        ];
    }
}