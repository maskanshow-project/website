<?php

namespace App\GraphQL\Query\Opinion\Comment;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Opinion\CommentProps;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;

abstract class BaseCommentQuery extends MainQuery
{
    use CommentProps;

    protected $acceptable = false;

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission('read-comment');
    }

    public function applyFilters($args, $data)
    {
        $data->where('parent_id', null);
    }
}
