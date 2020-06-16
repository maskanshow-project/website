<?php

declare(strict_types=1);

namespace SmaaT\EstateBot\GraphQL\Mutations;

use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use SmaaT\EstateBot\Models\CrawledLink;

class DeleteCrawledLinkMutation extends Mutation
{
	protected $attributes = [
		'name' => 'deleteCrawledLink',
		'description' => 'A mutation'
	];

	public function type(): Type
	{
		return GraphQL::type('result');
	}

	public function args(): array
	{
		return [
			'id' => [
				'type' => Type::int(),
				'rules' => 'required|exists:crawled_links,id'
			]
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
		CrawledLink::destroy($args['id']);

		return [
			'status'    => 200,
			'message'   => 'خطای مورد نظر با موفقیت حذف شد'
		];
	}
}
