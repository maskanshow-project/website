<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute باید پذیرفته شده باشد.',
    'active_url'           => 'آدرس :attribute معتبر نیست.',
    'after'                => ':attribute باید تاریخی بعد از :date باشد.',
    'after_or_equal'       => ':attribute باید تاریخی بعد از :date، یا مطابق با آن باشد.',
    'alpha'                => ':attribute باید فقط حروف الفبا باشد.',
    'alpha_dash'           => ':attribute باید فقط حروف الفبا، اعداد، خط تیره و زیرخط باشد.',
    'alpha_num'            => ':attribute باید فقط حروف الفبا و اعداد باشد.',
    'array'                => ':attribute باید آرایه باشد.',
    'before'               => ':attribute باید تاریخی قبل از :date باشد.',
    'before_or_equal'      => ':attribute باید تاریخی قبل از :date، یا مطابق با آن باشد.',
    'between'              => [
        'numeric' => ':attribute باید بین :min و :max باشد.',
        'file'    => ':attribute باید بین :min و :max کیلوبایت باشد.',
        'string'  => ':attribute باید بین :min و :max کاراکتر باشد.',
        'array'   => ':attribute باید بین :min و :max آیتم باشد.',
    ],
    'boolean'              => 'فیلد :attribute فقط می‌تواند true و یا false باشد.',
    'confirmed'            => ':attribute با فیلد تکرار مطابقت ندارد.',
    'date'                 => ':attribute یک تاریخ معتبر نیست.',
    'date_equals'          => 'The :attribute must be a date equal to :date.',
    'date_format'          => ':attribute با الگوی :format مطابقت ندارد.',
    'different'            => ':attribute و :other باید از یکدیگر متفاوت باشند.',
    'digits'               => ':attribute باید :digits رقم باشد.',
    'digits_between'       => ':attribute باید بین :min و :max رقم باشد.',
    'dimensions'           => 'ابعاد تصویر :attribute قابل قبول نیست.',
    'distinct'             => 'فیلد :attribute مقدار تکراری دارد.',
    'email'                => ':attribute باید یک ایمیل معتبر باشد.',
    'exists'               => ':attribute انتخاب شده، معتبر نیست.',
    'file'                 => ':attribute باید یک فایل معتبر باشد.',
    'filled'               => 'فیلد :attribute باید مقدار داشته باشد.',
    'gt'                   => [
        'numeric' => ':attribute باید بزرگتر از :value باشد.',
        'file'    => ':attribute باید بزرگتر از :value کیلوبایت باشد.',
        'string'  => ':attribute باید بیشتر از :value کاراکتر داشته باشد.',
        'array'   => ':attribute باید بیشتر از :value آیتم داشته باشد.',
    ],
    'gte'                  => [
        'numeric' => ':attribute باید بزرگتر یا مساوی :value باشد.',
        'file'    => ':attribute باید بزرگتر یا مساوی :value کیلوبایت باشد.',
        'string'  => ':attribute باید بیشتر یا مساوی :value کاراکتر داشته باشد.',
        'array'   => ':attribute باید بیشتر یا مساوی :value آیتم داشته باشد.',
    ],
    'image'                => ':attribute باید یک تصویر معتبر باشد.',
    'in'                   => ':attribute انتخاب شده، معتبر نیست.',
    'in_array'             => 'فیلد :attribute در لیست :other وجود ندارد.',
    'integer'              => ':attribute باید عدد صحیح باشد.',
    'ip'                   => ':attribute باید آدرس IP معتبر باشد.',
    'ipv4'                 => ':attribute باید یک آدرس معتبر از نوع IPv4 باشد.',
    'ipv6'                 => ':attribute باید یک آدرس معتبر از نوع IPv6 باشد.',
    'json'                 => 'فیلد :attribute باید یک رشته از نوع JSON باشد.',
    'lt'                   => [
        'numeric' => ':attribute باید کوچکتر از :value باشد.',
        'file'    => ':attribute باید کوچکتر از :value کیلوبایت باشد.',
        'string'  => ':attribute باید کمتر از :value کاراکتر داشته باشد.',
        'array'   => ':attribute باید کمتر از :value آیتم داشته باشد.',
    ],
    'lte'                  => [
        'numeric' => ':attribute باید کوچکتر یا مساوی :value باشد.',
        'file'    => ':attribute باید کوچکتر یا مساوی :value کیلوبایت باشد.',
        'string'  => ':attribute باید کمتر یا مساوی :value کاراکتر داشته باشد.',
        'array'   => ':attribute باید کمتر یا مساوی :value آیتم داشته باشد.',
    ],
    'max'                  => [
        'numeric' => ':attribute نباید بزرگتر از :max باشد.',
        'file'    => ':attribute نباید بزرگتر از :max کیلوبایت باشد.',
        'string'  => ':attribute نباید بیشتر از :max کاراکتر داشته باشد.',
        'array'   => ':attribute نباید بیشتر از :max آیتم داشته باشد.',
    ],
    'mimes'                => 'فرمت‌های معتبر فایل عبارتند از: :values.',
    'mimetypes'            => 'فرمت‌های معتبر فایل عبارتند از: :values.',
    'min'                  => [
        'numeric' => ':attribute نباید کوچکتر از :min باشد.',
        'file'    => ':attribute نباید کوچکتر از :min کیلوبایت باشد.',
        'string'  => ':attribute نباید کمتر از :min کاراکتر داشته باشد.',
        'array'   => ':attribute نباید کمتر از :min آیتم داشته باشد.',
    ],
    'not_in'               => ':attribute انتخاب شده، معتبر نیست.',
    'not_regex'            => 'فرمت :attribute معتبر نیست.',
    'numeric'              => ':attribute باید عدد یا رشته‌ای از اعداد باشد.',
    'present'              => 'فیلد :attribute باید در پارامترهای ارسالی وجود داشته باشد.',
    'regex'                => 'فرمت :attribute معتبر نیست.',
    'required'             => 'فیلد :attribute الزامی است.',
    'required_if'          => 'هنگامی که :other برابر با :value است، فیلد :attribute الزامی است.',
    'required_unless'      => 'فیلد :attribute الزامی است، مگر آنکه :other در :values موجود باشد.',
    'required_with'        => 'در صورت وجود فیلد :values، فیلد :attribute نیز الزامی است.',
    'required_with_all'    => 'در صورت وجود فیلدهای :values، فیلد :attribute نیز الزامی است.',
    'required_without'     => 'در صورت عدم وجود فیلد :values، فیلد :attribute الزامی است.',
    'required_without_all' => 'در صورت عدم وجود هر یک از فیلدهای :values، فیلد :attribute الزامی است.',
    'same'                 => ':attribute و :other باید همانند هم باشند.',
    'size'                 => [
        'numeric' => ':attribute باید برابر با :size باشد.',
        'file'    => ':attribute باید برابر با :size کیلوبایت باشد.',
        'string'  => ':attribute باید برابر با :size کاراکتر باشد.',
        'array'   => ':attribute باید شامل :size آیتم باشد.',
    ],
    'starts_with'          => 'The :attribute must start with one of the following: :values',
    'string'               => 'فیلد :attribute باید رشته باشد.',
    'timezone'             => 'فیلد :attribute باید یک منطقه زمانی معتبر باشد.',
    'unique'               => ':attribute قبلا انتخاب شده است.',
    'uploaded'             => 'بارگذاری فایل :attribute موفقیت آمیز نبود.',
    'url'                  => ':attribute معتبر نمی‌باشد.',
    'uuid'                 => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name'                  => 'نام',
        'username'              => 'نام کاربری',
        'display_name'          => 'نام قابل نمایش',
        'email'                 => 'ایمیل',
        'first_name'            => 'نام',
        'last_name'             => 'نام خانوادگی',
        'current_password'      => 'گذرواژه کنونی',
        'password'              => 'گذرواژه',
        'password_confirmation' => 'تکرار گذرواژه',
        'city'                  => 'شهر',
        'country'               => 'کشور',
        'address'               => 'نشانی',
        'plaque'                => 'پلاک',
        'phone'                 => 'شماره ثابت',
        'phone_number'          => 'شماره تلفن',
        'mobile'                => 'شماره همراه',
        'age'                   => 'سن',
        'sex'                   => 'جنسیت',
        'gender'                => 'جنسیت',
        'national_code'         => 'کد ملی',
        'day'                   => 'روز',
        'month'                 => 'ماه',
        'year'                  => 'سال',
        'hour'                  => 'ساعت',
        'minute'                => 'دقیقه',
        'second'                => 'ثانیه',
        'title'                 => 'عنوان',
        'text'                  => 'متن',
        'content'               => 'محتوا',
        'message'               => 'پیام',
        'description'           => 'توضیحات',
        'excerpt'               => 'گزیده مطلب',
        'date'                  => 'تاریخ',
        'time'                  => 'زمان',
        'available'             => 'موجود',
        'size'                  => 'اندازه',
        'count'                 => 'تعداد',
        'type'                  => 'نوع',
        'value'                 => 'مقدار',
        'terms'                 => 'شرایط',
        'province'              => 'استان',
        'logo'                  => 'لوگو',
        'avatar'                => 'آواتار',
        'icon'                  => 'آیکون',
        'color'                 => 'رنگ',
        'code'                  => 'کد',
        'body'                  => 'متن مقاله',
        'price'                 => 'قیمت',
        'cost'                  => 'ارزش',
        'quantity'              => 'تعداد',
        'image'                 => 'تصویر',
        'reading_time'          => 'زمان مطالعه',
        'is_active'             => 'وضعیت فعال/غیرفعال',
        
        'expired_at'            => 'تاریخ انقضاء',

        // Estate
        'landlord_fullname'     => 'نام مالک',
        'landlord_phone_number' => 'شماره تلفن مالک',
        'aparat_video'          => 'لینک ویدیو آپارات',
        'sales_price'           => 'قیمت کل',
        'mortgage_price'        => 'مبلغ رهن',
        'rental_price'          => 'مبلغ اجاره',
        'area'                  => 'متراژ',
        'advantages'            => 'مزایا',
        'advantages.*'          => 'مزایا',
        'disadvantages'         => 'معایب',
        'disadvantages.*'       => 'معایب',
        'tags'                  => 'کلمات کلیدی',
        'tags.*'                => 'کلمات کلیدی',
        'want_cooperation'      => 'درخواست همکاری',
        'lat'                   => 'مختصات عرض جغرافیایی',
        'lng'                   => 'مختصات طول جغرافیایی',

        // Plan
        'visited_estate_count'  => 'تعداد ملک قابل بازدید',
        'credit_days_count'     => 'اعتبار پلن بر حسب روز',
        'registered_estate_count'=> 'تعداد ملک قابل ثبت',

        // Setting
        'theme_color'           => 'رنگ قالب سایت',
        'banner'                => 'بنر',
        'header_banner'         => 'بنر ابتدایی',
        'opinions.*.avatar'     => 'عکس صاحب نظر ها',
        'opinions.*.post'       => 'سمت صاحب نظر ها',
        'opinions.*.full_name'  => 'نام کاربر صاحب نظر ها',
        'opinions.*.opinion'    => 'متن نظر ها)',
        'posters.*.image'       => 'عکس پوستر ها',
        'posters.*.title'       => 'عنوان پوستر ها',
        'posters.*.description' => 'توضیحات پوستر ها',
        'posters.*.link'        => 'لینک پوستر ها',

        // Specification
        'postfix'               => 'پسوند',
        'prefix'                => 'پیشوند',
        'help'                  => 'راهنما',
        'is_detailable'         => 'قابل نمایش بودن',
        'is_multiple'           => 'چند مقداره بودن',
        'is_filterable'         => 'قابل فیلتر بودن',

        // Register
        'office_name'           => 'نام  دفتر املاک',
        'office_username'       => 'نام کاربری دفتر املاک',
        'office_description'    => 'توضیحات دفتر املاک',
        'office_address'        => 'آدرس  دفتر املاک',
        'office_phone_number'   => 'شماره تلفن دفتر املاک',
        'reagent_code'          => 'کد معرف',
        

        /**
         * Relations feilds
         */        
        'subjects'              => 'دسته بندی ها',
        'subjects.*'            => 'دسته بندی ها',
        'keywords'              => 'کلمات کلیدی',
        'keywords.*'            => 'کلمات کلیدی',
        'street_id'             => 'خیابان',
        'label_id'              => 'لیبل',
        'assignment_id'         => 'نوع واگذاری',
        'features'              => 'امکانات',
        'features.*'            => 'امکانات',
        'photos'                => 'تصاویر ملک',
        'photos.*'              => 'تصاویر ملک',
        'deleted_images'        => 'عکس های حذف شده',
        'deleted_images.*'      => 'عکس های حذف شده',
        'plan'                  => 'پلن',
        'plans'                 => 'پلن ها',
        'plans.*'               => 'پلن ها',
        'permissions'           => 'دسترسی ها',
        'permissions.*'         => 'دسترسی ها',
        'roles'                 => 'نقش ها',
        'roles.*'               => 'نقش ها',
        'promocode'             => 'کد تخفیف',
        'parent_id'             => 'داده والد',
        'article_id'            => 'مقاله',
        'city_id'               => 'شهر',
        'area_id'               => 'منطقه',
        'role_id'               => 'نقش',
        'estate_type_id'        => 'نوع ملک',
        'spec_id'               => 'جدول مشخصات',
        'spec_row_id'           => 'سطر جدول',
        'spec_header_id'        => 'عنوان جدول',
    ],
];
