<template>
    <el-card class="mb-7" shadow="hover" body-style="padding: 0px !important">
        <div class="feature-item">

            <router-link :to=" 'estate/' + estate.id ">

                <div class="feature-pic js-tilt bg-estate"
                    :class="{ 'placeholder' : !img.has_img , 'blur' : !!estate.assignmented_at || is_exist(estate.label) }"
                    :style="{ backgroundImage : `url('${ img.has_img ? url+img.src : img.src }')` }">
                
                    <div class="sale-notic" :style="{ background : estate.assignment.color + ' !important' }"
                        v-if="!!estate.assignment && !!estate.estate_type">
                        {{ estate.assignment.title +' '+ estate.estate_type.title }}
                    </div>

                    <div class="bookmark" @click.prevent="add_fav">
                        <img v-show="!is_fav" src="/img/bookmark.svg">
                        <img v-show="is_fav" src="/img/bookmark-fill.svg">
                    </div>

                    <div class="room-info text-left d-flex as-info pt-0">
                        <div class="w-50">
                            <el-tooltip
                                class="item"
                                effect="dark"
                                placement="top-start"
                                :disabled="!(!!estate.registrar_type && !!estate.registrar_type.description)"
                                :content="!!estate.registrar_type ? estate.registrar_type.description : ''">
                                <p class="text-left">
                                    <i class="fa fa-user ml-0 ml-1"></i>
                                    {{ 'توسط ' +
                                    ( !!estate.is_mine ? 'شما' : (!!estate.registrar_type ? estate.registrar_type.display_name : 'مالک') ) }}
                                </p>
                            </el-tooltip>
                        </div>
                        <div class="w-50 text-right mr-0 ml-1 rtl">
                            <p><i class="fa fa-clock"></i> {{ estate.created_at | to_fa }} </p>
                        </div>	
                    </div>

                </div>

                <div class="assignmented" v-if="!!estate.assignmented_at">
                    <div>
                        <span class="stamp is-nope"> واگذار شده </span>
                    </div>
                </div>

                <div class="assignmented" v-else-if="!estate.is_active">
                    <div>
                        <span class="stamp is-nope">
                            تایید نشده
                        </span>
                    </div>
                </div>

                <div class="assignmented" v-else-if="is_exist(estate.label)">
                    <div>
                        <span class="stamp is-nope"
                            :style="{ color : `${estate.label.color} !important` , borderColor : `${estate.label.color} !important` }">
                            {{ estate.label.title }}
                        </span>
                    </div>
                </div>
                
            </router-link>

            <router-link :to=" 'estate/' + estate.id ">
                <div class="feature-text">

                    <div class="text-center feature-title rtl">
                        <router-link :to=" 'estate/' + estate.id ">
                                <h5 class="bold"> 
                                    <span class="web-color"> {{ '#'+ ( this.$route.params.username && estate.code ? estate.code : estate.id ) }} </span>
                                    {{ ( !!estate.title ? estate.title : '' ||
                                        (!!estate.assignment ? estate.assignment.title +' ' : '') +
                                        (!!estate.estate_type ? estate.estate_type.title +' ' : '') +
                                        `${(estate.area).toLocaleString('fa-IR')} متری` )
                                        | truncate( 30 - ( this.$route.params.username && estate.code ? estate.code : estate.id ).toString().length )
                                    }}
                                </h5>
                        </router-link>
                        <p class="text-muted fs-12 rtl mt-2 bold">
                            <i class="fa fa-map-marker-alt fs-15 ml-1 web-color"></i>
                            {{  ( estate.street && estate.street.area ? estate.street.area.name +' ، ' : '' ) +
                                ( estate.street ? 'خیابان ' + estate.street.name +' ، ' : '' ) +
                                estate.address | truncate(40)
                            }}
                        </p>
                    </div>

                    <div class="room-info-warp rtl text-right">
                        <div class="row room-info">

                            <template v-if="is_exist(estate.specifications)">
                                <template
                                    v-for="(spec,idx) in estate.specifications.slice( 0 , estate.specifications.length >= 4 ? 4 : estate.specifications.length )">
                                    <div class="col-6"
                                        v-if=" ( is_exist(spec.data) && spec.data != '[]' || spec.data == '0' ) || is_exist(spec.values) " :key="idx+'spec'">
                                        <p class="hvr-icon-back">
                                            <i :class="`fa fa-${ spec.row && spec.row.icon ? spec.row.icon : 'building' } hvr-icon web-color`"></i>
                                            <template v-if="is_exist(spec.values)">
                                                {{ join_props(
                                                    spec.values ,
                                                    spec.row ? spec.row.prefix : '' ,
                                                    spec.row ? spec.row.postfix : '' )
                                                    | truncate(15)
                                                }}
                                            </template>
                                            <template v-else-if="is_exist(spec.data) && is_json(spec.data)">
                                                {{ 
                                                    join_props( Json_parse(spec.data) ,
                                                    spec.row ? spec.row.prefix : '' ,
                                                    spec.row ? spec.row.postfix : '' )
                                                    | truncate(15)
                                                }}
                                            </template>
                                            <template v-else-if="is_exist(spec.data) || spec.data == '0'">
                                                {{ 
                                                    ( spec.row ? spec.row.prefix +' ' : '' ) +
                                                    spec.data +' '+
                                                    ( spec.row ? spec.row.postfix : '' )
                                                    | truncate(15)
                                                }}
                                            </template>
                                        </p>
                                    </div>
                                </template>
                            </template>

                            <template v-if="is_exist(estate.detailable_features)">
                                <template v-for="(feature,idx) in estate.detailable_features.slice( 0 , slice_features )">
                                    <div class="col-6" v-if="is_exist(feature.title)" :key="idx+'feature'">
                                        <p class="hvr-icon-back">
                                            <i :class="`fa fa-${ feature.icon || 'building' } hvr-icon web-color`"></i>
                                            {{ feature.title | truncate(15) }}
                                        </p>
                                    </div>
                                </template>
                            </template>

                        </div>
                    </div>

                    <div class="property-price clearfix">
                        <div class="read-more rtl">
                            <div class="theme-btn">

                                <div v-if="assignment.has_sales_price">
                                    {{ estate.sales_price | price }}
                                    <span class="fs-12 pr-1 normal"> {{ label_price(estate.sales_price) }} </span>
                                </div>
                                <div class="d-flex" v-else-if="assignment.has_mortgage_price && assignment.has_rental_price">
                                    <div>
                                        {{ estate.mortgage_price | multi_price }}
                                        <span class="fs-12 normal"> {{ label_multi_price(estate.mortgage_price) }} رهن </span>
                                    </div>
                                    <span class="mx-2"></span>
                                    <div>
                                        {{ estate.rental_price | multi_price }}
                                        <span class="fs-12 normal"> {{ label_multi_price(estate.rental_price) }} اجاره </span>
                                    </div>
                                </div>
                                <div v-else>
                                    {{ ( assignment.has_mortgage_price ? estate.mortgage_price : estate.rental_price) | price }}
                                    <span class="fs-12 pr-1 normal">
                                        {{ label_price( assignment.has_mortgage_price ? estate.mortgage_price : estate.rental_price) }}
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="price rtl"> {{ (estate.area).toLocaleString('fa-IR') }} متری </div>
                    </div>

                </div>
            </router-link>

        </div>
    </el-card>
</template>

<script>

    import {
        Card ,
        Tooltip
    } from 'element-ui';
    import mixin from '../../mixin';
    import moment from '../../moment';
    import { mapState } from 'vuex';

    export default {
        props : ['estate'] ,
        mixins : [mixin,moment] ,

        components: {
            elCard: Card ,
            elTooltip: Tooltip
        } ,

        mounted() {

            // $('.js-tilt').tilt({
            //     maxTilt: 1.2,
            //     scale : 1.01
            // })

        } ,

        filters : {

            price(val) {
                if(!val) {
                    return 'توافقی'
                } else if(val < 1000000) {
                    return (val/1000).toLocaleString('fa-IR')
                } else if(val < 1000000000) {
                    return (val/1000000).toLocaleString('fa-IR')
                } else if(val >= 1000000000) {
                    return (val/1000000000).toLocaleString('fa-IR')
                }
            } ,

            multi_price(val) {
                if(!val) {
                    return 'توافقی'
                } else if(val < 1000000) {
                    return (val/1000).toLocaleString('fa-IR')
                } else if(val < 1000000000) {
                    return parseFloat((val/1000000).toFixed(1)).toLocaleString('fa-IR')
                } else if(val >= 1000000000) {
                    return parseFloat((val/1000000000).toFixed(2)).toLocaleString('fa-IR')
                }
            }

        } ,

        computed : {

            ...mapState([
                'assignments' ,
                'url' ,
                'Auth' ,
                'req_url'
            ]) ,

            assignment() {
                if( !_.isEmpty(this.assignments) && !_.isEmpty(this.estate.assignment) && !!this.estate.assignment.id ) {
                    return this.assignments.filter( el => {
                        if( el.id == this.estate.assignment.id ) {
                            return {
                                has_sales_price : el.has_sales_price ,
                                has_rental_price : el.has_rental_price ,
                                has_mortgage_price : el.has_mortgage_price ,
                            }
                        }
                    })[0]
                } else {
                    return {
                        id : '' ,
                        title : '' ,
                        has_sales_price : false ,
                        has_rental_price : false ,
                        has_mortgage_price : false
                    }
                }
            } ,

            img() {
                return !(_.isEmpty(this.estate.photos)) ?
                    { src : this.estate.photos[0].medium , has_img : true } : { src : '/img/default.jpg' , has_img : false }
            } ,

            is_fav() {
                if(this.is_exist(this.estate)) {
                    return this.estate.is_favorite;
                } else {
                    return false;
                }
            } ,

            slice_features() {

                let valid_spec = this.estate.specifications
                .filter( el => ( this.is_exist(el.data) && el.data != '[]' ) || this.is_exist(el.values) );

                if( this.is_exist(this.estate.specifications) ) {
                    return valid_spec.length >= 4 ? 0 : 4 - valid_spec.length;
                } else {
                    return this.estate.detailable_features.length >= 4 ? 4 : this.estate.detailable_features.length;
                }
            }

        } ,

        methods : {

            add_fav() {
                if(!this.Auth) {

                    this.notif( 'کاربر گرامی ، برای ثبت علاقه مندی‌ها ابتدا باید وارد سایت شوید' , 'warning' , 'error' )
                    return;
                    
                } else {

                    let query = `
                        mutation {
                            ${ !this.is_fav ? 'addFavorite' : 'removeFavorite' }( estate : "${this.estate.id}" ) {
                                status
                                message
                            }
                        }
                    `
        
                    axios({
                        method : 'POST' ,
                        url : this.req_url ,
                        data : {
                            query : query
                        }
                    })
                    .then( ({data}) => {
                        if( !!data.errors ) {
                            data.errors.forEach( Err => console.error(Err.message) )
                        } else {
                            if( !this.is_fav && data.data.addFavorite.status == 200 ) {
                                this.notif( data.data.addFavorite.message , 'danger' , 'favorite' );
                                this.$store.state.Estates.map( (el,idx) => {
                                    if( el.id == this.estate.id ) {
                                        this.$store.state.Estates[idx].is_favorite = true;
                                    }
                                })
                            } else if( this.is_fav && data.data.removeFavorite.status == 200 ) {
                                this.notif( data.data.removeFavorite.message , 'warning' , 'close' );
                                this.$store.state.Estates.map( (el,idx) => {
                                    if( el.id == this.estate.id ) {
                                        this.$store.state.Estates[idx].is_favorite = false;
                                    }
                                })
                            } else {
                                this.notif( !this.is_fav ? data.data.addFavorite.message : data.data.removeFavorite.message , 'warning' , 'error' );
                            }
                        }
                    })
                    .catch( Err => console.log( Err ) )

                }
            } ,

            join_props( values , prefix , postfix ) {

                if( typeof values != 'object' ) return [];

                let arr = [];
                
                if(!prefix) prefix = '';
                if(!postfix) postfix = '';

                values.map( el => {
                    arr.push( prefix +' '+  el.value +' '+ postfix );
                })

                return arr.join(' ، ');
            } ,

            label_price(val) {
                if(!val) {
                    return '.';
                } else if(val < 1000000) {
                    return `هزار تومان`
                } else if(val < 1000000000) {
                    return `میلیون تومان`
                } else if(val >= 1000000000) {
                    return `میلیارد تومان`
                }
            } ,

            label_multi_price(val) {
                if(!val) {
                    return '.';
                } else if(val < 1000000) {
                    return ``
                } else if(val < 1000000000) {
                    return `م`
                } else if(val >= 1000000000) {
                    return `م`
                }
            } ,

            Json_parse(val) {
                return JSON.parse(val)
            } ,

            is_json(str) {
                try {
                    JSON.parse(str);
                } catch (e) {
                    return false;
                }
            } ,

        }
    }
    
</script>

<style scoped>

    .assignmented {
        position: absolute;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        margin-top: 20%;
        height: 100%;
        width: 100%;
        border-radius: 20px;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
    }

    .el-card {
        overflow: visible;
        border-color: #e4e7ed;
        border-radius: 13px;
    }

    .el-card.is-hover-shadow:hover {
        transform: scale(1.005);
    }

    .author-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .author-p {
        font-size: 11px;
        font-weight: 600;
    }

    .as-info {
        margin: auto auto 10px auto;
        z-index: 1;
        border: unset !important;
        width: 90%;
    }

    .as-info div p {
        display: inline-block;
        color: #ffffff;
        font-size: 12px;
        margin: 0px !important;
    }

    .as-info div p i {
        margin-left: 5px;
    }

    .feature-pic {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: -20px !important;
        border-radius: 20px;
        margin: 15px;
        box-shadow: 0px 6px 20px -14px #000, 0px 3px 25px -23px #28b5f6;
    }

    .feature-pic:before {
        position: absolute;
        left: 0;
        bottom: 0;
        height: 40%;
        width: 100%;
        background: -moz-linear-gradient(top, rgba(34,34,34,0) 0%, rgba(34,34,34,0.99) 99%, rgba(34,34,34,1) 100%);
        background: -webkit-linear-gradient(top, rgba(34,34,34,0) 0%,rgba(34,34,34,0.99) 99%,rgba(34,34,34,1) 100%);
        background: linear-gradient(to bottom, rgba(34,34,34,0) 0%,rgba(34,34,34,0.99) 99%,rgba(34,34,34,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00222222', endColorstr='#222222',GradientType=0 );
        content: "";
        z-index: 1;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
    }

    .feature-title {
        height: 71px;
        padding: 20px 25px 0px 25px !important;
        text-align: right !important;
    }

    .feature-pic.placeholder:before {
        background: linear-gradient(to bottom, rgba(34, 34, 34, 0) 0%,rgba(34, 34, 34, 0.3) 150%,rgba(34, 34, 34, 0.3) 100%);
        background: -moz-linear-gradient(top, rgba(34,34,34,0) 0%, rgba(34,34,34,0.3) 150%, rgba(34,34,34,0.3) 100%);
        background: -webkit-linear-gradient(top, rgba(34,34,34,0) 0%,rgba(34,34,34,0.3) 150%,rgba(34,34,34,0.3) 100%);
    }


</style>

