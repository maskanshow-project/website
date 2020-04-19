export default {
    state: {
        subject: [],

        has_loaded: {
            subject: false,
        },

        is_open: {
            subject: false,
        },

        is_mutation_loading: {
            subject: false,
        },
        
        is_query_loading: {
            subject: false,
        },
        
        is_incrementing: {
            // 
        },

        has_timestamps: {
            subject: true,
        },
        
        is_creating: {
            subject: false,
        },
        
        is_loading: {
            subject: false,
        },

        chart_object: {
            subject: null,
        },

        counts: {
            subject: {
                total: 0,
                trash: 0
            },
        },

        charts: {
            subject: {
                labels: [],
                data: [],
            },       
        },

        selected_items: {
            subject: [],
        },

        image_field: {
            subject: 'logo',
        },

        form: {
            subject: {
                parent_id: {
                    type: 'Int',
                    value: null
                },
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
                logo: {
                    type: 'Upload',
                    value: null,
                    file: null,
                    url: ''
                },
                is_deleted_image: {
                    type: 'Boolean',
                    value: false
                }
            },
        },

        selected: {
            subject: {
                id: null,
                index: null
            },
        },

        query: {
            subject: `{
                allData: subjects {
                    data {
                        id title description icon logo { id file_name thumb } childs {
                            id title description icon logo { id file_name thumb } childs {
                                id title description icon logo { id file_name thumb } childs {
                                    id title description icon logo { id file_name thumb } childs {
                                        id title description icon
                                    }
                                }
                            }
                        }
                    }
                    total trash chart { month count }
                }
            }`
        },
    },
}