<template>
    <section class="welcome_area p_100 col-xs-6 bg-white">
        <div class="container">
            <div class="row welcome_inner rtl">
                <div class="col-md-6">
                    <div class="welcome_img float-right">
                        <img class="img-fluid rounded" src="/img/welcome-1.jpg" alt="">
                    </div>
                </div>
                <div class="col-md-6 text-right px-xs-4">
                    <div class="welcome_text">
                        <h4> به <span class="web-color"> "مسکن شو" </span> خوش آمدید </h4>
                        <p v-if="siteSetting.description"> {{ siteSetting.description }} </p>
                        <p v-else>
                            «مسکن شو» سامانه هوشمندی است که در حوزه «املاک و مستغلات» و
                            «مشاغل ساختمانی» و «اشتغال زایی» وخدمات وابسته به آن، فعالیت می‌کند.
                            مسکن شو به شما امکان فروش، اجاره و خرید ملک مورد نظرتان را می‌دهد.
                        </p>
                        <div class="row" v-show="false">
                            <div class="col-sm-4">
                                <div class="wel_item">
                                    <i class="lnr lnr-database web-color"></i>
                                    <h4> {{ (920).toLocaleString('fa-IR') }} </h4>
                                    <p> معامله </p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="wel_item">
                                    <i class="lnr lnr-book web-color"></i>
                                    <h4> {{ (740).toLocaleString('fa-IR') }} </h4>
                                    <p> پروژه </p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="wel_item">
                                    <i class="lnr lnr-users web-color"></i>
                                    <h4> {{ (12000).toLocaleString('fa-IR') }} </h4>
                                    <p> کاربر </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>

    import { mapState } from 'vuex';

    export default {
        computed : {
            ...mapState([
                'siteSetting'
            ])
        }
    }
</script>

<style>
    .p_100 {
        padding-top: 100px;
        padding-bottom: 100px;
    }
</style>
