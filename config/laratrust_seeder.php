<?php

return [
    'role_structure' => [
        'owner' => [
            'estate_type' => 'c,u,d',
            'assignment' => 'c,u,d',
            'feature' => 'c,u,d',
            'spec' => 'r,c,u,d',

            'subject' => 'c,u,d',
            
            'area' => 'c,u,d',
            'street' => 'c,u,d',

            'promocode' => 'r,c,u,d',
            'plan' => 'c,u,d',
            'payment' => 'r',

            'article' => 'c,u,d',
            'estate' => 'at,r,c,u,d,s,acas',
            'label' => 'c,u,d',

            'comment' => 'r,c,a,d',
            
            'user' => 'at,r,u,d,s,sa,sph,chc,eac,scr',
            'role' => 'r,c,u,d',
            'office' => 'u,d',
            'message' => 'r,c,u,d',
            'blacklist_phone_number' => 'r,c,u,d',

            'setting' => 'u'
        ],
        'consultant' => [
            // 
        ],
    ],
    'permissions_map' => [
        'c'     => 'create',
        'r'     => 'read',
        'u'     => 'update',
        'd'     => 'delete',
        'a'     => 'accept',
        'at'    => 'active',
        's'     => 'see-details',
        'scr'   => 'see-credit',
        'sa'    => 'see-address',
        'sph'   => 'see-phone-number',
        'chc'   => 'change-credit',
        'acas'  => 'accept-assignment',
        'eac'   => 'empty-auth-code'
    ],

    'actions_label' => [
        'create'            => 'ثبت',
        'read'              => 'مشاهده',
        'update'            => 'ویرایش',
        'delete'            => 'حذف',
        'accept'            => 'تایید/رد کزدن',
        'active'            => 'فعال/غیرفعال کزدن',
        'see-details'       => 'مشاهده جزییات',
        'see-credit'        => 'مشاهده اعتبار',
        'see-address'       => 'مشاهده آدرس',
        'see-phone-number'  => 'مشاهده شماره تلفن',
        'change-credit'     => 'تغییر اعتبار',
        'accept-assignment' => 'تایید واگذاری',
        'empty-auth-code'   => 'پاک کردن کد دسترسی سیستم',
    ],

    'permissions_label' => [
        'estate_type' => 'نوع ملک',
        'assignment' => 'نوع واگذاری',
        'feature' => 'امکانات',
        'spec' => 'جدول مشخصات فنی',

        'subject' => 'دسته بندی',

        'area' => 'منطقه',
        'street' => 'خیابان',

        'promocode' => 'کد تخفیف',
        'plan' => 'پلن فروش',
        'payment' => 'پرداخت',
        
        'article' => 'مقاله',
        'estate' => 'ملک',
        'label' => 'لیبل ملک',

        'comment' => 'نظر',

        'user' => 'کاربر',
        'office' => 'دفتر املاک',
        'role' => 'نقش',
        'message' => 'پیام',
        'blacklist_phone_number' => 'شماره تلفن لیست سیاه',

        'setting' => 'تنظیمات'
    ],

    'roles_label' => [
        'owner' => [
            'name' => 'مدیر',
            'description' => 'مالک وبسایت کارگذاری املاک'
        ],
        'employee' => [
            'name' => 'کارمند',
            'description' => 'کارمند شاغل در وبسایت'
        ],
        'consultant' => [
            'name' => 'مشاور املاک',
            'description' => 'مشاور املاک'
        ],
        'landlord' => [
            'name' => 'صاحب خانه',
            'description' => 'صاحب خانه'
        ],
    ]
];
