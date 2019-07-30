<?php

use App\Models\Blog\Article;
use App\Models\Group\Subject;
use App\Models\Opinion\Comment;
use App\Models\Spec\Spec;
use App\Models\Estate\Estate;
use App\Models\Estate\Label;
use App\Models\Estate\Assignment;
use App\Models\Estate\EstateType;
use App\User;
use App\Role;
use App\Models\Financial\Promocode;
use App\Models\Financial\Plan;
use App\Models\BlacklistPhoneNumber;
use App\Models\Places\Area;
use App\Models\Places\Street;
use App\Models\Estate\Feature;
use App\Models\User\Message;
use App\Models\Financial\Payment;
use App\Models\User\Office;

return [
    // Blog models
    'article'               => Article::class,

    // Group models
    'subject'               => Subject::class,

    // Opinion models
    'comment'               => Comment::class,

    // Place
    'area'                  => Area::class,
    'street'                => Street::class,

    // Financial
    'promocode'             => Promocode::class,
    'plan'                  => Plan::class,
    'payment'               => Payment::class,

    // Estate models
    'feature'               => Feature::class,
    'estate'                => Estate::class,
    'label'                 => Label::class,
    'assignment'            => Assignment::class,
    'estate_type'           => EstateType::class,

    // Specification models
    'spec'                  => Spec::class,

    // User models
    'user'                  => User::class,
    'role'                  => Role::class,
    'office'                => Office::class,
    'blacklist_phone_number'=> BlacklistPhoneNumber::class,
    'message'               => Message::class
];
