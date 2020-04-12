<?php

namespace App\GraphQL\Mutation\Option\SiteSetting;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Option\SiteSettingProps;
use Closure;
use Rebing\GraphQL\Support\UploadType;

abstract class BaseSiteSettingMutation extends MainMutation
{
    use SiteSettingProps;

    protected $attributes = [
        'name' => 'SiteSettingMutation',
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
        return $this->checkPermission("update-setting");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(array $args = []): array
    {
        return (new $this->request)->rules($args, 'UPDATE');
    }

    public function attributes()
    {
        return (new $this->request)->attributes();
    }
}
