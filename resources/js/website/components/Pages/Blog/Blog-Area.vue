<template>
    <div>
        <section class="blog_area">
            <div class="container">
                <div class="row">
                    <!-- Articles -->
                    <div class="col-lg-8">

                        <div class="blog_left_sidebar mb-5" v-if="is_exist(articles)">
                            <article class="row blog_item" v-for="(article,index) in articles" :key="index">

                                <!-- Article Informations -->
                                <div class="col-md-3" v-if="!Res && is_exist(article.writer)">
                                    <div class="blog_info text-right pt-5">
                                        <ul class="blog_meta list">
                                            <li>
                                                <span> {{ article.writer.full_name }} </span>
                                                <i class="flaticon-profile"></i>
                                            </li>
                                            <li>
                                                <span class="rtl"> {{ article.created_at | ago }} </span>
                                                <i class="flaticon-calendar"></i>
                                            </li>
                                            <li>
                                                <span> {{ article.reading_time }} دقیقه </span>
                                                <i class="flaticon-clock"></i>
                                            </li>
                                            <li v-if="is_exist(article.comments)">
                                                <span> {{ article.comments.length }} دیدگاه </span>
                                                <i class="lnr lnr-bubble"></i>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <!-- Article Area -->
                                <div class="col-md-9">
                                    <div class="blog_post">

                                        <router-link :to="`/article/${article.slug}`">
                                            <v-img
                                                style="margin: -35px -5px 0px;"
                                                :src=" is_exist(article.image) && article.image.medium ? url + article.image.medium : '/img/none.png' "
                                                :min-height="300"
                                                :max-height="300"
                                                max-width="auto"
                                                aspect-ratio="1"
                                                cover
                                            />
                                        </router-link>
                                        
                                        <div class="blog_details">
                                            
                                            <router-link :to="`/article/${article.slug}`">
                                                <h4 class="bold" :class="{ 'text-right' : !Res , 'text-center' : Res }"> {{ article.title }} </h4>
                                            </router-link>
                                            
                                            <div class="w-100" v-if="Res && is_exist(article.writer)">
                                                <div class="blog_info text-right pt-3">
                                                    <ul class="blog_meta list rtl d-flex justify-content-around">
                                                        <li>
                                                            <span> {{ article.writer.full_name }} </span>
                                                            <i class="flaticon-profile"></i>
                                                        </li>
                                                        <li>
                                                            <span class="rtl"> {{ article.created_at | ago }} </span>
                                                            <i class="flaticon-calendar"></i>
                                                        </li>
                                                    </ul>
                                                    <ul class="blog_meta list rtl d-flex justify-content-around">
                                                        <li>
                                                            <span> {{ article.reading_time }} دقیقه </span>
                                                            <i class="flaticon-clock"></i>
                                                        </li>
                                                        <li v-if="is_exist(article.comments)">
                                                            <span> {{ article.comments.length }} دیدگاه </span>
                                                            <i class="lnr lnr-bubble"></i>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <p class="fs-13 pt-2 text-justify rtl p-article"> {{ article.description | truncate(200) }} </p>

                                            <router-link :to="`/article/${article.slug}`">
                                                <v-btn class="fs-13" outline round :color="web_color"> بیشتر </v-btn>
                                            </router-link>

                                        </div>

                                    </div>
                                </div>

                            </article>
                            <vs-pagination class="py-5" :color="web_color" :total="total" v-model="page"></vs-pagination>
                        </div>

                        <div v-else>
                            <el-card>
                                <div class="row pa-5">

                                    <div class="col-md-12 my-5 text-center">
                                        <i class="flaticon-search bold fa-9x" style="color:#777777"></i>
                                    </div>
                                    
                                    <div class="w-100"></div>

                                    <div class="col-md-12 text-center">
                                        <h2 class="iransans" style="color:#959595;"> !هیچ نتیجه ای یافت نشد </h2>
                                    </div>

                                </div>
                            </el-card>
                        </div>

                    </div>

                    <!-- SideBar -->
                    <div class="col-lg-4">
                        <Blog-Sidebar :Req="Req"></Blog-Sidebar>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import {
        VImg ,
        VBtn
    } from 'vuetify/lib';
    import { Card } from 'element-ui';
    import { mapState , mapMutations } from 'vuex';
    import mixin from '../../../mixin';
    import moment from '../../../moment';
    import BlogSidebar from './Blog_Sidebar.vue';

    export default {
        mixins : [mixin,moment] ,

        metaInfo() {
            return {
                title : 'وبلاگ ها' ,
            }
        } ,

        components: {
            BlogSidebar ,
            elCard: Card ,
            VImg ,
            VBtn
        } ,
    
        created() {
            this.Req()
        } ,

        data() {
            return {
                page : 1
            }
        } ,

        watch : {

            page() {
                this.Req()
            } ,

            '$route.params.category'() {
                this.Req()
            }
        } ,

        computed : {
            
            ...mapState([
                'articles' ,
                'pagination' ,
                'url'
            ]) ,

            total() {
                return Math.ceil(this.pagination.total/4)
            }

        } ,
        
        methods : {

            ...mapMutations([
                'Req_data'
            ]) ,

            Req(serach = '') {

                const query = 
                    `{
                        articles(
                                per_page: 4 ,
                                page : ${this.page} ,
                                query : "${serach}" ,
                                ${ !!this.$route.params.category ? 'subjects :' + `[${this.$route.params.category}]` : '' } ) {
                            data {
                                id
                                title
                                description
                                slug
                                image {
                                    id
                                    file_name
                                    medium
                                }
                                writer {
                                    id
                                    first_name
                                    last_name
                                    full_name
                                }
                                created_at
                                reading_time
                                comments {
                                    id
                                }
                            }
                            total
                        }

                        subjects {
                            data {
                                id
                                title
                                description
                                logo {
                                    id
                                    file_name
                                    medium
                                }
                            }
                        }


                    }
                `
                
                this.Req_data({
                    query : query ,
                    props : ['articles' , 'subjects'] ,
                    states : ['articles' , 'subjects']
                })

            }

        }
    }
</script>

<style scoped>

    .blog_meta li {
        display: flex;
        justify-content: flex-end;
        padding-bottom: 10px;
        direction: ltr;
    }

    .blog_meta li i {
        font-size: 18px;
        font-weight: bold;
    }

    .blog_meta li span {
        font-size: 12px;
        margin-right: 10px
    }

    .p-article {
        max-height: 100px;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }

</style>

<style>

    .blog_post .v-responsive {
        box-shadow: 0px 0px 10px -3px #000;
        border-radius: 10px;
    }

    .pointer {
        cursor: pointer;
    }

    .fs-10 {
        font-size: 10px;
    }

    .fs-13 {
        font-size: 13px;
    }

    .v-image__image {
        border-radius: 7px;
    }

    .v-chip .v-chip__content {
        cursor: pointer !important;
    }

    .theme--light.v-chip {
        border-radius: 7px;
    }

</style>