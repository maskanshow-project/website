<?php namespace App\ModelFilters\Estate;

use App\ModelFilters\MainFilter;
use App\ModelFilters\Query;
use App\ModelFilters\SimpleOrdering;
use Carbon\Carbon;

class EstateFilter extends MainFilter
{
    use Query, SimpleOrdering;

    /**
     * Define the search fields of this data type filter class
     *
     * @var array
     */
    protected $ordering_items = [
        'rental_price' => 'feild',
        'sales_price' => 'feild',
        'mortgage_price' => 'feild',
        'favorites'  => 'relation',
        'reviews'  => 'relation',
        'questions'  => 'relation',
        'accessories'  => 'relation',
    ];
    
    /**
     * Define the search fields of this data type filter class 
     *
     * @var array
     */
    protected $search_fields = [
        'name',
        'second_name',
        'description',
    ];

    /**
     * Filter the Estates base on their consultant
     */
    public function consultant($consultant)
    {
        return $this->where('office_id', $consultant);

        // $this->where('office_id', Office::select('id')->whereUsername( $consultant )->firstOrFail()->id );
    }

    /**

     * Filter the Estates base on it's codes
     */
    public function code($code)
    {
        return $this->whereLike('code', $code)->orWhere('estates.id', 'like', "%{$code}%");
    }

    /**
     * Filter the Estates base on it's landloard fullname
     */
    public function landloardFullname($full_name)
    {
        return $this->whereLike('landlord_fullname', $full_name);
    }

    /**
     * Filter the Estates base on it's landloard phone number
     */
    public function landloardPhoneNumber($phone_number)
    {
        return $this->whereLike('landlord_phone_number', $phone_number);
    }

    /**
     * Filter the Estates base on it's created_at
     */
    public function createdAt($datetime)
    {
        $datetime = new Carbon($datetime);

        return $this->where(\DB::raw('DATE(`created_at`)'),  (String) $datetime->toDateString() );
    }
       
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

    /**
     * Filter the Data that have active or not
     *
     * @param boolean $status
     * @return Builder
     */
    public function isAssignmented($status)
    {
        return $this->has_field_or_not('assignmented_at', $status);
    }

    /**
     * Filter the Data that have active or not
     *
     * @param boolean $status
     * @return Builder
     */
    public function hasAssignmentRequest($status)
    {
        return $this->where('assignment_count', '>', 0);
    }

    /**
     * Filter the Products that has advantages or not
     *
     * @param boolean $status
     * @return Builder
     */
    public function hasRejectReason($status)
    {
        return $this->has_field_or_not('reject_reason', $status);
    }

    /**
     * Filter the Products that has advantages or not
     *
     * @param boolean $status
     * @return Builder
     */
    public function registeredByMe($status)
    {
        if ( $status )
            return $this->where('user_id', auth()->id() );

        else
            return $this->where('user_id', '!=', auth()->id() );
    }

    /**
     * Filter the Products that has advantages or not
     *
     * @param boolean $status
     * @return Builder
     */
    public function hasAdvantages($status)
    {
        return $this->has_field_or_not('advantages', $status);
    }

    /**
     * Filter the Products that has disadvantages or not
     *
     * @param boolean $status
     * @return Builder
     */
    public function hasDisadvantages($status)
    {
        return $this->has_field_or_not('disadvantages', $status);
    }

    /**
     * Filter the Products that has video or not
     *
     * @param boolean $logo
     * @return Builder
     */
    public function hasVideo($status)
    {
        return $this->has_field_or_not('aparat_video', $status);
    }

    /**
     * Filter the products that have specification table or not
     *
     * @param boolean $logo
     * @return Builder
     */
    public function hasTable($status)
    {
        return $this->has_relation_or_not('spec_data', $status);
    }

    /**
     * Filter the estates base on the consultant want cooperation or not
     *
     * @param boolean $logo
     * @return Builder
     */
    public function wantCooperation($status)
    {
        return $this->where('want_cooperation', $status);
    }

    /**
     * Filter the estates that has certain areas
     */
    public function areas($ids)
    {
        return $this->filter_relation('street.area', $ids);
    }

    /**
     * Filter the estates that has certain areas
     */
    public function roles($ids)
    {
        return $this->filter_relation('registrar_type', $ids);
    }

    /**
     * Filter the estates that has certain streets
     */
    public function streets($ids)
    {
        return $this->filter_relation('street', $ids);
    }

    /**
     * Filter the estates that has certain assignments
     */
    public function assignment($id)
    {
        return $this->where('assignment_id', $id);
    }

    /**
     * Filter the estates that has certain estate_types
     */
    public function estateType($id)
    {
        return $this->where('estate_type_id', $id);
    }

    /**
     * Filter the estates that has certain features
     */
    public function features($ids)
    {
        return $this->filter_relation('features', $ids);
    }

    /**
     * Filter the estates that has certain spec_data
     */
    public function dynamicFilters($filters)
    {
        if ( !$filters ) return;

        foreach ($filters as $item)
        {
            $this->whereHas('spec_data', function($query) use ($item) {
                
                $query->where('spec_row_id', $item['row_id'])->when( $item['values'] ?? false, function($query) use($item) {

                    $query->whereHas('values', function($query) use ($item) {
        
                        $query->whereIn('id', $item['values'] );
                    });
                }, function($query) use($item) {

                    $query->whereHas('translations', function($query) use ($item) {
                        
                        $query->where('data', $item['data'] ?? null);
                    });
                });

            });
        }

        return;
    }

    /**
     * Filter the estates that have area between two values
     */
    public function area($values)
    {
        return $this->range_filter('area', $values);
    }

    /**
     * Filter the estates that have rental_price between two values
     */
    public function rentalPrice($values)
    {
        return $this->range_filter('rental_price', $values);
    }

    /**
     * Filter the estates that have mortgage_price between two values
     */
    public function mortgagePrice($values)
    {
        return $this->range_filter('mortgage_price', $values);
    }

    /**
     * Filter the estates that have sales_price between two values
     */
    public function salesPrice($values)
    {
        return $this->range_filter('sales_price', $values);
    }
}