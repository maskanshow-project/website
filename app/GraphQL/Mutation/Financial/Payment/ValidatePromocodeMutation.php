<?php

namespace App\GraphQL\Mutation\Financial\Plan;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Financial\PaymentProps;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\Financial\Promocode;
use Closure;

class ValidatePromocodeMutation extends MainMutation
{
    use PaymentProps;

    protected $attributes = [
        'name' => 'ValidatePromocodeMutation',
        'description' => 'A mutation'
    ];

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
        return true;
    }

    public function args(): array
    {
        return [
            'code' => [
                'type' => Type::nonNull(Type::string())
            ],
            'plan' => [
                'type' => Type::nonNull(Type::int())
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $promocode = Promocode::whereCode($args['code'])->with('plans')
            ->where(function ($query) {
                $query->where('expired_at', '>', now())->orWhere('expired_at', null);
            })
            ->where(function ($query) {
                $query->where('quantity', '>=', '1')->orWhere('quantity', null);
            })
            ->first();

        if (!$promocode) {
            return [
                'status' => 400,
                'message' => 'این کد تخفیف معتبر نیست یا منقضی شده است'
            ];
        }

        if ($promocode->plans->isNotEmpty() && $promocode->plans->where('id', $args['plan'])->count() !== 1) {
            return [
                'status' => 400,
                'message' => 'امکان استفاده از این کد تخفیف برای خرید این پلن ممکن نیست'
            ];
        }


        if ($promocode->quantity && $promocode->used_count >= $promocode->quantity) {
            return [
                'status' => 400,
                'message' => 'متاسفانه این کد تخفیف دیگر قابل استفاده نیست'
            ];
        }

        if ($promocode->users()->where('id', auth()->id())->count()) {
            return [
                'status' => 400,
                'message' => 'شما قبلا یکبار از این کد تخفیف استفاده کرده اید'
            ];
        }

        return [
            'count' => $promocode->cost,
            'status' => 200,
            'message' => 'کد تخفیف مورد نظر معتبر است'
        ];
    }
}
