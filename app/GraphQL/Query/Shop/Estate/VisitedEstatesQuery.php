<?php

namespace App\GraphQL\Query\Shop\Estate;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use App\GraphQL\Helpers\ChartQueryHelper;
use DB;

class VisitedEstatesQuery extends BaseEstateQuery
{
    use ChartQueryHelper;

    public function type()
    {
        return Type::listOf( \GraphQL::type('chart_record') );
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $date = jdate("now - 11 months");
        $date = $date->subDays( $date->getDay() - 1 );

        $result = DB::table('visited_estates')->where('user_id', auth()->id() )
            ->select([
                \DB::raw("month(`visited_at`) as 'month'"),
                \DB::raw("count(`visited_at`) 'count'")
            ])
            ->groupBy('month')
            ->where('visited_at', '>', $date)
            ->get()
            ->pluck('count', 'month');

            
        $this->change_new_year_month($result);
        $this->change_old_year_month($result);

        return array_values( $result->sortKeys()->toArray() );
    }
}