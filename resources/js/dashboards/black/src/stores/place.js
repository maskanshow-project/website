export default {
    state: {
        area: [],
        street: [],

        filters: {
            area: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
            street: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
        },

        has_loaded: {
            area: false,
            street: false,
        },

        image_field: {
            // 
        },

        is_mutation_loading: {
            area: false,
            street: false,
            variation: false,
        },
        
        is_query_loading: {
            area: false,
            street: false,
            variation: false,
        },

        is_open: {
            area: false,
            street: false,
            variation: false,
        },
        
        is_incrementing: {
            // 
        },

        has_timestamps: {
            area: true,
            street: true,
            variation: true,
        },
        
        is_creating: {
            area: false,
            street: false,
            variation: false,
        },
        
        is_loading: {
            area: false,
            street: false,
        },

        is_grid_view: {
            area: false,
            street: false,
        },

        has_more: {
            area: true,
        },

        page: {
            area: 1,
        },

        chart_object: {
            area: null,
            street: null,
        },

        counts: {
            area: {
                total: 0,
                trash: 0
            },
            street: {
                total: 0,
                trash: 0
            },
            variation: {
                total: 0,
                trash: 0
            },
        },

        charts: {
            area: {
                streets: [],
                data: [],
            },
        },

        selected_items: {
            area: [],
            street: [],
            variation: [],
        },

        form: {
            area: {
                city_id: {
                    type: 'Int',
                    value: null
                },
                name: {
                    type: 'String',
                    value: ''
                },
                lat: {
                    type: 'Float',
                    value: null
                },
                lng: {
                    type: 'Float',
                    value: null
                },
            },
            street: {
                area_id: {
                    type: 'Int',
                    value: null
                },
                name: {
                    type: 'String',
                    value: ''
                },
                lat: {
                    type: 'Float',
                    value: ''
                },
                lng: {
                    type: 'Float',
                    value: ''
                },
            },
        },

        selected: {
            area: {
                id: null,
                index: null
            },
            street: {
                id: null,
                index: null
            },
        },
    },
}