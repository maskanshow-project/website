<?php

declare(strict_types=1);

// Group Queries
use App\GraphQL\Query\Group\{
	Subject\SubjectQuery,
	Subject\SubjectsQuery
};

// Opinion Queries
use App\GraphQL\Query\Opinion\{
	Comment\CommentQuery,
	Comment\CommentsQuery
};

// Option Queries
use App\GraphQL\Query\Option\{
	SiteSetting\SiteSettingQuery,
	UserSetting\UserSettingQuery
};

// Shop Queries
use App\GraphQL\Query\Shop\{
	Spec\SpecQuery,
	Spec\SpecsQuery,
	Label\LabelQuery,
	Label\LabelsQuery,
	Estate\EstateQuery,
	Estate\EstatesQuery,
	Estate\VisitedEstatesQuery,
	Estate\RegisteredEstatesQuery,
	Assignment\AssignmentQuery,
	Assignment\AssignmentsQuery,
	EstateType\EstateTypeQuery,
	EstateType\EstateTypesQuery,
	Feature\FeatureQuery,
	Feature\FeaturesQuery
};

// Blog Queries
use App\GraphQL\Query\Blog\Article\{ArticlesQuery, ArticleQuery};

// Place Queries
use App\GraphQL\Query\Place\{
	Country\CountryQuery,
	Country\CountriesQuery,
	Province\ProvinceQuery,
	Province\ProvincesQuery,
	City\CityQuery,
	City\CitiesQuery,
	Area\AreaQuery,
	Area\AreasQuery,
	Street\StreetQuery,
	Street\StreetsQuery
};

// User Queries
use App\GraphQL\Query\User\{
	User\UserQuery,
	User\UsersQuery,
	Role\RoleQuery,
	Role\RolesQuery,
	User\MeQuery,
	Favorite\FavoriteQuery,
	BlacklistPhoneNumber\BlacklistPhoneNumbersQuery,
	BlacklistPhoneNumber\BlacklistPhoneNumberQuery,
	Message\MessageQuery,
	Message\MessagesQuery,
	Office\OfficeQuery,
	Office\OfficesQuery
};

// Group Mutations
use App\GraphQL\Mutation\Group\{
	Subject\CreateSubjectMutation,
	Subject\UpdateSubjectMutation,
	Subject\DeleteSubjectMutation
};

// Opinion Mutations
use App\GraphQL\Mutation\Opinion\{
	Comment\CreateCommentMutation,
	Comment\UpdateCommentMutation,
	Comment\DeleteCommentMutation,
	Comment\ActiveCommentMutation
};

// Estate Mutations
use App\GraphQL\Mutation\Estate\{
	Label\CreateLabelMutation,
	Label\UpdateLabelMutation,
	Label\DeleteLabelMutation,
	Feature\CreateFeatureMutation,
	Feature\UpdateFeatureMutation,
	Feature\DeleteFeatureMutation,
	Estate\AssignmentEstateMutation,
	Estate\AcceptAssignmentEstateMutation,
	Estate\ActiveEstateMutation
};

// Blog Mutations
use App\GraphQL\Mutation\Blog\{
	Article\ActiveArticleMutation,
	Article\CreateArticleMutation,
	Article\UpdateArticleMutation,
	Article\DeleteArticleMutation
};

// User Mutations
use App\GraphQL\Mutation\User\{
	User\ActiveUserMutation,
	User\UpdateUserMutation,
	User\DeleteUserMutation,
	User\LoginUserMutation,
	User\RegisterUserMutation,
	User\UpdateUserPasswordMutation,
	User\ChangeCreditUserMutation,
	User\LogoutUserMutation,

	AccessCode\CreateAccessCodeMutation,
	AccessCode\LoginWithAccessCodeMutation,

	Password\RequestResetPasswordMutation,
	Password\ResetPasswordMutation,

	Role\CreateRoleMutation,
	Role\UpdateRoleMutation,
	Role\DeleteRoleMutation,

	Favorite\AddFavoriteMutation,
	Favorite\RemoveFavoriteMutation,
	BlacklistPhoneNumber\CreateBlacklistPhoneNumberMutation,
	BlacklistPhoneNumber\UpdateBlacklistPhoneNumberMutation,
	BlacklistPhoneNumber\DeleteBlacklistPhoneNumberMutation,
	Message\CreateMessageMutation,
	Message\UpdateMessageMutation,
	Message\DeleteMessageMutation,
	User\RegisterConsultantMutation,
	User\EmptyAuthCodeUserMutation,
	Office\UpdateOfficeMutation,
	Office\DeleteOfficeMutation
};

// Spec Mutations
use App\GraphQL\Mutation\Spec\{
	Spec\CreateSpecMutation,
	Spec\UpdateSpecMutation,
	Spec\DeleteSpecMutation,
	SpecHeader\CreateSpecHeaderMutation,
	SpecHeader\UpdateSpecHeaderMutation,
	SpecHeader\DeleteSpecHeaderMutation,
	SpecRow\CreateSpecRowMutation,
	SpecRow\UpdateSpecRowMutation,
	SpecRow\DeleteSpecRowMutation,
	SpecDefault\CreateSpecDefaultMutation,
	SpecDefault\UpdateSpecDefaultMutation,
	SpecDefault\DeleteSpecDefaultMutation
};


// Spec Types
use App\GraphQL\Type\Spec\SpecType;
use App\GraphQL\Type\Spec\SpecHeaderType;
use App\GraphQL\Type\Spec\SpecRowType;
// Blog Types
use App\GraphQL\Type\Blog\ArticleType;
// User Types
use App\GraphQL\Type\User\UserType;
use App\GraphQL\Type\User\RoleType;
// Place Types
use App\GraphQL\Type\Place\CityType;
use App\GraphQL\Type\Place\ProvinceType;
use App\GraphQL\Type\Place\CountryType;
// Opinion Types
use App\GraphQL\Type\Opinion\CommentType;
// Helper Types
use App\GraphQL\Type\ResultMessageType;
use App\GraphQL\Type\TagType;
use App\GraphQL\Type\User\PermissionType;
use App\GraphQL\Type\User\SessionType;
use App\GraphQL\Type\AuditType;
use App\GraphQL\Type\ChartRecordType;
use App\GraphQL\Type\VotesType;
use App\GraphQL\Type\Spec\SpecDefaultType;
use App\GraphQL\Type\Spec\SpecDataType;
use App\GraphQL\Type\User\MeType;
use App\GraphQL\Type\Option\SiteSettingType;
use App\GraphQL\Type\Option\UserSettingType;
use App\GraphQL\Type\CoordinateType;
use App\GraphQL\Type\SingleMediaType;
use App\GraphQL\Input\SpecInput;
use App\GraphQL\Type\Estate\LabelType;
use App\GraphQL\Input\SliderItemInput;
use App\GraphQL\Mutation\Option\SiteSetting\InfoSiteSettingMutation;
use App\GraphQL\Mutation\Option\SiteSetting\SliderSiteSettingMutation;
use App\GraphQL\Mutation\Option\SiteSetting\PostersSiteSettingMutation;
use App\GraphQL\Type\Estate\EstateType;
use App\GraphQL\Type\Estate\EstateTypesType;
use App\GraphQL\Type\Estate\AssignmentType;
use App\GraphQL\Type\Group\SubjectType;
use App\GraphQL\Mutation\Estate\Estate\CreateEstateMutation;
use App\GraphQL\Mutation\Estate\Estate\UpdateEstateMutation;
use App\GraphQL\Mutation\Estate\Estate\DeleteEstateMutation;
use App\GraphQL\Mutation\Estate\Assignment\CreateAssignmentMutation;
use App\GraphQL\Mutation\Estate\Assignment\UpdateAssignmentMutation;
use App\GraphQL\Mutation\Estate\Assignment\DeleteAssignmentMutation;
use App\GraphQL\Mutation\Estate\EstateType\CreateEstateTypeMutation;
use App\GraphQL\Mutation\Estate\EstateType\UpdateEstateTypeMutation;
use App\GraphQL\Mutation\Estate\EstateType\DeleteEstateTypeMutation;
use App\GraphQL\Type\Option\SliderType;
use App\GraphQL\Type\Estate\PromocodeType;
use App\GraphQL\Type\Estate\PlanType;
use App\GraphQL\Query\Financial\Promocode\PromocodeQuery;
use App\GraphQL\Query\Financial\Promocode\PromocodesQuery;
use App\GraphQL\Query\Financial\Plan\PlanQuery;
use App\GraphQL\Query\Financial\Plan\PlansQuery;
use App\GraphQL\Mutation\Financial\Promocode\CreatePromocodeMutation;
use App\GraphQL\Mutation\Financial\Promocode\UpdatePromocodeMutation;
use App\GraphQL\Mutation\Financial\Promocode\DeletePromocodeMutation;
use App\GraphQL\Mutation\Financial\Plan\CreatePlanMutation;
use App\GraphQL\Mutation\Financial\Plan\UpdatePlanMutation;
use App\GraphQL\Mutation\Financial\Plan\DeletePlanMutation;
use App\GraphQL\Type\User\BlacklistPhoneNumberType;
use App\GraphQL\Type\Place\AreaType;
use App\GraphQL\Type\Place\StreetType;
use App\GraphQL\Mutation\Place\Area\CreateAreaMutation;
use App\GraphQL\Mutation\Place\Area\UpdateAreaMutation;
use App\GraphQL\Mutation\Place\Area\DeleteAreaMutation;
use App\GraphQL\Mutation\Place\Street\CreateStreetMutation;
use App\GraphQL\Mutation\Place\Street\UpdateStreetMutation;
use App\GraphQL\Mutation\Place\Street\DeleteStreetMutation;
use App\GraphQL\Type\Estate\FeatureType;
use App\GraphQL\Input\RangeInput;
use App\GraphQL\Input\DynamicFilterInput;
use App\GraphQL\Type\User\MessageType;
use App\GraphQL\Mutation\Financial\Plan\CreatePaymentMutation;
use App\GraphQL\Type\Estate\PaymentType;
use App\GraphQL\Query\Financial\Payment\PaymentQuery;
use App\GraphQL\Query\Financial\Payment\PaymentsQuery;
use App\GraphQL\Mutation\Financial\Plan\ValidatePromocodeMutation;
use App\GraphQL\Type\User\OfficeType;
use App\GraphQL\Type\Option\CustomerOpinionType;
use App\GraphQL\Input\CustomerOpinionInput;
use App\GraphQL\Mutation\Option\SiteSetting\AdvertisingSiteSettingMutation;
use App\GraphQL\Mutation\Option\SiteSetting\OpinionsSiteSettingMutation;
use SmaaT\EstateBot\GraphQL\Mutations\DeleteAllCrawledLinksMutation;
use SmaaT\EstateBot\GraphQL\Mutations\DeleteCrawledLinkMutation;
use SmaaT\EstateBot\GraphQL\Mutations\UpdateBotProviderInfoMutation;

return [

	// The prefix for routes
	'prefix' => 'graphql',

	// The routes to make GraphQL request. Either a string that will apply
	// to both query and mutation or an array containing the key 'query' and/or
	// 'mutation' with the according Route
	//
	// Example:
	//
	// Same route for both query and mutation
	//
	// 'routes' => 'path/to/query/{graphql_schema?}',
	//
	// or define each route
	//
	// 'routes' => [
	//     'query' => 'query/{graphql_schema?}',
	//     'mutation' => 'mutation/{graphql_schema?}',
	// ]
	//
	'routes' => '{graphql_schema?}',

	// The controller to use in GraphQL request. Either a string that will apply
	// to both query and mutation or an array containing the key 'query' and/or
	// 'mutation' with the according Controller and method
	//
	// Example:
	//
	// 'controllers' => [
	//     'query' => '\Rebing\GraphQL\GraphQLController@query',
	//     'mutation' => '\Rebing\GraphQL\GraphQLController@mutation'
	// ]
	//
	'controllers' => \Rebing\GraphQL\GraphQLController::class . '@query',

	// Any middleware for the graphql route group
	'middleware' => [],

	// Additional route group attributes
	//
	// Example:
	//
	// 'route_group_attributes' => ['guard' => 'api']
	//
	'route_group_attributes' => [],

	// The name of the default schema used when no argument is provided
	// to GraphQL::schema() or when the route is used without the graphql_schema
	// parameter.
	'default_schema' => 'default',

	// The schemas for query and/or mutation. It expects an array of schemas to provide
	// both the 'query' fields and the 'mutation' fields.
	//
	// You can also provide a middleware that will only apply to the given schema
	//
	// Example:
	//
	//  'schema' => 'default',
	//
	//  'schemas' => [
	//      'default' => [
	//          'query' => [
	//              'users' => 'App\GraphQL\Query\UsersQuery'
	//          ],
	//          'mutation' => [
	//
	//          ]
	//      ],
	//      'user' => [
	//          'query' => [
	//              'profile' => 'App\GraphQL\Query\ProfileQuery'
	//          ],
	//          'mutation' => [
	//
	//          ],
	//          'middleware' => ['auth'],
	//      ],
	//      'user/me' => [
	//          'query' => [
	//              'profile' => 'App\GraphQL\Query\MyProfileQuery'
	//          ],
	//          'mutation' => [
	//
	//          ],
	//          'middleware' => ['auth'],
	//      ],
	//  ]
	//
	'schemas' => [
		'default' => [
			'query' => [
				// Option
				'siteSetting' => SiteSettingQuery::class,

				// Shop
				'estate' => EstateQuery::class,
				'estates' => EstatesQuery::class,
				'assignment' => AssignmentQuery::class,
				'assignments' => AssignmentsQuery::class,
				'estate_type' => EstateTypeQuery::class,
				'estate_types' => EstateTypesQuery::class,

				// Blog
				'article' => ArticleQuery::class,
				'articles' => ArticlesQuery::class,

				// Financial
				'plan' => PlanQuery::class,
				'plans' => PlansQuery::class,

				// Place
				'country' => CountryQuery::class,
				'countries' => CountriesQuery::class,
				'province' => ProvinceQuery::class,
				'provinces' => ProvincesQuery::class,
				'city' => CityQuery::class,
				'cities' => CitiesQuery::class,
				'area' => AreaQuery::class,
				'areas' => AreasQuery::class,
				'street' => StreetQuery::class,
				'streets' => StreetsQuery::class,

				// Group
				'subject' => SubjectQuery::class,
				'subjects' => SubjectsQuery::class,

				// User
				'office' => OfficeQuery::class,
				'offices' => OfficesQuery::class,
			],
			'mutation' => [
				// User
				'login'                 => LoginUserMutation::class,
				'loginWithAccessCode'   => LoginWithAccessCodeMutation::class,
				'register'              => RegisterUserMutation::class,
				'registerConsultant'    => RegisterConsultantMutation::class,
				'requestResetPassword'  => RequestResetPasswordMutation::class,
				'resetPassword'         => ResetPasswordMutation::class,
			],
			'middleware' => [],
			'method' => ['get', 'post'],
		],

		'auth' => [
			'query' => [
				// Opinion
				'comment' => CommentQuery::class,
				'comments' => CommentsQuery::class,

				// Option
				'siteSetting' => SiteSettingQuery::class,
				'userSetting' => UserSettingQuery::class,

				// Shop
				'estate' => EstateQuery::class,
				'estates' => EstatesQuery::class,
				'visitedEstates' => VisitedEstatesQuery::class,
				'registeredEstates' => RegisteredEstatesQuery::class,
				'spec' => SpecQuery::class,
				'specs' => SpecsQuery::class,
				'label' => LabelQuery::class,
				'labels' => LabelsQuery::class,
				'assignment' => AssignmentQuery::class,
				'assignments' => AssignmentsQuery::class,
				'estate_type' => EstateTypeQuery::class,
				'estate_types' => EstateTypesQuery::class,
				'feature' => FeatureQuery::class,
				'features' => FeaturesQuery::class,

				// Financial
				'promocode' => PromocodeQuery::class,
				'promocodes' => PromocodesQuery::class,
				'plan' => PlanQuery::class,
				'plans' => PlansQuery::class,
				'payment' => PaymentQuery::class,
				'payments' => PaymentsQuery::class,

				// Blog
				'article' => ArticleQuery::class,
				'articles' => ArticlesQuery::class,

				// Place
				'country' => CountryQuery::class,
				'countries' => CountriesQuery::class,
				'province' => ProvinceQuery::class,
				'provinces' => ProvincesQuery::class,
				'city' => CityQuery::class,
				'cities' => CitiesQuery::class,
				'area' => AreaQuery::class,
				'areas' => AreasQuery::class,
				'street' => StreetQuery::class,
				'streets' => StreetsQuery::class,

				// Group
				'subject' => SubjectQuery::class,
				'subjects' => SubjectsQuery::class,

				// User
				'user' => UserQuery::class,
				'me' => MeQuery::class,
				'users' => UsersQuery::class,
				'role' => RoleQuery::class,
				'roles' => RolesQuery::class,
				'office' => OfficeQuery::class,
				'offices' => OfficesQuery::class,
				'message' => MessageQuery::class,
				'messages' => MessagesQuery::class,
				'blacklistPhoneNumbers' => BlacklistPhoneNumbersQuery::class,
				'blacklistPhoneNumber' => BlacklistPhoneNumberQuery::class,
				'favorites' => FavoriteQuery::class,

				'crawledResult' => \SmaaT\EstateBot\GraphQL\Queries\CrawledResult::class,
				'botProviders' => \SmaaT\EstateBot\GraphQL\Queries\BotProviders::class,
			],
			'mutation' => [
				// Blog
				'activeArticle' => ActiveArticleMutation::class,
				'createArticle' => CreateArticleMutation::class,
				'updateArticle' => UpdateArticleMutation::class,
				'deleteArticle' => DeleteArticleMutation::class,


				// Group
				'createSubject' => CreateSubjectMutation::class,
				'updateSubject' => UpdateSubjectMutation::class,
				'deleteSubject' => DeleteSubjectMutation::class,


				// Financial
				'createPromocode' => CreatePromocodeMutation::class,
				'updatePromocode' => UpdatePromocodeMutation::class,
				'deletePromocode' => DeletePromocodeMutation::class,

				'createPlan' => CreatePlanMutation::class,
				'updatePlan' => UpdatePlanMutation::class,
				'deletePlan' => DeletePlanMutation::class,

				'createPayment' => CreatePaymentMutation::class,
				'validatePromocode' => ValidatePromocodeMutation::class,


				// Opinion
				'acceptComment' => ActiveCommentMutation::class,
				'createComment' => CreateCommentMutation::class,
				'updateComment' => UpdateCommentMutation::class,
				'deleteComment' => DeleteCommentMutation::class,


				// Option
				'updateSiteInfo' => InfoSiteSettingMutation::class,
				'updateSitePosters' => PostersSiteSettingMutation::class,
				'updateSiteOpinions' => OpinionsSiteSettingMutation::class,
				'updateSiteAds' => AdvertisingSiteSettingMutation::class,

				// Place
				'createArea' => CreateAreaMutation::class,
				'updateArea' => UpdateAreaMutation::class,
				'deleteArea' => DeleteAreaMutation::class,

				'createStreet' => CreateStreetMutation::class,
				'updateStreet' => UpdateStreetMutation::class,
				'deleteStreet' => DeleteStreetMutation::class,

				// Estate
				'assignmentEstate' => AssignmentEstateMutation::class,
				'acceptAssignmentEstate' => AcceptAssignmentEstateMutation::class,
				'activeEstate' => ActiveEstateMutation::class,
				'createEstate' => CreateEstateMutation::class,
				'updateEstate' => UpdateEstateMutation::class,
				'deleteEstate' => DeleteEstateMutation::class,

				'createFeature' => CreateFeatureMutation::class,
				'updateFeature' => UpdateFeatureMutation::class,
				'deleteFeature' => DeleteFeatureMutation::class,

				'createLabel' => CreateLabelMutation::class,
				'updateLabel' => UpdateLabelMutation::class,
				'deleteLabel' => DeleteLabelMutation::class,

				'createAssignment' => CreateAssignmentMutation::class,
				'updateAssignment' => UpdateAssignmentMutation::class,
				'deleteAssignment' => DeleteAssignmentMutation::class,

				'createEstateType' => CreateEstateTypeMutation::class,
				'updateEstateType' => UpdateEstateTypeMutation::class,
				'deleteEstateType' => DeleteEstateTypeMutation::class,


				// Spec
				'createSpec' => CreateSpecMutation::class,
				'updateSpec' => UpdateSpecMutation::class,
				'deleteSpec' => DeleteSpecMutation::class,

				'createSpecHeader' => CreateSpecHeaderMutation::class,
				'updateSpecHeader' => UpdateSpecHeaderMutation::class,
				'deleteSpecHeader' => DeleteSpecHeaderMutation::class,

				'createSpecRow' => CreateSpecRowMutation::class,
				'updateSpecRow' => UpdateSpecRowMutation::class,
				'deleteSpecRow' => DeleteSpecRowMutation::class,

				'createSpecDefault' => CreateSpecDefaultMutation::class,
				'updateSpecDefault' => UpdateSpecDefaultMutation::class,
				'deleteSpecDefault' => DeleteSpecDefaultMutation::class,


				// User
				'activeUser'    => ActiveUserMutation::class,
				'updateUser'    => UpdateUserMutation::class,
				'deleteUser'    => DeleteUserMutation::class,
				'changeCreditUser' => ChangeCreditUserMutation::class,
				'createAccessCode' => CreateAccessCodeMutation::class,
				'updateUserPassword' => UpdateUserPasswordMutation::class,
				'emptyAuthCodeUser' => EmptyAuthCodeUserMutation::class,

				'createRole'    => CreateRoleMutation::class,
				'updateRole'    => UpdateRoleMutation::class,
				'deleteRole'    => DeleteRoleMutation::class,

				'updateOffice' => UpdateOfficeMutation::class,
				'deleteOffice' => DeleteOfficeMutation::class,

				'createMessage' => CreateMessageMutation::class,
				'updateMessage' => UpdateMessageMutation::class,
				'deleteMessage' => DeleteMessageMutation::class,

				'createBlacklistPhoneNumber' => CreateBlacklistPhoneNumberMutation::class,
				'updateBlacklistPhoneNumber' => UpdateBlacklistPhoneNumberMutation::class,
				'deleteBlacklistPhoneNumber' => DeleteBlacklistPhoneNumberMutation::class,

				'logout' => LogoutUserMutation::class,

				// Favorite
				'addFavorite'   => AddFavoriteMutation::class,
				'removeFavorite' => RemoveFavoriteMutation::class,

				'updateBotProviderInfo' => UpdateBotProviderInfoMutation::class,
				'deleteCrawledLink' => DeleteCrawledLinkMutation::class,
				'deleteAllCrawledLinks' => DeleteAllCrawledLinksMutation::class
			],
			'middleware' => ['auth:api'],
			'method' => ['get', 'post', 'put', 'delete']
		]
	],

	// The types available in the application. You can then access it from the
	// facade like this: GraphQL::type('user')
	//
	// Example:
	//
	// 'types' => [
	//     'user' => 'App\GraphQL\Type\UserType'
	// ]
	//
	'types' => [
		// Opinion
		'comment'           => CommentType::class,

		// Option
		'site_settings'     => SiteSettingType::class,
		'user_settings'     => UserSettingType::class,
		'slider_item'       => SliderType::class,
		'customer_opinion'  => CustomerOpinionType::class,

		// Estate
		'estate'            => EstateType::class,
		'label'             => LabelType::class,
		'estate_type'       => EstateTypesType::class,
		'assignment'        => AssignmentType::class,
		'feature'           => FeatureType::class,

		// Financial
		'promocode'         => PromocodeType::class,
		'plan'              => PlanType::class,
		'payment'           => PaymentType::class,

		// Blog
		'article'           => ArticleType::class,

		// Specification
		'spec'              => SpecType::class,
		'spec_header'       => SpecHeaderType::class,
		'spec_row'          => SpecRowType::class,
		'spec_data'         => SpecDataType::class,
		'spec_default'      => SpecDefaultType::class,

		// Group
		'subject'           => SubjectType::class,

		// User
		'user'              => UserType::class,
		'role'              => RoleType::class,
		'me'                => MeType::class,
		'office'            => OfficeType::class,
		'message'           => MessageType::class,
		'permission'        => PermissionType::class,
		'session'           => SessionType::class,
		'blacklist_phone_number' => BlacklistPhoneNumberType::class,

		// Place
		'city'              => CityType::class,
		'province'          => ProvinceType::class,
		'country'           => CountryType::class,
		'area'              => AreaType::class,
		'street'            => StreetType::class,

		// Inputs
		'spec_input'        => SpecInput::class,
		'slider_item_input' => SliderItemInput::class,
		'customer_opinion_input' => CustomerOpinionInput::class,
		'range_input'       => RangeInput::class,
		'dynamic_filter_input' => DynamicFilterInput::class,

		// Others
		'coordinate'        => CoordinateType::class,
		'audit'             => AuditType::class,
		'votes'             => VotesType::class,
		'tag'               => TagType::class,
		'result'            => ResultMessageType::class,
		'chart_record'      => ChartRecordType::class,
		'single_media'      => SingleMediaType::class,
		'bot_provider'      => \SmaaT\EstateBot\GraphQL\Types\BotProviderType::class,
		'crawled_link'      => \SmaaT\EstateBot\GraphQL\Types\CrawledLinkType::class,
		'crawled_result'    => \SmaaT\EstateBot\GraphQL\Types\CrawledResultType::class,
		'Upload'            => \Rebing\GraphQL\Support\UploadType::class,
	],

	// The types will be loaded on demand. Default is to load all types on each request
	// Can increase performance on schemes with many types
	// Presupposes the config type key to match the type class name property
	'lazyload_types' => true,

	// This callable will be passed the Error object for each errors GraphQL catch.
	// The method should return an array representing the error.
	// Typically:
	// [
	//     'message' => '',
	//     'locations' => []
	// ]
	'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],

	/*
     * Custom Error Handling
     *
     * Expected handler signature is: function (array $errors, callable $formatter): array
     *
     * The default handler will pass exceptions to laravel Error Handling mechanism
     */
	'errors_handler' => ['\Rebing\GraphQL\GraphQL', 'handleErrors'],

	// You can set the key, which will be used to retrieve the dynamic variables
	'params_key' => 'variables',

	/*
     * Options to limit the query complexity and depth. See the doc
     * @ https://webonyx.github.io/graphql-php/security
     * for details. Disabled by default.
     */
	'security' => [
		'query_max_complexity' => null,
		'query_max_depth' => null,
		'disable_introspection' => false,
	],

	/*
     * You can define your own pagination type.
     * Reference \Rebing\GraphQL\Support\PaginationType::class
     */
	'pagination_type' => \App\GraphQL\Helpers\PaginationType::class,

	/*
     * Config for GraphiQL (see (https://github.com/graphql/graphiql).
     */
	'graphiql' => [
		'prefix' => '/graphiql',
		'controller' => \Rebing\GraphQL\GraphQLController::class . '@graphiql',
		'middleware' => [],
		'view' => 'graphql::graphiql',
		'display' => env('ENABLE_GRAPHIQL', true),
	],

	/*
     * Overrides the default field resolver
     * See http://webonyx.github.io/graphql-php/data-fetching/#default-field-resolver
     *
     * Example:
     *
     * ```php
     * 'defaultFieldResolver' => function ($root, $args, $context, $info) {
     * },
     * ```
     * or
     * ```php
     * 'defaultFieldResolver' => [SomeKlass::class, 'someMethod'],
     * ```
     */
	'defaultFieldResolver' => null,

	/*
     * Any headers that will be added to the response returned by the default controller
     */
	'headers' => [],

	/*
     * Any JSON encoding options when returning a response from the default controller
     * See http://php.net/manual/function.json-encode.php for the full list of options
     */
	'json_encoding_options' => 0,
];
