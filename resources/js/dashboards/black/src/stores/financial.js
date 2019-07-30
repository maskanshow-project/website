export default {
    state: {
        promocode: [],
        plan: [],
        payment: [],

        filters: {
            promocode: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
            plan: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
            payment: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
        },

        has_loaded: {
            promocode: false,
            plan: false,
            payment: false,
        },

        is_mutation_loading: {
            promocode: false,
            plan: false,
            payment: false,
        },

        is_query_loading: {
            promocode: false,
            plan: false,
            payment: false,
        },

        is_open: {
            promocode: false,
            plan: false,
            payment: false,
        },

        is_incrementing: {
            // 
        },

        has_timestamps: {
            promocode: true,
            plan: true,
            payment: true,
        },
        
        is_creating: {
            promocode: false,
            plan: false,
            payment: false,
        },
        
        is_loading: {
            promocode: false,
            plan: false,
            payment: false,
        },

        is_grid_view: {
            promocode: false,
            plan: false,
            payment: false,
        },

        has_more: {
            promocode: true,
            plan: true,
            payment: true,
        },

        page: {
            promocode: 1,
            plan: 1,
            payment: 1,
        },

        chart_object: {
            promocode: null,
            plan: null,
            payment: null,
        },

        counts: {
            promocode: {
                total: 0,
                trash: 0
            },
            plan: {
                total: 0,
                trash: 0
            },
            payment: {
                total: 0,
                trash: 0
            },
        },

        charts: {
            promocode: {
                labels: [],
                data: [],
            },       
            plan: {
                labels: [],
                data: [],
            },       
            payment: {
                labels: [],
                data: [],
            },       
        },

        selected_items: {
            promocode: [],
            plan: [],
            payment: [],
        },

        image_field: {
            // 
        },

        form: {
            promocode: {
                code: {
                    type: 'String',
                    value: ''
                },
                title: {
                    type: 'String',
                    value: ''
                },
                description: {
                    type: 'String',
                    value: ''
                },
                quantity: {
                    type: 'Int',
                    value: null
                },
                cost: {
                    type: 'Int',
                    value: null
                },
                expired_at: {
                    type: 'String',
                    value: '',
                    clientResolver: value => value ? value : null 
                },
                plans: {
                    type: '[Int]',
                    value: [],
                    serverResolver: plans => plans.map(plan => plan.id)
                },
            },
            plan: {
                title: {
                    type: 'String',
                    value: ''
                },
                color: {
                    type: 'String',
                    value: ''
                },
                description: {
                    type: 'String',
                    value: ''
                },
                price: {
                    type: 'Int',
                    value: null
                },
                visited_estate_count: {
                    type: 'Int',
                    value: null
                },
                registered_estate_count: {
                    type: 'Int',
                    value: null
                },
                credit_days_count: {
                    type: 'Int',
                    value: null
                },
            },
        },

        selected: {
            promocode: {
                id: null,
                index: null
            },
            plan: {
                id: null,
                index: null
            },
            payment: {
                id: null,
                index: null
            },
        },
    },
}