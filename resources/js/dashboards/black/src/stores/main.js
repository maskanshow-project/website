import Vue from 'vue'
import Vuex from 'vuex'

import feature from './feature'
import financial from './financial'
import group from './group'
import place from './place'
import blog from './blog'
import estate from './estate'
import user from './user'
import spec from './spec'
import setting from './setting'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        feature,
        financial,
        group,
        blog,
        estate,
        place,
        user,
        spec,
        setting
    },
    state: {
        MAP_API_KEY: 'service.fDOivWbTrdb7yZB7m6rWY6SkWXjqnAaDdZWJV8Cn',

        inactive_comments: 0,
        
        roles: [],
        permissions: [],
        permissions_list: [],
        me: {
            id: null,
            first_name: '',
            last_name: '',
            full_name: '',
            email: '',
            email: '',
            visited_estate_count: 0,
            remaining_hits_count: 0,
            registered_estate_count: 0,
            remaining_registered_count: 0,
            validity_end_at: null,
            payments_count: null,
            last_payment: null,
            avatar: {
                thumb: ''
            },
            plan: {
                title: '',
                credit_days_count: 0,
                visited_estate_count: 0,
                registered_estate_count: 0
            }
        }, 
        siteSetting: {
            title: null
        },
        icons: [
            {
                label: 'Accessibility',
                icons: [
                    'assistive-listening-systems',
                    'audio-description',
                    'blind',
                    'braille',
                    'closed-captioning',
                    'deaf',
                    'low-vision',
                    'phone-volume',
                    'question-circle',
                    'tty',
                    'universal-access',
                    'wheelchair',
                ]
            },
            {
                label: 'Alert',
                icons: [
                    'bell',
                    'bell-slash',
                    'exclamation',
                    'exclamation-circle',
                    'radiation',
                    'radiation-alt',
                    'skull-crossbones',
                    
                ]
            },
            {
                label: 'Animals',
                icons: [
                    'cat',
                    'crow',
                    'dog',
                    'dove',
                    'dragon',
                    'feather',
                    'feather-alt',
                    'fish',
                    'frog',
                    'hippo',
                    'horse',
                    'horse-head',
                    'kiwi-bird',
                    'otter',
                    'paw',
                    'spider',
                ]
            },
            {
                label: 'Arrows',
                icons: [
                    'angle-double-down',
                    'angle-double-left',
                    'angle-double-right',
                    'angle-double-up',
                    'angle-down',
                    'angle-left',
                    'angle-right',
                    'angle-up',
                    'arrow-circle-down',
                    'arrow-circle-left',
                    'arrow-circle-right',
                    'arrow-circle-up',
                ]
            },
            {
                label: 'Audio & Video',
                icons: [
                    'audio-description',
                    'broadcast-tower',
                    'closed-captioning',
                    'compress',
                    'compress-arrows-alt',
                    'eject',
                    'file-audio',
                    'file-video',
                    'film',
                    'headphones',
                    'microphone',
                    'microphone-alt',
                    'music',
                    'photo-video',
                    'podcast',
                    'rss',
                    'video',
                ]
            },
            {
                label: 'Automotive',
                icons: [
                    'air-freshener',
                    'ambulance',
                    'bus-alt',
                    'car-alt',
                    'car-battery',
                    'car-crash',
                    'charging-station',
                    'gas-pump',
                    'motorcycle',
                    'oil-can',
                    'shuttle-van',
                    'tachometer-alt',
                ]
            },
            {
                label: 'Beverage',
                icons: [
                    'beer',
                    'blender',
                    'cocktail',
                    'coffee',
                    'flask',
                    'glass-cheers',
                    'glass-whiskey',
                    'glass-martini-alt',
                    'mug-hot',
                    'wine-bottle',
                    'wine-glass-alt',
                ]
            },
            {
                label: 'Buildings',
                icons: [
                    'archway',
                    'building',
                    'campground',
                    'church',
                    'city',
                    'clinic-medical',
                    'dungeon',
                    'gopuram',
                    'home',
                    'hospital',
                    'hospital-alt',
                    'hotel',
                    'house-damage',
                    'igloo',
                    'industry',
                    'kaaba',
                    'landmark',
                    'monument',
                    'mosque',
                    'place-of-worship',
                    'school',
                    'store',
                    'store-alt',
                    'synagogue',
                    'torii-gate',
                    'university',
                    'vihara',
                    'warehouse',   
                ]
            },
            {
                label: 'Business',
                icons: [
                    'address-book',
                    'archive',
                    'balance-scale',
                    'balance-scale-left',
                    'birthday-cake',
                    'book',
                    'briefcase',
                    'building',
                    'bullhorn',
                    'business-time',
                    'calculator',
                    'calendar-alt',
                    'certificate',
                    'chart-area',
                    'chart-bar',
                    'chart-line',
                    'clipboard',
                    'columns',
                    'cut',
                    'envelope',
                    'envelope-open',
                    'eraser',
                    'fax',
                    'file',
                    'folder-open',
                    'highlighter',
                    'glasses',   
                ]
            },
            {
                label: 'Camping',
                icons: [
                    'binoculars',   
                    'compass',
                    'fire',
                    'first-aid',
                    'hiking',
                    'map',
                    'map-marked',
                    'map-marked-alt',
                    'map-signs',
                    'mountain',
                    'route',
                    'toilet-paper',
                    'tree',   
                ]
            },
            {
                label: 'Childhood',
                icons: [
                    'apple-alt',
                    'baby',
                    'baby-carriage',
                    'bath',
                    'biking',
                    'birthday-cake',
                    'cookie',
                    'cookie-bite',
                    'ice-cream',
                    'mitten',
                    'robot',
                    'school',
                    'shapes',
                    'snowman'
                ]
            },
            {
                label: 'Design',
                icons: [
                    'adjust',
                    'bezier-curve',
                    'crop',
                    'crosshairs',
                    'drafting-compass',
                    'draw-polygon',
                    'eraser',
                    'eye',
                    'eye-dropper',
                    'fill-drip',
                    'highlighter',
                    'icons',
                    'layer-group',
                    'magic',
                    'marker',
                    'object-group',
                    'object-ungroup',
                    'paint-roller',
                    'palette',
                    'paste',
                    'pen',
                    'pen-nib',
                    'pencil-ruler',
                    'ruler-combined',
                    'save',
                    'splotch',
                    'spray-can',
                    'stamp',
                    'tint',
                    
                ]
            },
            {
                label: 'Finance',
                icons: [
                    'balance-scale',
                    'cash-register',
                    'chart-line',
                    'chart-pie',
                    'coins',
                    'comment-dollar',
                    'credit-card',
                    'donate',
                    'file-invoice',
                    'file-invoice-dollar',
                    'landmark',
                    'money-bill',
                    'money-bill-alt',
                    'money-check',
                    'money-check-alt',
                    'percentage',
                    'piggy-bank',
                    'receipt',
                    'wallet',
                    
                ]
            },
            {
                label: 'Hotel',
                icons: [
                    'baby-carriage',
                    'bath',
                    'bed',
                    'briefcase',
                    'car',
                    'plane-arrival',
                    'taxi',
                    'tram',
                    'globe-africa',
                    'bus-alt',
                    'couch',
                    'fan',
                    'cocktail',
                    'coffee',
                    'concierge-bell',
                    'dice',
                    'dice-five',
                    'door-closed',
                    'door-open',
                    'dumbbell',
                    'glass-martini',
                    'hot-tub',
                    'hotel',
                    'infinity',
                    'key',
                    'luggage-cart',
                    'shower',
                    'shuttle-van',
                    'smoking',
                    'snowflake',
                    'spa',
                    'suitcase',
                    'suitcase-rolling',
                    'swimmer',
                    'swimming-pool',
                    'tv',
                    'umbrella-beach',
                    'utensils',
                    'wheelchair',
                    'wifi',
                ]
            },
            {
                label: 'Weather',
                icons: [
                    'bolt',
                    'cloud',
                    'cloud-meatball',
                    'cloud-moon',
                    'cloud-moon-rain',
                    'cloud-rain',
                    'cloud-showers-heavy',
                    'cloud-sun',
                    'cloud-sun-rain',
                    'meteor',
                    'moon',
                    'poo-storm',
                    'rainbow',
                    'smog',
                    'snowflake',
                    'sun',
                    'temperature-high',
                    'temperature-low',
                    'umbrella',
                    'water',
                    'wind'
                ]
            },
        ]
    },
    mutations: {
        setData(state, data)
        {
            return state[data.group][data.type] = data.data
        },
        setAttr(state, data)
        {
            return state[data.group][data.attr][data.type] = data.data
        },
        setFormData(state, data)
        {
            return state[data.group].form[data.type][data.field].value = data.value
        },
        setFormImage(state, data)
        {
            const field = state[data.group].image_field[data.type]

            return state[data.group].form[data.type][field] = {
                type: 'Upload',
                value: null,
                file: data.file ? data.file : null,
                url: data.file ? URL.createObjectURL(data.file) : ''
            }
        },
        clearForm(state, data)
        {
            let form = state[data.group].form[data.type]

            for (let field in form)
            {
                switch ( form[field].type )
                {
                    case 'String':
                        form[field].value = ''
                        break;

                    case 'Int':
                    case 'Float':
                    case 'Boolean':
                        form[field].value = field === 'is_deleted_image' ? false : null
                        break;

                    case '[Int]':
                    case '[String]':
                    case '[Upload]':
                        form[field].value = []
                        break;

                    case 'Upload':
                        form[field].value = null
                        form[field].file = null
                        form[field].url = ''
                        break;
                }
            }

            return state[data.group].form[data.type] = form
        },
        fillFormForEditing(state, data)
        {
            let form = state[data.group].form[data.type]

            for (let field in form)
            {
                let value = data.row[field]
                
                if ( typeof form[field].serverResolver === "function" )
                    value = form[field].serverResolver(value)

                switch ( form[field].type )
                {
                    case 'String':
                        form[field].value = value ? value : ''
                        break;

                    case 'Int':
                    case 'Float':
                    case 'Boolean':
                        form[field].value = value ? value : field === 'is_deleted_image' ? false : null
                        break;

                    case '[Int]':
                    case '[String]':
                    case '[Upload]':
                        form[field].value = value ? value : []
                        break;

                    case 'Upload':
                        form[field].value = null
                        form[field].file = null
                        form[field].url = value ? value.medium ? value.medium : value.thumb : ''
                        break;
                }
            }

            return state[data.group].form[data.type] = form
        },
        setRoles(state, roles)
        {
            return state.roles = roles.map(value => value.name)
        },
        setPermissions(state, permissions)
        {
            return state.permissions = permissions.map(value => value.name)
        },
        setMeInfo(state, data)
        {
            return state.me = data
        },
        setInactiveComments(state, count)
        {
            return state.inactive_comments = count
        },
        setSiteSetting(state, data)
        {
            return state.siteSetting = data
        },
        setPermissionsList(state, permissions)
        {
            return state.permissions_list = permissions
        }
    },
    actions: {
        getData({ commit, state }, inputs)
        {
            if( state[inputs.group][inputs.type].length > 0 )
                return state[inputs.group][inputs.type]

            var query;

            if ( state[inputs.group].query  && state[inputs.group].query[inputs.type] )
            {
                query = state[inputs.group].query[inputs.type]
            }
            else
            {
                query = `{
                    allData: ${inputs.query}
                }`
            }

            axios.post('/graphql/auth', { query: query })
            .then(({data}) => {

                commit('setData', {
                    group: inputs.group,
                    type: inputs.type,
                    data: state[inputs.group].handleQuery && state[inputs.group].handleQuery[inputs.type]
                        ? state[inputs.group].handleQuery[inputs.type](data)
                        : data.data.allData.data
                })

                commit('setAttr', {
                    group: inputs.group,
                    type: inputs.type,
                    attr: 'counts',
                    data: {
                        total: data.data.allData.total,
                        trash: data.data.allData.trash
                    }
                })

                commit('setAttr', {
                    group: inputs.group,
                    type: inputs.type,
                    attr: 'charts',
                    data: {
                        labels: data.data.allData.chart.map(period => period.month),
                        data: data.data.allData.chart.map(period => period.count)
                    }
                })
            })
        },
        getPermissions({ commit, state }, inputs)
        {
            if( state.permissions.length > 0 )
                return state.permissions

            axios.post('/graphql/auth', {
                query: `{
                    me {
                        id first_name last_name full_name username email
                        visited_estate_count remaining_hits_count validity_end_at
                        registered_estate_count remaining_registered_count
                        payments_count last_payment
                        plan { id title credit_days_count visited_estate_count registered_estate_count }
                        avatar { id file_name thumb }
                        allPermissions { id name display_name description }
                        roles { id name }
                    }

                    siteSetting {
                        title
                    }

                    assignmented: estates(has_assignment_request: true, is_assignmented: false, per_page: 1) { data { id } total }
                    inactive: estates(is_active: false, has_reject_reason: false, per_page: 1) { data { id } total }
                }`
            })
            .then(({data}) => {
                commit('setRoles', data.data.me.roles)
                commit('setPermissions', data.data.me.allPermissions)
                commit('setPermissionsList', data.data.me.allPermissions)
                commit('setMeInfo', data.data.me)
                commit('setSiteSetting', data.data.siteSetting)

                
                if ( data.data.me.allPermissions.filter(i => i.name === 'read-comment').length )
                {
                    axios.post('/graphql/auth', {
                        query: `{
                            comments(is_accept: false, per_page: 1) { total data { id } }
                        }`
                    })
                    .then(({data}) => commit('setInactiveComments', data.data.comments.total))    
                }
                
                commit('setAttr', {
                    group: 'estate',
                    type: 'inactive_estate',
                    attr: 'counts',
                    data: { total: data.data.inactive.total, trash: 0 }
                })
                
                commit('setAttr', {
                    group: 'estate',
                    type: 'assignmented_estate',
                    attr: 'counts',
                    data: { total: data.data.assignmented.total, trash: 0 }
                })
            })
            .catch(error => {
                // return console.log(error)

                localStorage.removeItem('JWT')
                window.location = "/login"
            })
        },
    }
})