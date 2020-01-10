<template>
  <div>
    <transition name="fade" mode="in-out">
      <div class="as-loading" v-show="loading">
        <radar-spinner :animation-duration="2000" :size="130" :color="web_color" />
      </div>
    </transition>

    <Header />

    <transition name="fade" mode="in-out">
      <router-view></router-view>
    </transition>

    <Footer />

    <vs-popup class="login-modal" title="ورود" :active.sync="$store.state.login_modal">
      <v-form v-model="valid.login">
        <div class="register rtl p-4 pb-2">
          <div class="row">
            <h6 class="col-12 rtl bold required">نام کاربری</h6>
            <v-text-field
              @keyup.native.enter="user_login(false)"
              class="small-text-field col-12"
              v-model="login.email"
              label="نام کاربری خود را وارد کنید"
              reverse
              outline
              single-line
              :rules="[rules.required,rules.username_regex]"
            ></v-text-field>
          </div>

          <div>
            <div class="row">
              <h6 class="rtl bold required col-12">
                رمز عبور
                <!-- <span
                  class="forget-pass-text"
                  @click="change_modal( 'login_modal' , 'access_modal' )"
                >ورود با کد فعالسازی</span>-->
              </h6>
              <v-text-field
                @keyup.native.enter="user_login(false)"
                class="small-text-field col-8"
                v-model="login.password.value"
                label="رمز عبور را وارد کنید"
                reverse
                outline
                single-line
                :prepend-inner-icon="login.password.show ? 'visibility' : 'visibility_off'"
                :type="login.password.show ? 'text' : 'password'"
                @click:prepend-inner="login.password.show = !login.password.show"
                :rules="[rules.required]"
              ></v-text-field>

              <v-btn
                class="text-white col-4 mr-2 mt-0"
                :style="{ height: '44px' }"
                :color="web_color"
                :loading="login.loading"
                block
                @click="user_login(false)"
              >ورود</v-btn>
            </div>
          </div>

          <div class="row">
            <h6 class="rtl bold required col-12">کد فعالسازی</h6>
            <v-text-field
              @keyup.native.enter="user_login(true)"
              class="small-text-field access_code col-8"
              v-model="login.access_code"
              v-mask="mask"
              label="کد فعالسازی را وارد کنید"
              reverse
              outline
              single-line
              :rules="[rules.required]"
            ></v-text-field>

            <v-btn
              class="text-white col-4 mr-2 mt-0"
              :style="{ height: '44px' }"
              color="error"
              :loading="login.loading"
              block
              @click="user_login(true)"
            >ورود</v-btn>
          </div>

          <div class="text-center mt-3">
            <span class="fs-12">کاربر جدید هستید؟</span>
            <el-link
              class="fs-12 mr-1"
              type="primary"
              @click="change_modal( 'login_modal' , 'register_modal' )"
            >ثبت نام</el-link>
          </div>

          <p class="text-center fs-10 mt-2 mb-0">
            رمز عبور را فراموش کرده اید؟
            <a
              class="tel-text"
              :href=" 'tel:'+$store.state.siteSetting.phone "
            >با پشتیبانی تماس بگیرید</a>
          </p>
        </div>
      </v-form>
    </vs-popup>

    <vs-popup
      class="login-modal"
      title="ورود با کد فعالسازی"
      :active.sync="$store.state.access_modal"
    >
      <v-form v-model="valid.access_code">
        <div class="register rtl p-4 pb-2">
          <p class="text-center">برای دریافت کد فعالسازی لطفا با پشتیبانی تماس بگیرید</p>

          <div>
            <h6 class="rtl bold required">نام کاربری</h6>
            <v-text-field
              @keyup.native.enter="user_login(true)"
              class="small-text-field"
              v-model="login.email"
              label="نام کاربری خود را وارد کنید"
              reverse
              outline
              single-line
              :rules="[rules.required,rules.username_regex]"
            ></v-text-field>
          </div>

          <div>
            <h6 class="rtl bold required">کد فعالسازی</h6>
            <v-text-field
              @keyup.native.enter="user_login(true)"
              class="small-text-field access_code"
              v-model="login.access_code"
              v-mask="mask"
              label="کد فعالسازی را وارد کنید"
              reverse
              outline
              single-line
              :rules="[rules.required]"
            ></v-text-field>
          </div>

          <v-btn
            class="text-white"
            :color="web_color"
            :disabled=" !valid.access_code || login.loading "
            :loading="login.loading"
            block
            round
            @click="user_login(true)"
          >ورود</v-btn>
        </div>
      </v-form>
    </vs-popup>

    <vs-popup
      class="login-modal"
      title="بازیابی رمز عبور"
      :active.sync="$store.state.reset_pass_modal && false"
    >
      <v-form v-model="valid.reset">
        <div class="register rtl p-4 pb-2">
          <p>
            در صورتی که رمز عبورتان را فراموش کرده اید با داشتن ایمیلی که هنگام
            ثبت نام وارد کرده اید میتوانید رمزتان را بازیابی کنید.
          </p>

          <div>
            <h6 class="rtl bold required">پست الکترونیک</h6>
            <v-text-field
              class="small-text-field"
              v-model="reset_pass.email"
              label="ایمیل خود را وارد کنید"
              reverse
              outline
              single-line
              :rules="[rules.required,rules.email]"
            ></v-text-field>
          </div>

          <v-btn
            class="text-white"
            :color="web_color"
            :disabled=" !valid.reset || reset_pass.loading "
            :loading="reset_pass.loading"
            block
            round
            @click="reset_password"
          >
            <v-icon class="ml-2">vpn_key</v-icon>بازیابی کلمه عبور
          </v-btn>
        </div>
      </v-form>
    </vs-popup>

    <vs-popup
      class="login-modal"
      title="تغییر رمز عبور"
      :active.sync="$store.state.change_pass_modal && false"
    >
      <v-form v-model="valid.change">
        <div class="register rtl p-4 pb-2">
          <div>
            <h6 class="rtl bold required">رمز عبور</h6>
            <v-text-field
              class="small-text-field"
              v-model="change_pass.password.value"
              label="رمز عبور را وارد کنید"
              reverse
              outline
              single-line
              :prepend-inner-icon="change_pass.password.show ? 'visibility' : 'visibility_off'"
              :type="change_pass.password.show ? 'text' : 'password'"
              @click:prepend-inner="change_pass.password.show = !change_pass.password.show"
              :rules="[rules.required]"
            ></v-text-field>
          </div>

          <div>
            <h6 class="rtl bold required">تکرار رمز عبور</h6>
            <v-text-field
              class="small-text-field"
              v-model="change_pass.confirm_password.value"
              label="رمز عبور خود را تکرار کنید"
              reverse
              outline
              single-line
              type="password"
              :rules="[rules.required,match_reset_pass]"
            ></v-text-field>
          </div>

          <v-btn
            class="text-white"
            :color="web_color"
            :disabled=" !valid.change || change_pass.loading "
            :loading="change_pass.loading"
            block
            round
            @click="change_password"
          >
            <v-icon class="ml-2">vpn_key</v-icon>تغییر کلمه عبور
          </v-btn>
        </div>
      </v-form>
    </vs-popup>

    <vs-popup class="register-modal" title="ثبت نام" :active.sync="$store.state.register_modal">
      <v-stepper v-model="stepper">
        <v-stepper-header class="rtl">
          <v-stepper-step
            :complete="valid.steps.step_1"
            step="1"
            :color=" valid.steps.step_1 ? '#00E676' : web_color "
          >مشخصات فردی</v-stepper-step>

          <transition name="fade" mode="out-in">
            <v-divider v-show="is_consultant"></v-divider>
          </transition>

          <transition name="fade" mode="out-in">
            <template v-if="is_consultant">
              <v-stepper-step
                :complete="valid.steps.step_2"
                step="2"
                :color=" valid.steps.step_2 ? '#00E676' : web_color "
              >مشخصات املاک</v-stepper-step>
            </template>
          </transition>
        </v-stepper-header>

        <v-stepper-items>
          <v-stepper-content step="1">
            <v-checkbox
              class="checkbox"
              :color="web_color"
              v-model="is_consultant"
              label="مشاور املاک هستم"
            ></v-checkbox>

            <v-form v-model="valid.steps.step_1">
              <div class="row rtl register">
                <div class="col-sm-6">
                  <h6 class="rtl bold required">نام کاربری</h6>
                  <v-text-field
                    class="small-text-field"
                    v-model="register.username"
                    label=" برای مثال heydari"
                    reverse
                    outline
                    single-line
                    :rules="[rules.required,rules.username,rules.username_regex]"
                  ></v-text-field>
                </div>

                <div class="col-sm-6">
                  <h6 class="rtl bold required">شماره همراه</h6>
                  <v-text-field
                    class="small-text-field"
                    v-model="register.phone_number"
                    label="شماره همراه خود را وارد کنید"
                    reverse
                    outline
                    single-line
                    type="number"
                    :rules="[rules.required,rules.phone_number]"
                  ></v-text-field>
                </div>

                <div class="col-sm-6">
                  <h6 class="rtl bold required">نام</h6>
                  <v-text-field
                    class="small-text-field"
                    v-model="register.first_name"
                    label="نام خود را وارد کنید"
                    reverse
                    outline
                    single-line
                    :rules="[rules.required]"
                  ></v-text-field>
                </div>

                <div class="col-sm-6">
                  <h6 class="rtl bold required">نام خانوادگی</h6>
                  <v-text-field
                    class="small-text-field"
                    v-model="register.last_name"
                    label="نام خانوادگی خود را وارد کنید"
                    reverse
                    outline
                    single-line
                    :rules="[rules.required]"
                  ></v-text-field>
                </div>

                <div class="col-sm-12" v-if="false">
                  <h6 class="rtl bold">پست الکترونیک</h6>
                  <v-text-field
                    class="small-text-field"
                    v-model="register.email"
                    label="ایمیل خود را وارد کنید"
                    reverse
                    outline
                    single-line
                    type="email"
                    :rules="[rules.email]"
                  ></v-text-field>
                </div>

                <div class="col-sm-6">
                  <h6 class="rtl bold required">رمز عبور</h6>
                  <v-text-field
                    class="small-text-field"
                    v-model="register.password.value"
                    label="رمز عبور را وارد کنید"
                    reverse
                    outline
                    single-line
                    :prepend-inner-icon="register.password.show ? 'visibility' : 'visibility_off'"
                    :type="register.password.show ? 'text' : 'password'"
                    @click:prepend-inner="register.password.show = !register.password.show"
                    :rules="[rules.required,rules.password]"
                  ></v-text-field>
                </div>

                <div class="col-sm-6">
                  <h6 class="rtl bold required">تکرار رمز عبور</h6>
                  <v-text-field
                    class="small-text-field"
                    v-model="register.confirm_password.value"
                    label="رمز عبور خود را تکرار کنید"
                    reverse
                    outline
                    single-line
                    type="password"
                    :rules="[rules.required,match_pass]"
                  ></v-text-field>
                </div>
              </div>

              <transition name="fade" mode="out-in">
                <v-btn
                  class="text-white"
                  color="#00E676"
                  v-if="!is_consultant"
                  :loading="register.loading"
                  :disabled="!valid.steps.step_1 || register.loading"
                  @click="user_register"
                >ثبت نام</v-btn>
                <v-btn color="info" v-else :disabled="!valid.steps.step_1" @click="stepper = 2">بعدی</v-btn>
              </transition>
            </v-form>
          </v-stepper-content>

          <v-stepper-content step="2" v-if="is_consultant">
            <v-form v-model="valid.steps.step_2">
              <div class="row rtl register">
                <div class="col-sm-6">
                  <h6 class="rtl bold required">نام املاک</h6>
                  <v-text-field
                    class="small-text-field"
                    v-model="register.estate_info.name"
                    label="نام املاک خود را وارد کنید"
                    reverse
                    outline
                    single-line
                    :rules="[rules.required]"
                  ></v-text-field>
                </div>

                <div class="col-sm-6">
                  <h6 class="rtl bold required">شماره همراه املاک</h6>
                  <v-text-field
                    class="small-text-field"
                    v-model="register.estate_info.phone_number"
                    label="شماره همراه املاک را وارد کنید"
                    reverse
                    type="number"
                    outline
                    single-line
                    :rules="[rules.required,rules.phone_number]"
                  ></v-text-field>
                </div>

                <div class="col-sm-6">
                  <h6 class="rtl bold required">نام کاربری املاک</h6>
                  <v-text-field
                    class="small-text-field"
                    v-model="register.estate_info.username"
                    label="نام کاربری املاک خود را وارد کنید"
                    :hint="`لینک صفحه شما : 
                                        www.MaskanShow.ir/<b>${register.estate_info.username}</b>`"
                    persistent-hint
                    reverse
                    outline
                    single-line
                    :rules="[rules.required,rules.username_regex]"
                  ></v-text-field>
                </div>

                <div class="col-md-6">
                  <h6 class="rtl bold required">کد معرف</h6>
                  <v-text-field
                    class="small-text-field w-100"
                    v-model="register.estate_info.reagent_code"
                    label="نام کاربری معرف خود را وارد کنید"
                    hint="کد معرف ندارید؟ با پشتیبانی تماس بگیرید"
                    persistent-hint
                    reverse
                    outline
                    single-line
                    :rules="[rules.required]"
                  ></v-text-field>
                </div>

                <div class="col-sm-12 mb-4">
                  <h6 class="rtl bold required">منطقه</h6>
                  <el-select class="w-100" v-model="register.estate_info.area" placeholder="منطقه">
                    <el-option
                      class="areas"
                      v-for="item in city_areas"
                      :key="item.id"
                      :label="`${item.name} : ${item.streets.map( el => el.name ).join(' ، ')}` | truncate(50)"
                      :value="item.id"
                    ></el-option>
                  </el-select>
                </div>

                <div class="col-md-12">
                  <h6 class="rtl bold required">آدرس</h6>
                  <v-textarea
                    v-model="register.estate_info.address"
                    label="آدرس املاک خود را وارد کنید "
                    outline
                    reverse
                    :rows="3"
                    name="input-7-4"
                    :rules="[rules.required]"
                  ></v-textarea>
                </div>

                <div class="col-md-12">
                  <h6 class="rtl bold">توضیحات</h6>
                  <v-textarea
                    v-model="register.estate_info.description"
                    label="توضیحاتی در مورد املاک خود بنویسید "
                    outline
                    reverse
                    :rows="1"
                    name="input-7-4"
                  ></v-textarea>
                </div>
              </div>

              <div class="col-md-6 ltr">
                <v-btn
                  class="text-white"
                  color="#00E676"
                  :loading="register.loading"
                  :disabled="!valid.steps.step_2 || register.loading"
                  @click="user_register"
                >ثبت نام</v-btn>
                <v-btn @click="stepper = 1">قبلی</v-btn>
              </div>
            </v-form>
          </v-stepper-content>
        </v-stepper-items>
      </v-stepper>
    </vs-popup>

    <div id="back-to-top">
      <v-btn :color="web_color" fab dark small @click="back_to_top">
        <v-icon>expand_less</v-icon>
      </v-btn>
    </div>
  </div>
</template>

<script>
import Header from "./Layout/header.vue";
import Footer from "./Layout/footer.vue";
import {
  VForm,
  VTextField,
  VTextarea,
  VBtn,
  VIcon,
  VStepper,
  VStepperStep,
  VStepperItems,
  VStepperContent,
  VStepperHeader,
  VDivider,
  VCheckbox
} from "vuetify/lib";
import { Link, Select, Option } from "element-ui";
import { mask } from "vue-the-mask";
import mixin from "../mixin";
import { mapState, mapMutations } from "vuex";

export default {
  mixins: [mixin],

  components: {
    Header,
    Footer,
    elLink: Link,
    elSelect: Select,
    elOption: Option,
    VForm,
    VTextField,
    VTextarea,
    VBtn,
    VIcon,
    VStepper,
    VStepperStep,
    VStepperItems,
    VStepperContent,
    VStepperHeader,
    VDivider,
    VCheckbox
  },

  directives: {
    mask
  },

  metaInfo() {
    return {
      titleTemplate: `%s | ${this.site_title}`
    };
  },

  created() {
    if (
      this.$route.path.search("password") != -1 &&
      this.$route.path.search("reset") != -1 &&
      this.is_exist(this.$route.params.token)
    ) {
      this.Set_state({ prop: "change_pass_modal", val: true });
    } else if (this.$route.path == "/login") {
      this.Auth
        ? this.$router.replace({ path: "/" })
        : this.Set_state({ prop: "login_modal", val: true });
    }

    var $vm = this;
    window.onscroll = function() {
      if ($(window).scrollTop() > 150) {
        $vm.btn_scroll = true;
      } else {
        $vm.btn_scroll = false;
      }
    };

    this.App();
    this.WatchLocation();
  },

  data() {
    return {
      mask: "# - # - # - # - # - #",

      site_title: "مسکن شو",

      btn_scroll: false,
      stepper: 1,

      valid: {
        login: false,
        steps: {
          step_1: false,
          step_2: false
        },
        reset: false,
        change: false,
        access_code: false
      },

      is_consultant: false,

      login: {
        email: "",
        password: {
          value: "",
          show: false
        },
        access_code: "",
        loading: false
      },

      register: {
        username: "",
        first_name: "",
        last_name: "",
        email: "",
        password: {
          show: false,
          value: ""
        },
        confirm_password: {
          show: false,
          value: ""
        },
        phone_number: "",
        estate_info: {
          name: "",
          phone_number: "",
          reagent_code: "",
          username: "",
          area: "",
          address: "",
          description: ""
        },
        loading: false
      },

      reset_pass: {
        email: "",
        loading: false
      },

      change_pass: {
        loading: false,
        password: {
          value: "",
          show: false
        },
        confirm_password: {
          value: "",
          show: false
        }
      },

      rules: {
        required: value => !!value || "این فیلد الزامی است",
        username: value =>
          value.length >= 6 || "نام کاربری حداقل باید 6 کاراکتر باشد",
        password: value =>
          value.length >= 6 || "رمز عبور حداقل باید 6 کاراکتر باشد",
        phone_number: value =>
          value.length == 11 || "شماره همراه باید 11 رقم باشد",
        username_regex: value => {
          const pattern_username = /^(?!\d)(?!.*-.*-)(?!.*-$)(?!-)[a-zA-Z0-9-]{0,20}$/;
          if (value.startsWith("-") || value.match(/^\d/)) {
            return "نام کاربری نمیتواند با عدد یا خط تیره شروع شود";
          } else {
            return (
              pattern_username.test(value) ||
              "نام کاربری شامل حروف لاتین ، عدد و خط تیره میباشد"
            );
          }
        },
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "پست الکترونیک نامعتبر است";
        }
      }
    };
  },

  computed: {
    ...mapState(["loading", "register_modal", "city_areas", "Auth", "req_url"]),

    match_pass() {
      if (this.register.password.value) {
        return v =>
          (!!v && v) === this.register.password.value ||
          "رمز عبور با تاییدیه آن مطابقت ندارد";
      } else {
        return true;
      }
    },

    match_reset_pass() {
      if (this.change_pass.password.value) {
        return v =>
          (!!v && v) === this.change_pass.password.value ||
          "رمز عبور با تاییدیه آن مطابقت ندارد";
      } else {
        return true;
      }
    }
  },

  watch: {
    btn_scroll(val) {
      val ? this.anime_btn(true) : this.anime_btn(false);
    },

    "register.username"(val) {
      this.register.estate_info.username = val;
    },

    "login.email"(val) {
      if (val && this.rules.email(val) === true) this.reset_pass.email = val;
    }
  },

  methods: {
    ...mapMutations(["Req_data", "Set_state"]),

    App() {
      this.Set_state({ prop: "loading", val: true });

      let props = ["siteSetting", "assignments", "estate_types", "offices"];

      let me_query = `
                    me {
                        id
                        username
                        first_name
                        last_name
                        full_name
                        email
                        remaining_hits_count
                        validity_end_at
                        visited_estate_count
                        allPermissions {
                            id
                            name
                        }
                        avatar {
                            id
                            file_name
                            small
                        }
                    }        
                `;

      let query = `
                    {
                        ${this.Auth ? me_query : ""}

                        siteSetting {
                            title
                            description
                            phone
                            address
                            banner_link
                            banner {
                                id
                                file_name
                                large
                            }
                            header_banner {
                                id
                                file_name
                                large
                            }
                            theme_color
                            is_enabled
                            opinions {
                                avatar {
                                    id
                                    file_name
                                    thumb
                                }
                                fullname
                                post
                                opinion
                            }
                            posters {
                                title
                                description
                                link
                                image {
                                    id
                                    file_name
                                    large
                                }
                            }
                            ads {
                                link
                                image {
                                    id
                                    file_name
                                    original
                                }
                            }
                        }

                        assignments {
                            data {
                                id
                                title
                                description
                                has_sales_price
                                has_rental_price
                                has_mortgage_price
                                estate_types {
                                    id
                                    title
                                    description
                                    icon
                                }
                            }
                        }

                        estate_types {
                            data {
                                id
                                title
                                description
                                icon
                            }
                        }

                        offices {
                            data {
                                id
                                name
                                username
                                owner {
                                    id
                                    first_name
                                    last_name
                                    full_name
                                    avatar {
                                        id
                                        file_name
                                        thumb
                                    }
                                }
                            }
                        }

                        areas(per_page:50) {
                            data {
                                id
                                name
                                streets {
                                    id
                                    name
                                }
                            }
                        }

                    }
                `;

      if (this.Auth) props.unshift("me");

      axios({
        method: "POST",
        url: this.req_url,
        data: {
          query: query
        }
      })
        .then(({ data }) => {
          if (data.data.siteSetting.title)
            this.site_title = data.data.siteSetting.title;
          if (data.data.siteSetting.theme_color)
            this.web_color = data.data.siteSetting.theme_color;
          props.map(el => {
            if (el == "me" || el == "siteSetting") {
              this.Set_state({ prop: el, val: data.data[el] });
            } else {
              this.Set_state({ prop: el, val: data.data[el].data });
            }
          });
          this.Set_state({ prop: "city_areas", val: data.data.areas.data });
        })
        .then(() => {
          this.Dynamic_Color();
          setTimeout(() => {
            this.Set_state({ prop: "loading", val: false });
          }, 500);
        })
        .catch(Err => {
          if (Err.response && Err.response.status === 401) {
            window.localStorage.removeItem("JWT");
            location.reload();
          } else {
            console.error(Err);
          }
        });
    },

    WatchLocation() {
      this.$watchLocation({
        enableHighAccuracy: true
      }).then(({ lat, lng }) => {
        this.Set_state({ prop: "User_Location", val: [lat, lng] });
      });
    },

    Dynamic_Color() {
      // Web Color
      var style = document.createElement("style");
      style.type = "text/css";
      style.innerHTML = `

                    /* =============== Colors =============== */

                    .web-color {
                        color : ${this.web_color} !important;
                    }

                    .web-color-light {
                        color : ${this.web_color_light} !important;
                    }

                    .web-color-dark {
                        color : ${this.web_color_dark} !important;
                    }

                    .web-color-fade {
                        color : ${this.web_color_fade} !important;
                    }

                    /* =============== Backgrounds =============== */

                    .web-bg {
                        background: ${this.web_color} !important;
                    }

                    .web-bg-light {
                        background: ${this.web_color_light} !important;
                    }

                    .web-bg-dark {
                        background: ${this.web_color_dark} !important;
                    }

                    .web-bg-fade {
                        background: ${this.web_color_fade} !important;
                    }

                    .web-bg-ultra-fade {
                        background: ${this.web_color_ultra_fade} !important;
                    }

                    /* =============== Gradients =============== */

                    .web-grd-to-light {
                        background: -webkit-linear-gradient(90deg, ${this.web_color_light} 10%, ${this.web_color} 90%) !important;
                        background: -moz-linear-gradient(90deg, ${this.web_color_light} 10%, ${this.web_color} 90%) !important;
                        background: -o-linear-gradient(90deg, ${this.web_color_light} 10%, ${this.web_color} 90%) !important;
                        background: linear-gradient(90deg, ${this.web_color_light} 10%, ${this.web_color} 90%) !important;
                    }

                    .web-grd-form-dark {
                        background: -webkit-linear-gradient(90deg, ${this.web_color} 10%, ${this.web_color_dark} 90%) !important;
                        background: -moz-linear-gradient(90deg, ${this.web_color} 10%, ${this.web_color_dark} 90%) !important;
                        background: -o-linear-gradient(90deg, ${this.web_color} 10%, ${this.web_color_dark} 90%) !important;
                        background: linear-gradient(90deg, ${this.web_color} 10%, ${this.web_color_dark} 90%) !important;
                    }

                    /* =============== Borders =============== */

                    .web-border {
                        border : 1px solid ${this.web_color} !important;
                    }

                    /* =============== Other =============== */

                    .property-price .read-more {
                            background-color: ${this.web_color} !important;
                    }

                    .comming-soon {
                        background: ${this.web_color};
                    }

                    .items:hover {
                        background: ${this.web_color} !important;
                    }

                    .items:hover .comming-soon {
                        color: ${this.web_color} !important;
                    }

                    .feature_item:hover {
                        background: ${this.web_color} !important;
                        color: #fff;
                    }

                    .vs-con-input .material-icons {
                        background: ${this.web_color} !important;
                        box-shadow: 0px 2px 10px -7px #000, 0px 4px 10px -5px ${this.web_color} !important;
                    }

                    .vs-input-primary .vs-input--input:focus {
                        border: 1px solid ${this.web_color} !important;
                    }

                    .sec-title:after {
                        background: ${this.web_color} !important;
                    }

                    .sec-title:before {
                        background: ${this.web_color} !important;
                    }

                    .grid-list-btn .el-radio-button__orig-radio:checked+.el-radio-button__inner {
                        background-color: ${this.web_color} !important;
                        border-color: ${this.web_color} !important;
                        box-shadow: 1px 3px 8px -4px ${this.web_color}, 0px 2px 6px -6px #000 !important;
                    }

                    .gallery-item:before {
                        background: -webkit-gradient(linear, left top, left bottom, from(black), color-stop(70%, ${this.web_color}));
                        background: -o-linear-gradient(top, black 0%, ${this.web_color} 70%);
                        background: linear-gradient(to bottom, black 0%, ${this.web_color} 70%);
                    }

                `;
      document.getElementsByTagName("head")[0].appendChild(style);
    },

    change_modal(from, to) {
      this.Set_state({ prop: from, val: false });
      setTimeout(() => {
        this.Set_state({ prop: to, val: true });
      }, 300);
    },

    user_register() {
      this.register.loading = true;

      let query_consultant = `
                    office_name : "${this.register.estate_info.name}" ,
                    office_phone_number : "${
                      this.register.estate_info.phone_number
                    }" ,
                    ${
                      !!this.register.estate_info.area
                        ? `area_id : ${this.register.estate_info.area} ,`
                        : ""
                    }
                    office_username : "${this.register.estate_info.username}" ,
                    office_address : "${this.register.estate_info.address}" ,
                    office_description : "${
                      this.register.estate_info.description
                    }" ,
                    reagent_code : "${this.register.estate_info.reagent_code}" ,
                `;

      axios({
        method: "POST",
        url: this.req_url,
        data: {
          query: `
                            mutation {
                                ${
                                  this.is_consultant
                                    ? "registerConsultant"
                                    : "register"
                                } (
                                    username : "${this.register.username}" ,
                                    phone_number : "${
                                      this.register.phone_number
                                    }" ,
                                    first_name : "${this.register.first_name}" ,
                                    last_name : "${this.register.last_name}" ,
                                    email : "${this.register.email}" ,
                                    password : "${
                                      this.register.password.value
                                    }" ,
                                    password_confirmation : "${
                                      this.register.confirm_password.value
                                    }" ,
                                    ${
                                      this.is_consultant ? query_consultant : ""
                                    }
                                ) {
                                    id
                                    token
                                    system_authentication_code
                                }
                            }                            
                        `
        },
        headers: {
          SystemAuthenticationCode: localStorage.getItem("SAC")
        }
      })
        .then(({ data }) => {
          if (
            this.is_exist(data) &&
            this.is_exist(data.data) &&
            ((this.is_exist(data.data.register) &&
              this.is_exist(data.data.register.token)) ||
              (this.is_exist(data.data.registerConsultant) &&
                this.is_exist(data.data.registerConsultant.token)))
          ) {
            if (this.is_exist(data.data.register)) {
              localStorage.setItem("JWT", data.data.register.token);
              if (data.data.register.system_authentication_code) {
                localStorage.setItem(
                  "SAC",
                  data.data.register.system_authentication_code
                );
              }
            } else {
              localStorage.setItem("JWT", data.data.registerConsultant.token);
              if (data.data.registerConsultant.system_authentication_code) {
                localStorage.setItem(
                  "SAC",
                  data.data.registerConsultant.system_authentication_code
                );
              }
            }
            location.reload();
          } else if (
            this.is_exist(data) &&
            this.is_exist(data.errors) &&
            this.is_exist(data.errors[0].validation)
          ) {
            Object.keys(data.errors[0].validation).map(el => {
              this.notif(
                data.errors[0].validation[el].join(" ، "),
                "warning",
                "error",
                5000
              );
            });
            this.register.loading = false;
          } else {
            this.notif(
              "متاسفانه ثبت نام با موفقیت انجام نشد",
              "warning",
              "error"
            );
            this.register.loading = false;
          }
        })
        .catch(Err => {
          window.localStorage.removeItem("JWT");
          location.reload();
          console.log(Err);
        });
    },

    user_login(with_access_code = false) {
      // console.log(this.login, this.login.email);
      // if (!with_access_code) {
      //   !this.login.email
      //     ? this.notif("نام کاربری را وارد کنید", "warning", "error")
      //     : this.notif("رمز عبور را وارد کنید", "warning", "error");
      //   return;
      // } else {
      //   !this.login.email
      //     ? this.notif("نام کاربری را وارد کنید", "warning", "error")
      //     : this.notif("کد فعالسازی را وارد کنید", "warning", "error");
      //   return;
      // }

      this.login.loading = true;

      axios({
        method: "POST",
        url: this.req_url,
        data: {
          query: `
                            mutation {
                                ${
                                  with_access_code
                                    ? "loginWithAccessCode"
                                    : "login"
                                } (
                                    username : "${this.login.email}" ,
                                    ${
                                      !with_access_code
                                        ? `password : "${this.login.password.value}"`
                                        : `access_code : "${this.login.access_code
                                            .split(" - ")
                                            .join("")}"`
                                    }
                                ) {
                                    token
                                    system_authentication_code
                                }
                            }
                        `
        },
        headers: {
          SystemAuthenticationCode: localStorage.getItem("SAC")
        }
      })
        .then(({ data }) => {
          if (this.$route.path == "/login") this.$router.replace({ path: "/" });
          if (
            this.is_exist(data) &&
            this.is_exist(data.data) &&
            ((this.is_exist(data.data.login) && data.data.login.token) ||
              (this.is_exist(data.data.loginWithAccessCode) &&
                data.data.loginWithAccessCode.token))
          ) {
            if (this.is_exist(data.data.login)) {
              window.localStorage.setItem("JWT", data.data.login.token);
              if (data.data.login.system_authentication_code) {
                window.localStorage.setItem(
                  "SAC",
                  data.data.login.system_authentication_code
                );
              }
            } else {
              window.localStorage.setItem(
                "JWT",
                data.data.loginWithAccessCode.token
              );
              if (data.data.loginWithAccessCode.system_authentication_code) {
                window.localStorage.setItem(
                  "SAC",
                  data.data.loginWithAccessCode.system_authentication_code
                );
              }
            }
            location.reload();
          } else if (data.status == 403) {
            let message = `
                            این حساب بر روی دستگاه شما قابل دسترس نیست ،
                             در صورتی که این حساب متعلق به شماست برای فعال کردن آن با پشتیبانی سایت در ارتباط باشید
                        `;
            this.notif(message, "warning", "error", 20000);
            this.login.loading = false;
          } else if (data.status == 400) {
            this.notif(data.message, "warning", "error", 8000);
            this.login.loading = false;
          } else {
            this.notif(
              with_access_code
                ? "عملیات با موفقیت انجام نشد"
                : "نام کاربری یا رمز عبور اشتباه است",
              "warning",
              "error"
            );
            this.login.loading = false;
          }
        })
        .catch(Err => {
          if (Err.response && Err.response.status === 401) {
            window.localStorage.removeItem("JWT");
            location.reload();
          } else {
            console.log(Err);
          }
        });
    },

    reset_password() {
      this.reset_pass.loading = true;

      axios({
        method: "POST",
        url: this.req_url,
        data: {
          query: `
                            mutation {
                                requestResetPassword (
                                    email : "${this.reset_pass.email}"
                                )
                                {
                                    status
                                    message
                                }
                            }                            
                        `
        }
      })
        .then(({ data }) => {
          if (
            this.is_exist(data) &&
            this.is_exist(data.data) &&
            this.is_exist(data.data.requestResetPassword)
          ) {
            data.data.requestResetPassword.status == 200
              ? this.notif(
                  data.data.requestResetPassword.message,
                  "success",
                  "email",
                  15000
                )
              : this.notif(
                  data.data.requestResetPassword.message,
                  "warning",
                  "error",
                  8000
                );

            this.reset_pass.loading = false;
            this.Set_state({ prop: "reset_pass_modal", val: false });
          } else {
            this.notif(
              "متاسفانه عملیات باموفقیت انجام نشد",
              "warning",
              "error"
            );
            this.reset_pass.loading = false;
          }
        })
        .catch(Err => {
          if (Err.response && Err.response.status === 401) {
            window.localStorage.removeItem("JWT");
            location.reload();
          } else {
            console.log(Err);
          }
        });
    },

    change_password() {
      this.change_pass.loading = true;

      axios({
        method: "POST",
        url: this.req_url,
        data: {
          query: `
                            mutation {
                                resetPassword (
                                    token : "${this.$route.params.token}" ,
                                    password : "${this.change_pass.password.value}"
                                    password_confirmation : "${this.change_pass.confirm_password.value}"
                                ) {
                                    token
                                    system_authentication_code
                                }
                            }                            
                        `
        },
        headers: {
          SystemAuthenticationCode: localStorage.getItem("SAC")
        }
      })
        .then(({ data }) => {
          if (
            this.is_exist(data) &&
            this.is_exist(data.data) &&
            this.is_exist(data.data.resetPassword) &&
            this.is_exist(data.data.resetPassword.token) &&
            this.is_exist(data.data.resetPassword.system_authentication_code)
          ) {
            window.localStorage.setItem("JWT", data.data.resetPassword.token);
            window.localStorage.setItem(
              "SAC",
              data.data.resetPassword.system_authentication_code
            );
            this.$router.replace({ path: "/" });
            location.reload();
          } else if (data.status == 403) {
            let message = `
                            این حساب بر روی دستگاه شما قابل دسترس نیست ،
                             در صورتی که این حساب متعلق به شماست برای فعال کردن آن با پشتیبانی سایت در ارتباط باشید
                        `;
            this.notif(message, "warning", "error", 20000);
            this.change_pass.loading = false;
          } else if (data.status == 400) {
            this.notif(data.message, "warning", "error", 8000);
            this.change_pass.loading = false;
          } else {
            this.notif(
              "متاسفانه عملیات با موفقیت انجام نشد",
              "warning",
              "error"
            );
            this.change_pass.loading = false;
          }
        })
        .catch(Err => {
          if (Err.response && Err.response.status === 401) {
            window.localStorage.removeItem("JWT");
            this.$router.replace({ path: "/" });
            location.reload();
          } else {
            console.log(Err);
          }
        });
    },

    back_to_top() {
      this.$vuetify.goTo(0, {
        duration: 1000
      });
    },

    anime_btn(enter) {
      anime({
        targets: "#back-to-top",
        translateX: enter ? -100 : 100,
        duration: 500,
        easing: "easeInOutBack"
      });
    }
  }
};
</script>

<style>
.access_code input {
  text-align: center;
  font-size: 19px !important;
  direction: ltr;
}

.access_code label {
  left: 50% !important;
  transform: translateX(-50%) !important;
}

.areas.el-select-dropdown__item {
  direction: rtl;
}

.forget-pass-text {
  position: absolute;
  color: #1ca2bd;
  left: 10px;
  cursor: pointer;
  font-size: 11px;
  font-weight: 400;
}

.tel-text {
  color: #1ca2bd !important;
  cursor: pointer;
  font-weight: 400;
}

.forget-pass-text:after {
  left: 0;
  right: 0;
  top: 50%;
  margin-top: 0.85em;
  content: "";
  position: absolute;
  border-bottom: 1px dashed #1ca2bd;
}

#back-to-top {
  z-index: 200;
  position: fixed;
  bottom: 20px;
  right: -80px;
}

.el-link.el-link--primary {
  color: #409eff !important;
}

.login-modal .vs-popup {
  width: 380px !important;
  text-align: right;
}

.con-vs-popup {
  z-index: 1000 !important;
}

.v-stepper__step__step {
  margin-right: 0px !important;
  margin-left: 8px;
}

.v-textarea label {
  right: 15px !important;
}

.v-text-field .v-input__prepend-inner {
  margin: auto !important;
}

.v-text-field .v-input__prepend-inner i {
  font-size: 20px !important;
}

.v-input__slot {
  margin-bottom: 3px !important;
}

.v-messages__message {
  font-size: 10px;
}

.register-modal .v-messages__message {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.v-text-field.v-text-field--enclosed .v-text-field__details {
  min-height: 20px !important;
}

.v-stepper,
.v-stepper__header {
  box-shadow: unset !important;
}

.register h6 {
  position: relative;
  text-align: right;
  color: #484848;
  padding-right: 5px;
}

.register h6.required:after {
  color: #ff0000 !important;
  /* content: ' / اجباری ';
        font-size: 10px; */
  content: "*";
  font-size: 15px;
}

.register .el-input__inner {
  border: 1px solid rgba(0, 0, 0, 0.54);
  height: 45px !important;
}

.register .el-input__inner::placeholder {
  color: rgba(0, 0, 0, 0.54);
}

.v-input input {
  font-size: 14px;
  margin: auto !important;
}

.v-text-field .v-label {
  font-size: 13px;
  margin: 0px !important;
  top: 11px;
}

.small-text-field.v-text-field--outline > .v-input__control > .v-input__slot {
  min-height: 45px !important;
  max-height: 45px !important;
}

.v-input__slot {
  border: 1px solid !important;
}

.checkbox {
  direction: rtl !important;
  margin: 0px !important;
}

.checkbox .v-input__slot {
  border: unset !important;
}

.checkbox label {
  color: #484848 !important;
  margin: 0px !important;
}

.checkbox .v-input--selection-controls__input {
  margin-right: 0px !important;
  margin-left: 10px !important;
}

.vs-popup--title h3 {
  text-align: center;
  font-weight: bold;
  margin: 5px;
}

.as-loading {
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  z-index: 9999999999;
  height: 100%;
  width: 100%;
  background: #fffffff0;
  top: 0px;
  bottom: 0;
  right: 0;
  left: 0;
}

.v-navigation-drawer {
  z-index: 2000;
}

.v-overlay {
  z-index: 1000;
}

.site-drawer .v-list__tile__title {
  font-size: 12px;
  transform: scaleX(-1);
  text-align: right;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  max-width: 180px;
  direction: rtl;
}

.site-drawer .v-list {
  transform: scaleX(-1);
}

.site-drawer .material-icons {
  transform: scaleX(-1);
}

.vs-notifications p {
  font-size: 12px !important;
}
</style>
