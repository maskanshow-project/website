<template>
    <el-card class="mb-3" shadow="hover" body-style="padding: 0px !important">
        <div class="feature-item row rtl list">
            <router-link class="col-md-4" :to=" 'estate/' + estate.id ">
                <div class="feature-pic js-tilt ltr bg-estate"
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

            <router-link class="col-md-8" :to=" 'estate/' + estate.id ">
                <!-- Details -->
                <div class="feature-text mt-2">
                    <div class="text-center feature-title row">
                        <div class="col-md-7 pr-0">
                            <router-link :to=" 'estate/' + estate.id ">
                                <h5 class="bold"> 
                                    <span class="web-color"> {{ '#'+ ( this.$route.params.username && estate.code ? estate.code : estate.id ) }} </span>
                                    {{ ( !!estate.title ? estate.title : '' ||
                                        (!!estate.assignment ? estate.assignment.title +' ' : '') +
                                        (!!estate.estate_type ? estate.estate_type.title +' ' : '') +
                                        `${(estate.area).toLocaleString('fa-IR')} متری` )
                                        | truncate( 41 - ( this.$route.params.username && estate.code ? estate.code : estate.id ).toString().length )
                                    }}
                                </h5>
                            </router-link>
                            <p class="text-muted fs-12 rtl mt-2 bold">
                                <i class="fa fa-map-marker-alt fs-15 ml-1 web-color"></i>
                                {{  ( estate.street && estate.street.area ? estate.street.area.name +' ، ' : '' ) +
                                    ( estate.street ? 'خیابان ' + estate.street.name +' ، ' : '' ) +
                                    estate.address | truncate(45)
                                }}
                            </p>
                        </div>

                        <div class="col-md-5 border-right d-flex" :class="{ 'blur' : !!estate.assignmented_at || is_exist(estate.label) }">
                            <div class="flex-unset">
                                <v-avatar :size="50">
                                    <img :src=" 
                                        !( !!estate.assignmented_at || is_exist(estate.label) ) &&
                                        is_exist(estate.creator) &&
                                        estate.creator.is_public_info &&
                                        !!estate.creator.avatar &&
                                        !!estate.creator.avatar.small 
                                            ? url + estate.creator.avatar.small
                                            : '/img/user.png' "
                                            alt="avatar">
                                </v-avatar>
                            </div>
                            <div class="pr-4">
                                <p class="author-p">
                                    {{
                                        !( !!estate.assignmented_at || is_exist(estate.label) ) &&
                                        is_exist(estate.creator) &&
                                        estate.creator.is_public_info &&
                                        !!estate.creator.full_name &&
                                        estate.creator.full_name != " "
                                        ? estate.creator.full_name
                                        : 'ناشناس' 
                                    }}
                                </p>
                                <p class="fs-10 bold" :class="estate.want_cooperation ? 'text-success' : 'text-danger'"
                                    v-if="estate.registrar_type && estate.registrar_type.id == 2 || !estate.want_cooperation"> 
                                    {{
                                        estate.want_cooperation
                                        ? 'تمایل همکاری با مشاورین املاک'
                                        : 'عدم تمایل همکاری با مشاورین املاک'
                                    }}
                                </p>
                                <p class="fs-10 text-muted bold" v-else-if=" !( !!estate.assignmented_at || is_exist(estate.label) )
                                    && is_exist(estate.creator) && estate.creator.is_public_info ">
                                    {{ estate.creator.username+'@' }}
                                </p>
                                <p class="fs-10 text-muted bold" v-else> بدون اطلاعات </p>
                            </div>
                        </div>
                    </div>

                    <div class="room-info-warp rtl text-right">
                        <div class="row room-info d-flex rtl">
                            <template v-if="is_exist(estate.specifications)">
                                <template
                                    v-for="(spec,idx) in estate.specifications.slice( 0 , estate.specifications.length >= 8 ? 8 : estate.specifications.length )">
                                    <div :class="set_grid"
                                        v-if=" ( is_exist(spec.data) && spec.data != '[]' || spec.data == '0' ) || is_exist(spec.values) " :key="idx+'spec'">
                                        <p class="hvr-icon-back">
                                            <i :class="`fa fa-${ spec.row && spec.row.icon ? spec.row.icon : 'building' } hvr-icon web-color`"></i>
                                            <template v-if="is_exist(spec.values)">
                                                {{ join_props(
                                                    spec.values ,
                                                    spec.row ? spec.row.prefix : '' ,
                                                    spec.row ? spec.row.postfix : '' )
                                                    | truncate(25)
                                                }}
                                            </template>
                                            <template v-else-if="is_exist(spec.data) && is_json(spec.data)">
                                                {{ 
                                                    join_props( Json_parse(spec.data) ,
                                                    spec.row ? spec.row.prefix : '' ,
                                                    spec.row ? spec.row.postfix : '' )
                                                    | truncate(25)
                                                }}
                                            </template>
                                            <template v-else-if="is_exist(spec.data) || spec.data == '0'">
                                                {{ 
                                                    ( spec.row ? spec.row.prefix +' ' : '' ) +
                                                    spec.data +' '+
                                                    ( spec.row ? spec.row.postfix : '' )
                                                    | truncate(25)
                                                }}
                                            </template>
                                        </p>
                                    </div>
                                </template>
                            </template>

                            <template v-if="is_exist(estate.detailable_features)">
                                <template v-for="(feature,idx) in estate.detailable_features.slice( 0 , slice_features )">
                                    <div :class="set_grid" v-if="is_exist(feature.title)" :key="idx+'feature'">
                                        <p class="hvr-icon-back">
                                            <i :class="`fa fa-${ feature.icon || 'building' } hvr-icon web-color`"></i>
                                            {{ feature.title | truncate(25) }}
                                        </p>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>

                    <div class="property-price clearfix mt-4">
                        <div class="read-more rtl">
                        <div class="theme-btn">
                                <div v-if="assignment.has_sales_price">
                                    {{ estate.sales_price | price }}
                                    <span class="fs-12 pr-1 normal"> {{ label_price(estate.sales_price) }} </span>
                                </div>
                                <div class="d-flex" v-else-if="assignment.has_mortgage_price && assignment.has_rental_price">
                                    <div>
                                        {{ estate.mortgage_price | price }}
                                        <span class="fs-12 normal"> {{ label_price(estate.mortgage_price) }} رهن </span>
                                    </div>
                                    <span class="mx-3 line-price"> | </span>
                                    <div>
                                        {{ estate.rental_price | price }}
                                        <span class="fs-12 normal"> {{ label_price(estate.rental_price) }} اجاره </span>
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
        VAvatar
    } from 'vuetify/lib';
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
            elTooltip: Tooltip ,
            VAvatar
        } ,

        mounted() {
                
            // $('.js-tilt').tilt({
            //     maxTilt: 1.2,
            //     scale : 1.01
            // })

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

            set_grid() {

                let length = 0

                if( this.is_exist(this.estate.specifications) && this.is_exist(this.estate.detailable_features) ) {
                    length = this.estate.specifications.length + this.estate.detailable_features.length;
                } else if( this.is_exist(this.estate.specifications) ) {
                    length = this.estate.specifications.length;
                } else if( this.is_exist(this.estate.detailable_features) ) {
                    length = this.estate.detailable_features.length;
                }

                switch(length) {
                    case 1 : 
                        return 'col-md-12'
                        break;
                    case 2 : 
                        return 'col-md-12';
                        break;
                    case 3 : 
                        return 'col-md-6';
                        break;
                    case 4 : 
                        return 'col-md-6';
                        break;
                    case 5 : 
                        return 'col-md-4';
                        break;
                    case 6 : 
                        return 'col-md-4';
                        break;
                    case 7 : 
                        return 'col-md-3';
                        break;
                    case 8 : 
                        return 'col-md-3';
                        break;
                    default :
                        return 'col-md-3';
                        break;
                }
            } ,

            slice_features() {

                let valid_spec = this.estate.specifications
                .filter( el => ( this.is_exist(el.data) && el.data != '[]' ) || this.is_exist(el.values) );

                if( this.is_exist(this.estate.specifications) ) {
                    return valid_spec.length >= 8  ? 0 : 8 - valid_spec.length
                } else {
                    return this.estate.detailable_features.length >= 8 ? 8 : this.estate.detailable_features.length
                }
            }
            
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
            }

        }

    }
</script>

<style>

    .bookmark {
        position: absolute;
        padding: 20px;
        left: 0;
        top: 0;
    }

    .bookmark img {
        height: 27px;
        width: 30px;
        filter: drop-shadow(0px 0px 0.5px black);
    }

    .flex-unset {
        flex: unset !important;
    }

    .feature-pic.blur {
        filter: blur(2px);
    }

    .list .assignmented {
        position: absolute;
        display: flex;
        justify-content: center;
        align-items: center;
        height: calc( 100% - 15px );
        width: calc( 100% - 15px );
        border-radius: 20px;
        top: 7px;
        bottom: 0;
        right: -15px;
    }

    .stamp {
        transform: rotate(12deg);
        font-size: 3rem;
        font-weight: 700;
        display: inline-block;
        padding: 1rem;
        text-transform: uppercase;
        border-radius: 1rem;
        font-family: 'Courier';
        -webkit-mask-image: url('/img/stamp.png');
        -webkit-mask-size: 944px 604px;
        mix-blend-mode: multiply;
    }

    .is-nope {
        color: #D23;
        border: 0.5rem double #D23;
        transform: rotate(5deg);
        -webkit-mask-position: 2rem 3rem;
        font-size: 2rem;
        text-align: center;  
    }

    .el-tooltip__popper {
        font-size: 10px;
    }

    .bg-estate {
        background-repeat: no-repeat;
        background-size: cover;
        background-position: top center;
    }

    .line-price {
        color: #222222;
        font-weight: bold;
        font-size: 20px;
        transform: rotate(20deg);
    }

</style>

<style scoped>

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
        color: #484848;
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
        margin-right: -30px !important;
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
        background: linear-gradient(to bottom, rgba(34,34,34,0) 0%,rgba(34,34,34,0.99) 99%,rgba(34,34,34,1) 100%);
        background: -moz-linear-gradient(top, rgba(34,34,34,0) 0%, rgba(34,34,34,0.99) 99%, rgba(34,34,34,1) 100%);
        background: -webkit-linear-gradient(top, rgba(34,34,34,0) 0%,rgba(34,34,34,0.99) 99%,rgba(34,34,34,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00222222', endColorstr='#222222',GradientType=0 );
        content: "";
        z-index: 1;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
    }

    .feature-title {
        padding: 20px 25px 0px 25px !important;
        text-align: right !important;
    }

    .feature-pic.placeholder:before {
        background: linear-gradient(to bottom, rgba(34, 34, 34, 0) 0%,rgba(34, 34, 34, 0.3) 150%,rgba(34, 34, 34, 0.3) 100%);
        background: -moz-linear-gradient(top, rgba(34,34,34,0) 0%, rgba(34,34,34,0.3) 150%, rgba(34,34,34,0.3) 100%);
        background: -webkit-linear-gradient(top, rgba(34,34,34,0) 0%,rgba(34,34,34,0.3) 150%,rgba(34,34,34,0.3) 100%);
    }

</style>
