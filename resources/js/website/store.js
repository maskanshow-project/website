import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const store = new Vuex.Store({
    
    state : {

        loading : true ,
        siteSetting : {} ,

        dialog_filters : false ,
        dialog_sort : false ,

        register_modal : false ,
        login_modal : false ,
        reset_pass_modal : false ,
        change_pass_modal : false ,
        access_modal : false ,

        Auth : !!window.localStorage.getItem('JWT') ,
        url : '' ,
        req_url : !!window.localStorage.getItem('JWT') ? '/graphql/auth' : '/graphql' ,
        
        // User Information
        me : {} ,

        User_Location : [] ,

        office : {} ,

        // ========== Filters States ========== //
        city_areas : [] ,
        area_streets : [] ,

        // provinces : [] ,
        // cities : [] ,
        
        assignments : [] ,
        estate_types : [] ,

        dynamic_filters : {
            spec : {
                filters : []
            }
        } ,
        // ========== End Of Filters States ========== //

        Estates : [] ,
        Single_estate : {} ,

        offices : [] ,

        pagination : {
            total : ''
        } ,

        // ========== Articles States ========== //
        articles : [
            {
                id : '' ,
                title : '' ,
                description : '' ,
                image : {
                    meduim : ''
                } ,
                writer : {
                    id : '' ,
                    fullname : ''
                } ,
                // created_at : '' ,
                reading_time : '' ,
                comments : [] ,
                subjects : []
            }
        ] ,

        last_articles : [] ,

        subjects : [] ,

        single_article : {
            id : '' ,
            title : '' ,
            description : '' ,
            body : '' ,
            image : {
                big : ''
            } ,
            reading_time : '' ,
            created_at : ' ' ,
            subjects : [{
                id : '' ,
                title : '' ,
            }] ,
            tags : [{
                name : ''
            }] ,
            writer : {
                id : '' ,
                full_name : '' ,
                avatar : {
                    small : ''
                } ,
                social_links : [{
                    type : '' ,
                    value : ''
                }]
            } ,
            comments : [{
                id : '' ,
                title : '' ,
                message : '' ,
                writer : { full_name : '' } ,
                votes : {
                    likes : '' ,
                    dislikes : ''
                }
            }] ,
            questions: [{
                id : '' ,
                title : '' ,
                message : '' ,
                writer : { full_name : '' } ,
                votes : {
                    likes : '' ,
                    dislikes : ''
                }
            }]
        } ,
        // ========== End Of Articles States ========== //

    } ,

    mutations : {
        Set_state( state , data ) {
            state[data.prop] = data.val
        } ,

        Set_obj( state , data ) {
            state[data.prop] = { ...state[data.prop] , ...data.val }
        } ,

        // Query - Props - States
        Req_data( state , obj ) {
            if(obj.loading !== false) {
                state.loading = true;
            }

            axios({
                method : 'POST' ,
                url : state.req_url ,
                data : {
                    query : obj.query
                }
            })
            .then( ({data}) => {
                if( !!data.errors ) {
                    console.log(data.errors);
                    data.errors.forEach( Err => console.error(Err.message) )
                } else {

                    if( obj.changeDataResolver && typeof obj.changeDataResolver === "function" ) {
                        data = obj.changeDataResolver(data)
                    }

                    if( obj.resolver && typeof obj.resolver === "function" ) {
                        obj.resolver(data)
                    }

                    obj.props.forEach( ( el , index ) => {

                        if( obj.is_object ) {
                            state[obj.states[index]] = Object.assign( state[obj.states[index]] , (data.data[el].data ? data.data[el].data : data.data[el]) );
                        } else {
                            state[obj.states[index]] = data.data[el].data ? data.data[el].data : data.data[el]
                        }

                        if( data.data[el].total || data.data[el].total == 0 ) {
                            state.pagination.total = data.data[el].total
                        }
    
                    });

                }
            })
            .then( () => {
                setTimeout(() => {
                    state.loading = false
                }, 500);
            })
            .then( obj.afterResolver && typeof obj.afterResolver === "function" ? obj.afterResolver() : () => null )
            .catch( Err => {
                if( Err.response && Err.response.status === 401 ) {
                    window.localStorage.removeItem('JWT');
                    location.reload();
                } else {
                    console.error(Err);
                }
            })
        }
    }

});

export default store;