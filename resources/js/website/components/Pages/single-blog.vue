<template>
    <div>
        <!--================Blog Area =================-->
        <section class="blog_area single-post-area section_gap pt-5">
            <div class="container">
                <div class="row">

                    <div class="col-lg-8 posts-list">

                        <!-- وبلاگ -->
                        <div class="single-post bg-white py-3 border-radius">
                            
                            <div class="col-lg-12">
                                <div class="feature-img">
                                    <v-img
                                        :src=" is_exist(single_article.image) && single_article.image.large ? url + single_article.image.large : '/img/none.png' "
                                        :min-height="350"
                                        :max-height="350"
                                        max-width="auto"
                                        aspect-ratio="1"
                                        cover
                                    />
                                </div>
                            </div>

                            <div class="col-lg-12 blog_details as-align-right">

                                <h3 class="bold text-right title-article"> {{ single_article.title }} </h3>

                                <!-- Article Informations -->
                                <div class="pb-3 pt-4">
                                    <div class="blog_info w-100">
                                        <ul class="blog_meta list rtl">
                                            <li>
                                                <span> {{ single_article.writer.full_name }} </span>
                                                <i class="flaticon-profile"></i>
                                            </li>
                                            <li>
                                                <span class="rtl"> {{ single_article.created_at | ago }} </span>
                                                <i class="flaticon-calendar"></i>
                                            </li>
                                            <li v-show="!Res">
                                                <span> {{ single_article.reading_time }} دقیقه </span>
                                                <i class="flaticon-clock"></i>
                                            </li>
                                            <li v-if="!Res && single_article.comments ">
                                                <span> {{ single_article.comments.length }} دیدگاه </span>
                                                <i class="lnr lnr-bubble"></i>
                                            </li>
                                        </ul>
                                        <ul class="blog_meta list rtl" v-show="Res">
                                            <li>
                                                <span> {{ single_article.reading_time }} دقیقه </span>
                                                <i class="flaticon-clock"></i>
                                            </li>
                                            <li>
                                                <span> {{ single_article.comments.length }} دیدگاه </span>
                                                <i class="lnr lnr-bubble"></i>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="v-html text-justify rtl line-height p-color border-top p-3" v-html="single_article.body"></div>

                            </div>

                        </div>

                        <!-- نظرات -->
                        <div class="tab-pane text-right bg-white border-radius mt-3 mb-5 p-5" id="review">
                            <div class="row">
                            
                                <div class="col-12">
                                    <div class="review_box">
                                        
                                        <h4>
                                            نظرات
                                            <img class="ml-1" src="/img/help.svg" height="30px" width="30px">
                                        </h4>
                                        
                                        <div class="w-100 mt-3" id="contactForm">

                                            <v-text-field
                                                v-model="new_comment.title"
                                                class="fs-12"
                                                label="عنوان را وارد کنید"
                                                outline
                                                name="input-7-4"
                                                reverse
                                                counter
                                                :maxlength="max_title"
                                                :rules="[rules.required,rules.title]"
                                            ></v-text-field>

                                            <v-textarea
                                                v-model="new_comment.content"
                                                class="fs-12"
                                                outline
                                                name="input-7-4"
                                                label="نظر خود را مطرح نمایید"
                                                reverse
                                                counter
                                                :maxlength="max_comment"
                                                :rules="[rules.required,rules.comment]"
                                            ></v-textarea>

                                        </div>

                                        <div class="w-100 text-right">
                                            <v-btn
                                                @click="new_cm(false)"
                                                class="text-white mr-0"
                                                :color="web_color"
                                                :disabled="!new_comment.title || !new_comment.content"
                                                round
                                                large>
                                                {{ Auth ? 'ثبت نظر' : 'ابتدا وارد شوید' }}
                                            </v-btn>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-12 mt-4" v-if="is_exist(single_article.comments)">
                                    <div class="comment_list">
                                        <div class="mb-4" v-for="Qa in single_article.comments" :key="Qa.id">

                                            <!-- Question -->
                                            <div class="review_list">
                                                <div class="review_item row rtl p-4 my-2 web-bg-ultra-fade border-radius">
                                                    
                                                    <div class="col-md-3">
                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                            
                                                            <vs-avatar size="70px"
                                                                :src=" Qa.writer.avatar ? url + Qa.writer.avatar.small : '/img/user.png' "/>
                                                        
                                                            <span class="text-color my-2">
                                                                {{ !!Qa.writer.full_name && Qa.writer.full_name !=" " ? Qa.writer.full_name : 'کاربر مهمان' }}
                                                            </span>

                                                            <vs-tooltip class="fs-12 text-center" :text=" Qa.created_at | created " v-if="is_exist(Qa.created_at)">
                                                                <span class="text-center fs-12 mr-2"> {{ Qa.created_at | ago }} </span>                                            
                                                            </vs-tooltip>

                                                            <v-btn class="open-answer" outline round :color="web_color_dark" small>
                                                                <span class="fs-12" @click="open_dialpg_cm(Qa.id)"> پاسخ دهید </span>
                                                            </v-btn>       
                                                            
                                                        </div>
                                                    </div>

                                                    <div class="col-md-9 mt-xs-3"
                                                        :class="{ ' border-right' : !Res }">

                                                        <div class="d-flex justify-content-between mb-2">
                                                            <h6 class="bold"> {{ Qa.title }} </h6>
                                                            <el-button v-if="!Qa.is_accept" type="danger" size="mini" plain> <span class="label"> تایید نشده </span> </el-button>
                                                        </div>

                                                        <p class="rtl text-justify m-0"> {{ Qa.message }} </p>

                                                    </div>

                                                </div>
                                            </div>

                                            <!-- Reply -->
                                            <div class="review_item reply row rtl p-4 mr-5 web-bg-ultra-fade border-radius"
                                                v-for="Answer in Qa.answers" :key="Answer.id">

                                                    <div class="col-md-3">
                                                        <div class="d-flex text-center flex-column justify-content-center align-items-center">
                                                            
                                                            <vs-avatar size="70px"
                                                                :src=" Answer.writer.avatar ? url + Answer.writer.avatar.small : '/img/user.png' "/>
                                                        
                                                            <span class="text-color my-2">
                                                                {{ !!Answer.writer.full_name && Answer.writer.full_name !=" " ? Answer.writer.full_name : 'کاربر مهمان' }}
                                                            </span>

                                                            <vs-tooltip class="fs-12 text-center" :text=" Answer.created_at | created " v-if="is_exist(Answer.created_at)">
                                                                <span class="text-center fs-12 mr-2"> {{ Answer.created_at | ago }} </span>                                            
                                                            </vs-tooltip>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-9 mt-xs-3"
                                                        :class="{ ' border-right' : !Res }">

                                                        <div class="d-flex justify-content-between mb-2">
                                                            <h6 class="bold"> {{ Answer.title }} </h6>
                                                            <el-button v-if="!Answer.is_accept" type="danger" size="mini" plain> <span class="label"> تایید نشده </span> </el-button>
                                                        </div>

                                                        <p class="rtl text-justify m-0"> {{ Answer.message }} </p>

                                                    </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    
                    <div class="col-lg-4">
                        <Blog-Sidebar
                            :writer="single_article.writer"
                            :subjects="single_article.subjects"
                            :tags="single_article.tags"></Blog-Sidebar>
                    </div>

                </div>
            </div>
        </section>
        <!--================Blog Area =================-->
        
        <!-- Dialog For Edit User -->
        <v-app>
            <v-layout row justify-center>
                <v-dialog v-model="dialog_cm" persistent max-width="600px">
                    <v-card class="pa-3 rtl">

                        <v-card-title>
                            <span class="head-dialog iransans"> ثبت پاسخ </span>
                        </v-card-title>

                        <v-card-text>
                            <v-form v-model="valid_form_cm">
                                <v-container grid-list-md>
                                    <div class="row rtl">
                                        <div class="col-12 mb-4 review_box text-right" id="contactForm" dir="ltr">
                                            <div>

                                                <h6 class="rtl bold">
                                                    عنوان (اجباری)
                                                </h6>

                                                <v-text-field
                                                    v-model="Answer.title"
                                                    class="fs-12"
                                                    label="عنوان را وارد کنید"
                                                    outline
                                                    name="input-7-4"
                                                    reverse
                                                    counter
                                                    :maxlength="max_title"
                                                    :rules="[rules.required,rules.title]"
                                                ></v-text-field>

                                                <h6 class="rtl mt-3 bold">
                                                    متن نظر (اجباری)
                                                </h6>

                                                <v-textarea
                                                    v-model="Answer.content"
                                                    class="fs-12 textarea-rtl"
                                                    label="نظر خود را وارد کنید"
                                                    outline
                                                    name="input-7-4"
                                                    reverse
                                                    counter
                                                    :maxlength="max_comment"
                                                    :rules="[rules.required,rules.comment]"
                                                ></v-textarea>

                                            </div>
                                        </div>
                                    </div>
                                </v-container>
                            </v-form>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn class="iransans" color="red" flat @click="dialog_cm = false"> انصراف </v-btn>
                            <v-btn class="iransans" :disabled="!valid_form_cm" color="blue" flat @click="new_cm(id_cm)"> ثبت </v-btn>
                        </v-card-actions>

                    </v-card>
                </v-dialog>
            </v-layout>
        </v-app>
    </div>
</template>

<script>
    import {
        VImg ,
        VTextField ,
        VTextarea ,
        VBtn ,
        VDialog ,
        VLayout ,
        VCard ,
        VForm ,
        VContainer ,
        VCardTitle ,
        VCardText ,
        VCardActions ,
        VSpacer
    } from 'vuetify/lib';
    import { Button } from 'element-ui';
    import { mapState , mapMutations } from 'vuex';
    import mixin from '../../mixin';
    import moment from '../../moment';
    import BlogSidebar from './Blog/Blog_Sidebar.vue';

    export default {

        mixins : [mixin,moment] ,

        metaInfo() {
            return {
                title : this.single_article.title ,
            }
        } ,

        components: {
            BlogSidebar ,
            elButton: Button ,
            VImg ,
            VTextField ,
            VTextarea ,
            VBtn ,
            VDialog ,
            VLayout ,
            VCard ,
            VForm ,
            VContainer ,
            VCardTitle ,
            VCardText ,
            VCardActions ,
            VSpacer
        } ,

        created() {
            this.Req();
            this.reply_cm();
        } ,

        data() {
            return {
                alert : {
                    new : { sc : false , err : false } ,
                    reply : { sc : false , err : false }
                } ,
                Answer : {
                    title : '' ,
                    content : ''
                } ,
                new_comment : {
                    title : '' ,
                    content : ''
                } ,
                dialog_cm : false ,
                valid_form_cm : false ,

                id_cm : '' ,

                max_title : 100 ,
                max_comment : 2000 ,

                rules: {
                    required: value => !!value || '.این فیلد نمیتواند خالی باشد' ,
                    title: value => value.length <= this.max_title || `حداکثر ${this.max_title} .کاراکتر مجاز میباشد` ,
                    comment: value => value.length <= this.max_comment || `حداکثر ${this.max_comment} .کاراکتر مجاز میباشد` ,
                }

            }
        } ,

        computed : {
            ...mapState([
                'single_article' ,
                'Auth' ,
                'req_url' ,
                'url'
            ])
        } ,

        methods : {

            ...mapMutations([
                'Req_data'
            ]) ,

            wait_cm() {
                return new Promise(resolve => {
                    let Interval = setInterval(() => {
                        if (!!this.single_article.comments.length && !!this.single_article.comments[0].id) {
                            resolve(true);
                            clearInterval(Interval);
                        }
                    }, 100);
                })
            } ,

            async reply_cm() {
                
                await this.wait_cm()

                $(document).ready(function () {

                    $('.answer').hide();

                    $('.open-answer').click(function() {
                        $(this).parents().eq(3).find('.answer').show();
                    })

                    $('.close-answer').click(function() {
                        $(this).parents().eq(3).find('.answer').hide();
                    })

                });

            } ,

            Req() {

                let query = 
                    `{
                        article( slug : "${this.$route.params.slug}") {
                            id
                            slug
                            title
                            description
                            body
                            image {
                                id
                                file_name
                                large
                            }
                            reading_time
                            created_at
                            subjects {
                                id
                                title
                            }
                            tags {
                                name
                            }
                            writer {
                                id
                                first_name
                                last_name
                                full_name
                                avatar {
                                    id
                                    file_name
                                    small
                                }
                            }
                            comments {
                                id
                                title
                                message
                                is_accept
                                created_at
                                writer {
                                    id
                                    first_name
                                    last_name
                                    full_name
                                    avatar {
                                        id
                                        file_name
                                        small
                                    }
                                }
                                answers {
                                    id
                                    title
                                    message
                                    is_accept
                                    created_at
                                    writer {
                                        id
                                        first_name
                                        last_name
                                        full_name
                                        avatar {
                                            id
                                            file_name
                                            small
                                        }
                                    }
                                }
                            }
                        }
                    }
                `

                this.Req_data({
                    query : query ,
                    props : ['article'] ,
                    states : ['single_article']
                })

            } ,

            open_dialpg_cm(id) {
                if( !this.Auth ) {
                    this.$store.state.login_modal = true;
                    return;
                } else if( this.id_cm === id ) {
                    this.dialog_cm = true;
                } else {
                    this.Answer.title = '';
                    this.Answer.content = '';
                    this.id_cm = id;
                    this.dialog_cm = true;
                }
            } ,

            new_cm( reply = false ) {
                if( !this.Auth ) {
                    this.$store.state.login_modal = true;
                    return
                } else if(
                    !!reply
                    ? !this.Answer.title && !!this.Answer.content
                    : !this.new_comment.title && !this.new_comment.content
                ) {
                    this.notif( 'فیلد های مورد پر کنید' , 'warning' , 'error' );
                    return;
                } else {

                    let query = `
                        mutation {
                            createComment(
                                ${ !(!!reply) ? 'article_id : '+ `"${this.single_article.id}"` : '' }
                                title : "${ !!reply ? this.Answer.title : this.new_comment.title }" ,
                                message : "${ !!reply ? this.Answer.content : this.new_comment.content }" ,
                                ${ !!reply ? 'parent_id : '+ reply : '' }
                            ) {
                                id
                                title
                                message
                                created_at
                            }
                        }                    
                    `
                    this.$store.state.loading = true
                    
                    axios({
                        method : 'POST' ,
                        url : this.req_url ,
                        data : {
                            query : query
                        } ,
                    })
                    .then( ({data}) => {
                        if(this.is_exist(data.data.createComment)) {
                            this.$store.state.loading = false;
                            this.dialog_cm = false;
                            this.notif( 'ثبت نظر با موفقیت انجام شد' , 'success' , 'comment' )
                        }
                    })
                    .catch( Err => {
                        if( Err.response && Err.response.status === 401 ) {
                            window.localStorage.removeItem('JWT');
                            location.reload();
                        } else {
                            console.log(Err);
                        }
                    }) 

                }
            } ,

        }

    }
</script>

<style scoped>

    .blog_meta li {
        display: flex;
        direction: ltr;
    }

    ul.blog_meta {
        display: flex;
        justify-content: space-evenly;
    }

    .blog_meta li i {
        font-size: 18px;
        font-weight: bold;
    }

    .blog_meta li span {
        font-size: 12px;
        margin-right: 10px
    }

    .material-icons {
        font-size: 20px !important;
        padding-bottom: 3px;
        padding-left: 1px;
    }
    
</style>

<style>

    .blog_area textarea , .textarea-rtl textarea {
        text-align: right;
    }

    .margin-minus {
        margin-left: -15px;
        margin-right: -15px;
    }

    .title-article {
        line-height: 45px !important;
        font-size: 20px !important;
    }

    .p-color {
        color: #5b5b5b;
    }

    .line-height {
        line-height: 30px;
        letter-spacing: 0.5px;
    }

</style>
