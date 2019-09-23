import persianJs from 'persianjs'

export default {
    state: {
        user: [],
        role: [],
        blacklist_phone_number: [],
        message: [],
        office: [],

        filters: {
            user: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
            role: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
            blacklist_phone_number: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
            message: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
            office: {
                query: null,
                hasLogo: null,
                hasCategories: null,
                categories: [],
                categories_string: null
            },
        },

        has_loaded: {
            user: false,
            role: false,
            blacklist_phone_number: false,
            message: false,
            office: false,
        },

        is_open: {
            user: false,
            role: false,
            blacklist_phone_number: false,
            message: false,
            office: false,
        },
        
        is_incrementing: {
            user: true 
        },


        has_timestamps: {
            user: true,
            role: true,
            blacklist_phone_number: true,
            message: true,
            office: true,
        },

        is_mutation_loading: {
            user: false,
            role: false,
            blacklist_phone_number: false,
            message: false,
            office: false,
        },
        
        is_query_loading: {
            user: false,
            role: false,
            blacklist_phone_number: false,
            message: false,
            office: false,
        },

        is_creating: {
            user: false,
            role: false,
            blacklist_phone_number: false,
            message: false,
            office: false,
        },
        
        is_loading: {
            user: false,
            role: false,
            blacklist_phone_number: false,
            message: false,
            office: false,
        },

        is_grid_view: {
            user: false,
            role: false,
            blacklist_phone_number: false,
            message: false,
            office: false,
        },

        has_more: {
            user: true,
            role: true,
            blacklist_phone_number: true,
            message: true,
            office: true,
        },

        page: {
            user: 1,
            role: 1,
            blacklist_phone_number: 1,
            message: 1,
            office: 1,
        },

        chart_object: {
            user: null,
            role: null,
            blacklist_phone_number: null,
            message: null,
            office: null,
        },

        counts: {
            user: {
                total: 0,
                trash: 0
            },
            role: {
                total: 0,
                trash: 0
            },
            blacklist_phone_number: {
                total: 0,
                trash: 0
            },
            message: {
                total: 0,
                trash: 0
            },
            office: {
                total: 0,
                trash: 0
            },
        },

        charts: {
            user: {
                labels: [],
                data: [],
            },       
            role: {
                labels: [],
                data: [],
            },
            blacklist_phone_number: {
                labels: [],
                data: [],
            },
            message: {
                labels: [],
                data: [],
            },
            office: {
                labels: [],
                data: [],
            },
        },

        selected_items: {
            user: [],
            role: [],
            blacklist_phone_number: [],
            message: [],
            office: [],
        },

        image_field: {
            user: 'avatar',
        },

        form: {
            user: {
                first_name: {
                    type: 'String',
                    value: ''
                },
                last_name: {
                    type: 'String',
                    value: ''
                },
                avatar: {
                    type: 'Upload',
                    value: null,
                    file: null,
                    url: ''
                },
                email: {
                    type: 'String',
                    value: ''
                },
                username: {
                    type: 'String',
                    value: ''
                },
                address: {
                    type: 'String',
                    value: ''
                },
                phone_number: {
                    type: 'String',
                    value: '',
                    clientResolver: (value) => value ? persianJs(value).persianNumber().toString() : null
                },
                national_code: {
                    type: 'String',
                    value: ''
                },
                gender: {
                    type: 'Boolean',
                    value: null,
                    clientResolver: gender => {
                        return gender === 'true' ? true : gender === 'false' ? false : null
                    }
                },
                permissions: {
                    type: '[Int]',
                    value: [],
                    serverResolver: permissions => permissions.map(p => p.id),
                    clientResolver: (permissions, state) => {
                        let finalPermissions = []

                        permissions.forEach(permission => {
                            if ( !_.find( state.permissions_list, ['id', permission]).disabled )
                                finalPermissions.push(permission)
                        })
                        
                        return finalPermissions
                    }
                },
                roles: {
                    type: '[Int]',
                    value: [],
                    serverResolver: roles => roles.map(p => p.id)
                },
                is_public_info: {
                    type: 'Boolean',
                    value: false
                },
                is_deleted_image: {
                    type: 'Boolean',
                    value: false
                }
            },
            role: {
                display_name: {
                    type: 'String',
                    value: ''
                },
                description: {
                    type: 'String',
                    value: ''
                },
                permissions: {
                    type: '[Int]',
                    value: [],
                    serverResolver: permissions => permissions.map(p => p.id)
                },
            },
            blacklist_phone_number: {
                phone_number: {
                    type: 'String',
                    value: '',
                    clientResolver: (value) => persianJs(value).persianNumber().toString()
                },
                description: {
                    type: 'String',
                    value: ''
                },
            },
            message: {
                title: {
                    type: 'String',
                    value: '',
                },
                message: {
                    type: 'String',
                    value: ''
                },
                type: {
                    type: 'String',
                    value: ''
                },
                expired_at: {
                    type: 'String',
                    value: ''
                },
                role_id: {
                    type: 'Int',
                    value: null
                },
            },
            office: {
                name: {
                    type: 'String',
                    value: '',
                },
                username: {
                    type: 'String',
                    value: ''
                },
                description: {
                    type: 'String',
                    value: ''
                },
                address: {
                    type: 'String',
                    value: ''
                },
                phone_number: {
                    type: 'String',
                    value: '',
                    clientResolver: (value) => value ? persianJs(value).persianNumber().toString() : null
                },
                area_id: {
                    type: 'Int',
                    value: null,
                    clientResolver: (value) => value ? value : null
                },
            },
        },

        selected: {
            user: {
                id: null,
                index: null
            },
            role: {
                id: null,
                index: null
            },
            blacklist_phone_number: {
                id: null,
                index: null
            },
            message: {
                id: null,
                index: null
            },
            office: {
                id: null,
                index: null
            },
        },
    },
}