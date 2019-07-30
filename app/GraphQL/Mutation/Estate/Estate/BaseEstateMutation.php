<?php

namespace App\GraphQL\Mutation\Estate\Estate;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Estate\EstateProps;
use Rebing\GraphQL\Support\UploadType;
use Spatie\MediaLibrary\Models\Media;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use App\Models\Estate\EstateType;

class BaseEstateMutation extends MainMutation
{
    use EstateProps;
    
    protected $old_spec;
    
    protected $attributes = [
        'name' => 'EstateMutation',
        'description' => 'A mutation'
    ];

    public function getArgs()
    {
        return [            
            'code' => [
                'type' => Type::string()
            ],
            'title' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
            'landlord_fullname' => [
                'type' => Type::string()
            ],
            'landlord_phone_number' => [
                'type' => Type::string()
            ],
            'area' => [
                'type' => Type::int()
            ],

            'street_id' => [
                'type' => Type::int()
            ],
            'address' => [
                'type' => Type::string()
            ],
            'plaque' => [
                'type' => Type::string()
            ],
            'lat' => [
                'type' => Type::float()
            ],
            'lng' => [
                'type' => Type::float()
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

            'label_id' => [
                'type' => Type::int()
            ],
            'assignment_id' => [
                'type' => Type::int()
            ],
            'estate_type_id' => [
                'type' => Type::int()
            ],
            'specs' => [
                'type' => Type::listOf( \GraphQL::type('spec_input') )
            ],
            

            'aparat_video' => [
                'type' => Type::string()
            ],
            'tags' => [
                'type' => Type::listOf( Type::string() )
            ],
            'advantages' => [
                'type' => Type::listOf( Type::string() )
            ],
            'disadvantages' => [
                'type' => Type::listOf( Type::string() )
            ],
            'photos' => [
                'type' => Type::listOf( UploadType::getInstance() )
            ],
            'features' => [
                'type' => Type::listOf( Type::int() )
            ],
            'deleted_images' => [
                'type' => Type::listOf( Type::int() )
            ],
            'want_cooperation' => [
                'type' => Type::boolean()
            ]
        ];
    }
    

    /**
     * Get the portion of request class
     *
     * @param Request $request
     * @return Array $request
     */
    public function getRequest( $request)
    {
        $id = $request->get('estate_type_id');

        return array_merge( $request->all(), [
            'spec_id'       => $id ? ( EstateType::find( $id )->spec->id ?? null ) : null,
            'coordinates'   => new Point( $request->get('lat'), $request->get('lng') ),
            'is_active'     => auth()->user()->can('active-estate'),
            'reject_reason' => null
        ]);
    }

    /**
     * Check the request to it'has image or not,
     * then create a data with appropirate method
     *
     * @param Request $request
     * @return void
     */
    public function storeData($request)
    {
        if ( !auth()->user()->can('create-estate') )
        {
            if ( auth()->user()->remaining_registered_count > 0 && !auth()->user()->validity_end_at->isPast() )
                auth()->user()->decrement('remaining_registered_count');

            else
            {
                die(json_encode([
                    'status' => 400,
                    'true' => 'متاسفانه اعتبار شما برای ثبت ملک کافی نیست',
                ]));
            }
        }

        $estate = $this->createNewModel(
            array_merge($this->getRequest( $request ), [
                'role_id' => auth()->user()->roles()->first()->id ?? null,
                'office_id' => auth()->user()->workplace->id ?? null,
            ])
        );

        if ( $request->get('photos') )
        {
            foreach ( $request->get('photos') as $item )
                $estate->addMedia( $item )->toMediaCollection('photos');
        }

        auth()->user()->increment('registered_estate_count');

        return $estate;
    }

    /**
     * Check the request to it'has image or not,
     * then update the data with appropirate method
     *
     * @param Request $request
     * @return void
     */
    public function updateData($request, $estate)
    {
        $this->old_spec = $estate->spec_id;

        $estate->update( $this->getRequest( $request ) );

        if ( $request->get('photos') )
        {
            foreach ( $request->get('photos') as $item )
                $estate->addMedia( $item )->toMediaCollection('photos');
        }

        if ( $request->get('deleted_images') )
            Media::whereIn('id', $request->get('deleted_images'))->delete();

        return $estate;
    }

    /**
     * The function that get the model and run after the model was created
     *
     * @param Request $request
     * @param Model $estate
     * @return void
     */
    public function afterCreate($request, $estate)
    {
        $estate->attachTags( $request->get('tags') );
        $estate->features()->attach( $request->get('features') );
        
        if ( $request->get('specs', false) && count( $request->get('specs', []) ) )
            $this->createSpecData($request, $estate);
    }

    /**
     * The function that get the model and run after the model was updated
     *
     * @param Request $request
     * @param Model $estate
     * @return void
     */
    public function afterUpdate($request, $estate)
    {
        $estate->syncTags( $request->get('tags') );
        $estate->features()->sync( $request->get('features') );
        
        
        if ( $this->old_spec !== $estate->spec_id )
            $estate->spec_data()->delete();

        if ( $request->get('specs', false) && count( $request->get('specs', []) ) )
            $this->createSpecData($request, $estate);
    }

    /**
     * Create records for specification data for specific product
     *
     * @param Request $request
     * @param id $estate
     * @return void
     */
    public function createSpecData($request, $estate)
    {
        $spec = $estate->spec;

        if ( !$spec ) return;
        
        $spec->load([
            'headers:id,spec_id',
            'headers.rows:id,spec_header_id',
            'headers.rows.defaults:id,spec_row_id',
            'headers.rows.data' => function($query) use($estate) {
                return $query->where('estate_id', $estate->id);
            }
        ]);

        $rows = $spec->headers->map(function($item) {
            return $item->rows;
        })->flatten(1)->keyBy('id');


        foreach ( $request->get('specs') as $key => $item )
        {
            $row = $rows[ $item['id'] ];

            if ( $row->defaults()->count() !== 0 )
            {
                $values = $row->defaults;

                if ( $item['values'] ?? false )
                    $values = $values->whereIn('id', $item['values']);
                    
                elseif ( $item['value'] ?? false )
                    $values = $values->where('id', $item['value']);
                
                else
                    $values = collect([]);
                
                $this->createNewSpecData($row, $estate, null, $values->pluck('id'));
            }
            else
                $this->createNewSpecData($row, $estate, $item['data']);
        }
    }

    public function createNewSpecData($row, $estate, $data = null, $values = [])
    {
        if ( $row->data )
        {
            $row->data->update([ 'data' => $data ]);
            $row->data->values()->sync( $values );
        }
        else
        {
            $spec_data = $row->data()->create([
                'estate_id' => $estate->id,
                'data' => $data
            ]);

            $spec_data->values()->sync( $values );
        }
    }
}