<?php

namespace App\GraphQL\Mutation\Financial\Plan;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\Financial\Plan;
use App\Models\Financial\Promocode;
use App\Models\Financial\Payment;
use App\GraphQL\Helpers\CreateMutation;
use App\GraphQL\Props\Financial\PaymentProps;
use Closure;

class CreatePaymentMutation extends MainMutation
{
    use CreateMutation, PaymentProps;

    protected $attributes = [
        'name' => 'CreatePaymentMutation',
        'description' => 'A mutation'
    ];

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
            'plan' => [
                'type' => Type::int()
            ],
            'promocode' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ]
        ];
    }


    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $plan = Plan::findOrFail($args['plan']);

        if ($args['promocode'] ?? false)
            $promocode = Promocode::whereCode($args['promocode'])
                ->where(function ($query) {
                    $query->where('expired_at', '>', now())->orWhere('expired_at', null);
                })
                ->where(function ($query) {
                    $query->where('quantity', '>=', '1')->orWhere('quantity', null);
                })
                ->first();


        if ($promocode ?? false) {
            if ($promocode->quantity && $promocode->used_count >= $promocode->quantity)
                $promocode = null;

            if ($promocode->users()->where('id', auth()->id())->count())
                $promocode = null;

            if ($promocode->plans->isNotEmpty() && $promocode->plans->where('id', $args['plan'])->count() !== 1)
                $promocode = null;
        }


        return Payment::create([
            'code' => str_random(50),
            'plan_id' => $plan->id,
            'promocode_id' => $promocode->id ?? null,
            'description' => $args['description'] ?? null,
            'amount' => $promocode ?? false ? $plan->price - ($plan->price * $promocode->cost / 100) : $plan->price,
            'expired_at' => now()->addMinute(5)
        ]);
    }
}
