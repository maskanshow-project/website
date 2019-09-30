<template>

    <div class="blog_right_sidebar">
        
        <!-- Search Box -->
        <aside class="single_sidebar_widget search_widget rtl" v-if="is_exist(!writer)">
            <div class="input-group">
                <input v-model="search" @keyup.enter="Req(search)"
                    type="text" class="form-control web-bg" 
                    placeholder="جستجو در مقالات ..." 
                    onfocus="this.placeholder = ''" 
                    onblur="this.placeholder = 'جستجو در مقالات ...'">
                
                <span class="input-group-btn">
                    <button @click="Req(search)" class="btn btn-default" type="button">
                        <i class="lnr lnr-magnifier"></i>
                    </button>
                </span>
            </div>
            <div class="br"></div>
        </aside>

        <!-- Profile Writer -->
        <aside class="single_sidebar_widget author_widget" v-if="is_exist(writer)">

            <v-avatar
            size="130"
            color="grey lighten-4">
                <img :style="{ border : ` 4px solid ${web_color}` }" :src=" writer.avatar ? url + writer.avatar.small : '/img/user.png' " alt="avatar">
            </v-avatar>

            <h4 class="bold"> {{ writer.full_name }} </h4>
            <p class="text-center"> نویسنده </p>
            <div class="social_icon" v-show="false">
                <a href="#">
                    <img class="mx-1" src="/template/karma/img/blog/twitter.svg" height="25px" width="25px" alt="facebook">
                </a>
                <a href="#">
                    <img class="mx-1" src="/template/karma/img/blog/linkedin.svg" height="25px" width="25px" alt="linkedin">
                </a>
                <a href="#">
                    <img class="mx-1" src="/template/karma/img/blog/instagram.svg" height="25px" width="25px" alt="instagram">
                </a>
                <a href="#">
                    <img class="mx-1" src="/template/karma/img/blog/facebook.svg" height="25px" width="25px" alt="facebook">
                </a>
            </div>
            <div class="br"></div>
        </aside>

        <!-- Popular Post -->
        <aside v-if="false" class="single_sidebar_widget popular_post_widget as-align-right">
            <h3 class="widget_title web-bg"> محبوب ترین مقالات </h3>
            <div class="media post_item text-right" v-for="(article,index) in articles" :key="index">
                
                <div class="media-body">
                    <a href="blog-details.html">
                        <h6> {{ article.title }} </h6>
                    </a>
                    <p> {{ article.created_at }} </p>
                </div>
                
                <v-img
                    src="/template/karma/img/none.png"
                    :min-height="60"
                    :max-height="60"
                    :man-width="100"
                    :max-width="100"
                    aspect-ratio="1"
                    cover
                />

            </div>
            <div class="br"></div>
        </aside>

        <!-- Categories -->
        <aside v-if=" is_exist(subjects) ? true : is_exist(all_subjects) " class="single_sidebar_widget post_category_widget">
            <h4 class="widget_title web-bg"> دسته‌ بندی ها </h4>
            <ul class="list cat-list text-right">
                <li class="pointer" v-for="(sub,index) in ( is_exist(subjects) ? subjects : all_subjects )" :key="index">
                    <router-link :to=" '/articles/' + sub.id ">
                        <div>
                            <p class="fs-13"> {{ sub.title }} </p>
                        </div>
                    </router-link>
                </li>
            </ul>
            <div class="br" v-if="is_exist(tags)"></div>
        </aside>

        <!-- Tags -->
        <aside v-if="is_exist(tags)" class="single-sidebar-widget tag_cloud_widget">
            <h4 class="widget_title web-bg"> برچسب ها </h4>
            <ul class="list text-right">
                <li v-for="(tag,index) in tags" :key="index">
                    <v-chip class="fs-10" label :color="web_color_dark" text-color="white">
                        <v-icon left :style="{ fontSize : '20px !important' }">label</v-icon> {{ tag.name }}
                    </v-chip>
                </li>
            </ul>
        </aside>

    </div>

</template>

<script>
    import {
        VImg ,
        VAvatar ,
        VChip ,
        VIcon
    } from 'vuetify/lib';
    import { mapState } from 'vuex';
    import mixin from '../../../mixin';

    export default {
        props : [ 'Req' , 'writer' , 'subjects' , 'tags' ] ,
        mixins : [mixin] ,

        components: {
            VImg ,
            VAvatar ,
            VChip ,
            VIcon
        } ,

        data() {
            return {
                search : '' ,
            }
        } ,

        computed: {
            ...mapState({
                all_subjects : 'subjects' ,
                url : 'url'
            })
        }
    }
</script>