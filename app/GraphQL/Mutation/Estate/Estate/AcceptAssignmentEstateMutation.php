<?php

namespace App\GraphQL\Mutation\Estate\Estate;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\Estate\Estate;
use Closure;

class AcceptAssignmentEstateMutation extends BaseEstateMutation
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type('result');
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission("accept-assignment-estate");
    }

    public function args(): array
    {
        return [
            'estate' => [
                'type' => Type::int()
            ],
            'estates' => [
                'type' => Type::listOf(Type::int())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $result = false;

        if ($args['estate'] ?? false)
            $result = $this->model::where('assignmented_at', null)
                ->where('id', $args['estate'] ?? false)
                ->update(['assignmented_at' => now()]);

        elseif ($args['estates'] ?? false)
            $result = $this->model::where('assignmented_at', null)
                ->whereIn('id', $args['estates'] ?? false)
                ->update(['assignmented_at' => now()]);

        return [
            'status' => $result ? 200 : 400,
            'message' => $result ? 'ملک با موفقیت واگذار شد' : 'متاسفانه هیچ ملکی واگذار نشد'
        ];
    }
}
