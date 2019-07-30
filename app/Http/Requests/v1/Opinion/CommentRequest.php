<?php

namespace App\Http\Requests\v1\Opinion;

use App\Http\Requests\v1\MainRequest;
use App\Rules\ExistsTenant;

class CommentRequest extends MainRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($args, $method)
    {
        $this->method = $method;

        return [
            'title'         => 'required_without:parent_id|string|max:100',
            'message'       => 'required|string|max:2000',

            /* relateion */
            'parent_id'     => 'nullable|integer|exists:comments,id',
            'article_id'    => 'required_without:parent_id|string|exists:articles,id'
        ];
    }
}
