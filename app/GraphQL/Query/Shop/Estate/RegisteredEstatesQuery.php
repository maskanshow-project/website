<?php

namespace App\GraphQL\Query\Shop\Estate;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use App\Models\Estate\Estate;
use App\GraphQL\Helpers\ChartQueryHelper;
use Closure;
use DB;

class RegisteredEstatesQuery extends BaseEstateQuery
{
    use ChartQueryHelper;

    public function type(): \GraphQL\Type\Definition\Type
    {
        return Type::listOf(\GraphQL::type('chart_record'));
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $date = jdate("now - 11 months");
        $date = $date->subDays($date->getDay() - 1);

        $result = Estate::where('user_id', auth()->id())
            ->select([
                \DB::raw("month(`jalali_created_at`) as 'month'"),
                \DB::raw("count(`id`) 'count'")
            ])
            ->groupBy('month')
            ->where('jalali_created_at', '>', $date)
            ->get()
            ->pluck('count', 'month');


        $this->change_new_year_month($result);
        $this->change_old_year_month($result);

        return array_values($result->sortKeys()->toArray());
    }
}
