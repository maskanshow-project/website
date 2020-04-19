<?php

namespace App\GraphQL\Type\User;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\User\Message;

class MessageType extends BaseType
{
    protected $incrementing = false;

    protected $attributes = [
        'name' => 'message',
        'description' => 'A type',
        'model' => Message::class
    ];

    public function get_fields()
    {
        return [
            'is_mine' => $this->isMineField(),
            'creator' => $this->creator('role'),
            'title' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'message' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'type' => [
                'type' => Type::string(),
            ],
            'expired_at' => [
                'type' => Type::string(),
            ],
            'role' => [
                'type' => \GraphQL::type('role')
            ],
            'audits' => $this->audits('message'),
            'is_active' => $this->acceptableField('message')
        ];
    }
}
