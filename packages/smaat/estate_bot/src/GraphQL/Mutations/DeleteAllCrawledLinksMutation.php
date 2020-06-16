<?php

declare(strict_types=1);

namespace SmaaT\EstateBot\GraphQL\Mutations;

use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use SmaaT\EstateBot\Models\CrawledLink;

class DeleteAllCrawledLinksMutation extends Mutation
{
	protected $attributes = [
		'name' => 'deleteAllCrawledLinks',
		'description' => 'A mutation'
	];

	public function type(): Type
	{
		return GraphQL::type('result');
	}

	public function args(): array
	{
		return [
			//
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
		CrawledLink::whereNull('estate_id')->whereNotNull('errors')->delete();

		return [
			'status'    => 200,
			'message'   => 'کلیه خطا ها با موفقیت حذف شدند'
		];
	}
}
