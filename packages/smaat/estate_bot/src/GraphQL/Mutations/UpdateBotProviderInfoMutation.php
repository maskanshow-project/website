<?php

declare(strict_types=1);

namespace SmaaT\EstateBot\GraphQL\Mutations;

use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use SmaaT\EstateBot\Models\EstateBotProvider;

class UpdateBotProviderInfoMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateBotProviderInfo',
        'description' => 'A mutation'
    ];

    public function type(): Type
    {
        return GraphQL::type('result');
    }

    public function args(): array
    {
        return [
            'provider' => [
                'type' => Type::string(),
                'rules' => 'required|in:MaskanFile,MelkeIrani'
            ],
            'username' => [
                'type' => Type::string(),
                'rules' => 'required'
            ],
            'password' => [
                'type' => Type::string(),
                'rules' => 'required'
            ],
        ];
    }

    /**
     * Override this in your queries or mutations
     * to provide custom authorization.
     *
     * @return bool
     */
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return auth()->user()->can('manage-estate-bot');
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $provider = EstateBotProvider::whereProvider($args['provider'])->first();

        $provider->update([
            'username'  => $args['username'],
            'password'  => $args['password'],
        ]);

        return [
            'status'    => 200,
            'message'   => 'اطلاعات منبع با موفقیت بروزرسانی شد'
        ];
    }
}
