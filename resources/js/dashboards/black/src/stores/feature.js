export default {
    state: {
        assignment: [],
        estate_type: [],
        spec: [],
        feature: [],

        filters: {
            assignment: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
            estate_type: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
            spec: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
            feature: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
        },

        has_loaded: {
            assignment: false,
            estate_type: false,
            spec: false,
            feature: false,
        },

        is_open: {
            assignment: false,
            estate_type: false,
            spec: false,
            feature: false,
        },
        
        is_incrementing: {
            // 
        },

        has_timestamps: {
            assignment: true,
            estate_type: true,
            spec: true,
            feature: true,
        },
        
        is_creating: {
            assignment: false,
            estate_type: false,
            spec: false,
            feature: false,
        },
        
        is_loading: {
            assignment: false,
            estate_type: false,
            spec: false,
            feature: false,
        },

        image_field: {
            assignment: false,
            estate_type: false,
            spec: false,
            feature: false,
        },

        is_mutation_loading: {
            assignment: false,
            estate_type: false,
            spec: false,
            feature: false,
        },
        
        is_query_loading: {
            assignment: false,
            estate_type: false,
            spec: false,
            feature: false,
        },

        is_grid_view: {
            assignment: false,
            estate_type: false,
            spec: false,
            feature: false,
        },

        has_more: {
            assignment: true,
            estate_type: true,
            spec: true,
            feature: true,
        },

        page: {
            assignment: 1,
            estate_type: 1,
            spec: 1,
            feature: 1,
        },

        chart_object: {
            assignment: null,
            estate_type: null,
            spec: null,
            feature: null,
        },

        counts: {
            assignment: {
                total: 0,
                trash: 0
            },
            estate_type: {
                total: 0,
                trash: 0
            },
            spec: {
                total: 0,
                trash: 0
            },
            feature: {
                total: 0,
                trash: 0
            },
        },

        charts: {
            assignment: {
                labels: [],
                data: [],
            },
            estate_type: {
                labels: [],
                data: [],
            },       
            spec: {
                labels: [],
                data: [],
            },          
            feature: {
                labels: [],
                data: [],
            },
        },

        selected_items: {
            assignment: [],
            estate_type: [],
            spec: [],
            feature: [],
        },

        form: {
            assignment: {
                title: {
                    type: 'String',
                    value: ''
                },
                description: {
                    type: 'String',
                    value: ''
                },
                color: {
                    type: 'String',
                    value: ''
                },
                has_sales_price: {
                    type: 'Boolean',
                    value: null
                },
                has_mortgage_price: {
                    type: 'Boolean',
                    value: null
                },
                has_rental_price: {
                    type: 'Boolean',
                    value: null
                }
            },
            estate_type: {
                title: {
                    type: 'String',
                    value: ''
                },
                description: {
                    type: 'String',
                    value: ''
                },
                icon: {
                    type: 'String',
                    value: ''
                },
                assignments: {
                    type: '[Int]',
                    value: [],
                    serverResolver: assignments => assignments.map( assignment => assignment.id )
                },
            },
            spec: {
                title: {
                    type: 'String',
                    value: ''
                },
                description: {
                    type: 'String',
                    value: ''
                },
                estate_type_id: {
                    type: 'Int',
                    value: null
                },
            },
            feature: {
                title: {
                    type: 'String',
                    value: ''
                },
                description: {
                    type: 'String',
                    value: ''
                },
                icon: {
                    type: 'String',
                    value: ''
                },
                is_detailable: {
                    type: 'Boolean',
                    value: null
                },
                estate_types: {
                    type: '[Int]',
                    value: [],
                    serverResolver: estate_types => estate_types.map( estate_type => estate_type.id )
                },
            },
        },

        selected: {
            assignment: {
                id: null,
                index: null
            },
            estate_type: {
                id: null,
                index: null
            },
            spec: {
                id: null,
                index: null
            },
            feature: {
                id: null,
                index: null
            },
        },
    },
}