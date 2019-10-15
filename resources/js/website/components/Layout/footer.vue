<template>
    <footer>

        <svg class="defs-only position-fixed" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink">
            <symbol id="wave">
                <svg viewBox="0 0 100 50" preserveAspectRatio="none">
                    <g>
                        <path class="path-footer" d="M100,30 Q70,40 50,30 T0,30 v20 h100Z" stroke-width="0" />
                    </g>
                </svg>
            </symbol>
        </svg>

        <div class="svg_wrapper">

            <svg class="w-100 svg-wave" width="50" height="50">
                <use xlink:href="#wave" />
            </svg>

            <div class="div-svg row rtl text-right m-0 text-white h-auto px-3">

                <div class="col-lg-1"></div>

                <div class="col-lg-2">

                    <!-- <h4 class="web-color bold"> دیگر صفحات </h4> -->
                    <h4 class="web-color bold"> نماد ساماندهی </h4>

                    <div class="other-pages d-flex justify-content-around mb-xs-4 mt-xs-3" :class="{ 'flex-column' : !Res }">

                        <img id = 'jxlzfukzjxlzrgvjoeukjxlz' style = 'cursor:pointer' onclick = 'window.open("https://logo.samandehi.ir/Verify.aspx?id=161381&p=rfthgvkarfthxlaomcsirfth", "Popup","toolbar=no, scrollbars=no, location=no, statusbar=no, menubar=no, resizable=0, width=450, height=630, top=30")' alt = 'logo-samandehi' src = 'https://logo.samandehi.ir/logo.aspx?id=161381&p=nbpdwlbqnbpdqftiaqgwnbpd' />
                        <!-- <router-link to="/properties">
                            <span> خرید </span>
                        </router-link>

                        <router-link to="/properties">
                            <span> رهن و اجاره </span>
                        </router-link>

                        <router-link to="/blog">
                            <span> بلاگ </span>
                        </router-link> -->
                    </div>

                </div>

                <div class="col-lg-3" :class="{ 'mt-4' : Res }">
                    <h4 class="web-color bold"> درباره ما </h4>
                    <p v-if="siteSetting.description"> {{ siteSetting.description }} </p>
                    <p v-else>
                        «مسکن شو» سامانه هوشمندی است که در حوزه «املاک و مستغلات» و
                        «مشاغل ساختمانی» و «اشتغال زایی» وخدمات وابسته به آن، فعالیت می‌کند.
                        مسکن شو به شما امکان فروش، اجاره و خرید ملک مورد نظرتان را می‌دهد.
                    </p>
                </div>

                <div class="col-lg-1"></div>

                <div class="col-lg-3">

                    <h4 class="web-color bold"> شماره تماس </h4>
                    <p> {{ siteSetting.phone || '09158916794 - 05138696207' }} </p>

                    <h4 class="web-color bold mt-3"> آدرس </h4>
                    <p> {{ siteSetting.address || 'آدرسی ثبت نشده' }} </p>

                </div>

                <div class="col-lg-1 d-flex justify-content-around mt-xs-3 text-center"
                    :class="{ 'flex-column' : !Res }">

                    <a href="https://t.me/joinchat/CbN9akJV_W9hXHPu9KpqgQ">
                        <img class="mx-1" src="/img/social-icons/telegram.svg" height="25px" width="25px"
                            alt="linkedin">
                    </a>
                    <a href="https://instagram.com/maskanshow">
                        <img class="mx-1" src="/img/social-icons/instagram.svg" height="25px" width="25px"
                            alt="instagram">
                    </a>
                    <a href="https://twitter.com/maskanshow">
                        <img class="mx-1" src="/img/social-icons/twitter.svg" height="25px" width="25px" alt="facebook">
                    </a>
                    <a href="https://facebook.com/maskanshow">
                        <img class="mx-1" src="/img/social-icons/facebook.svg" height="25px" width="25px"
                            alt="facebook">
                    </a>

                </div>

                <div class="col-lg-1"></div>

                <div class="col-12 row mx-0 rtl pt-4">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-5 text-center text-lg-right pr-0">
                        © 2017 - 2019
                        <p class="d-inline-block mr-3 fs-12">
                            طراحی و توسعه توسط     
                            <a class="web-color fs-13 mr-1" href="https://www.smaat.ir">
                                SmaaT Co
                            </a>
                        </p>
                    </div>
                    <div class="col-lg-5 d-flex text-center mt-xs-n2 text-lg-left mb-xs-4">
                        <a class="fs-12 web-color" href="tel:+989105009868">
                            <i class="fas fa-laptop fs-15 ml-2"></i>
                            تماس با تیم توسعه دهنده
                        </a>
                        <a class="fs-12 web-color" :href="'tel:'+siteSetting.phone">
                            <i class="fas fa-life-ring fs-15 ml-2"></i>
                            تماس با پشتیبانی
                        </a>
                    </div>
                    <div class="col-lg-1"></div>
                </div>

            </div>

        </div>

    </footer>
</template>

<script>
    import mixin from '../../mixin';
    import {
        mapState
    } from 'vuex';

    export default {
        mixins: [mixin],
        computed: {
            ...mapState([
                'siteSetting'
            ])
        }
    }

</script>

<style>

    .btm {
        bottom: 230px;
    }

    @media (max-width: 700px) {
        .btm {
            bottom: 450px;
        }
    }

    .path-footer {
        fill: #484848;
        stroke-linejoin: round;
        stroke: #ffffff
    }

    .svg_wrapper {
        width: 100%;
    }

    .svg_wrapper svg {
        margin-bottom: -1px;
    }

    .svg-wave {
        height: 250px;
        transform: scaleX(-1);
    }

    .div-svg {
        width: 100%;
        min-height: 220px;
        background: #484848;
    }

    .social-footer a {
        height: 50px;
        width: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;

    }

    .other-pages a {
        color: #fff;
        margin-top: 10px;
    }

</style>
