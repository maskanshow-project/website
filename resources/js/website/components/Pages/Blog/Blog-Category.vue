<template>
    <div>

        <!--================Blog Categorie Area =================-->
        <section class="blog_categorie_area">
            <div class="container" v-if="categories.length">
                <div class="row">
                    <div class="col-lg-4" v-for="ctg in categories.slice(0,3)" :key="ctg.id">
                        <router-link :to=" '/articles/' + ctg.id ">
                            <div class="categories_post">
                                <img :src=" $store.state.url + ctg.logo.medium " alt="post">
                                <div class="categories_details">
                                    <div class="categories_text">
                                        <h6 class="text-white"> {{ ctg.title || 'ندارد' }} </h6>
                                        <div class="border_line"></div>
                                        <p class="fs-12 rtl"> {{ ctg.description | truncate(20) }} </p>
                                        <p class="fs-12 rtl" v-if="!(!!ctg.description)"> بدون توضیح </p>
                                    </div>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </section>
        <!--================Blog Categorie Area =================-->
        
    </div>
</template>

<script>
    export default {
     
        computed : {
            categories() {
                if(this.$store.state.subjects.filter( el => !!el.logo ).length != 0) {
                    $(document).ready(function () {
                        $('.categories_post').tilt({
                            maxTilt: 5 ,
                        })
                    });
                }
                return this.$store.state.subjects.filter( el => !!el.logo )
            } ,
        }

    }
</script>

<style>

    .blog_post {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 12px 0 rgba(0,0,0,.1);
    }
    
    .categories_post {
        transform-style: preserve-3d;
    }

    .categories_post:hover .categories_details {
        transform: translateZ(95px) scale(0.65);
    }

</style>