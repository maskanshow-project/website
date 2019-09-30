<template>
    <section class="clients_logo_area pt-5 pb-0" v-if="has_offices">
        <div class="container">
            <div class="main_title">
                <h2> املاک های مسکن شو </h2>
            </div>
            <div class="clients_slider owl-carousel">
                <div class="item" v-for="office in offices" :key="office.id">
                    <router-link :to=" '/'+office.username ">
                        <div class="office mx-1 my-4 p-4">
                            <v-avatar class="mb-4" :size="100">
                                <img :src=" office.owner && office.owner.avatar ? url + office.owner.avatar.thumb : '/img/user.png' " alt="avatar">
                            </v-avatar>

                            <p class="text-center mb-2 mt-2">
                                {{ ( office.name ? 'املاک ' + office.name : 'ناشناس' ) | truncate(20) }}
                            </p>
                            <span>
                                {{ office.owner && office.owner.full_name && office.owner.full_name != " " 
                                    ? office.owner.full_name
                                    : 'ناشناس' 
                                    | truncate(20)
                                }}
                            </span>

                        </div>
                    </router-link>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import {
        VAvatar
    } from 'vuetify/lib';
    import { mapState } from 'vuex';

    export default {

        components: {
            VAvatar
        } ,

        computed : {
            ...mapState([
                'offices' ,
                'url'
            ]) ,

            has_offices() {
                if(!_.isEmpty(this.offices)) {
                    setTimeout(() => {
                        $('.clients_slider').owlCarousel({
                            loop:false,
                            rtl : true,
                            items: 5,
                            nav: false,
                            autoplay: false,
                            smartSpeed: 1500,
                            dots:false, 
                            loop : true,
                            responsiveClass: true,
                            responsive: {
                                0: {
                                    items: 2,
                                },
                                400: {
                                    items: 2,
                                },
                                575: {
                                    items: 3,
                                },
                                768: {
                                    items: 4,
                                },
                                992: {
                                    items: 5,
                                }
                            }
                        })
                    }, 500);
                }
                return !_.isEmpty(this.offices)
            }
        }

    }
</script>

<style scoped>

    .clients_slider .item p {
        color: #484848;
        font-weight: bold;
    }

    .clients_slider .item span {
        font-size: 12px;
        color: #868e96 !important;
    }

    .item .office {
        border-radius: 7px;
        transition: all 0.3s;
    }

    .item .office:hover {
        box-shadow: 0 5px 10px -8px #00bcd421, 0 6px 25px -12px rgba(0,0,0,.1) !important;
        background: #fff;
    }

</style>
