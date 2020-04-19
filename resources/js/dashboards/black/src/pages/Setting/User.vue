<template>
  <div class="row" :style="{ position: 'relative', zIndex: 10 }">
    <div class="row col-12 mb-3">
      <div class="col-12 text-right">
        <div class="pull-right">
          <h1 class="animated bounceInRight delay-first" :style="{ color: '#fff', fontWeight: 'bold', textShadow: '0px 3px 15px #333' }">
            تنظیمات <span :style="{ color: '#ff3d3d' }">حساب</span> کاربری
            <i class="header-nav-icon tim-icons icon-align-left-2" :style="{fontSize: '25px'}"></i>
          </h1>
          <h6 class="header-description animated bounceInRight delay-secound">با استفاده از این بخش میتوانید اطلاعات حساب خود را مدیریت کرده یا رمز عبور خود را تغییر دهید</h6>
        </div>
        <div class="pull-left animated bounceInDown delay-last">
          <flip-clock :options="{
            label: false,
            clockFace: 'TwentyFourHourClock'
          }" />
        </div>
      </div>
    </div>

    <card class="text-right animated bounceInBottom delay-last" v-if="is_loaded" dir="rtl">
      <span slot="header">
        ویرایش اطلاعات کاربری
      </span>

      <div class="row">
        <div class="col-md-4">
          <base-input label="عکس آواتار">
            <el-upload
              class="avatar-uploader"
              action="/"
              :auto-upload="false"
              :show-file-list="false"
              :on-change="addImage">
              <div v-if="$store.state[group].form.user.avatar.url">
                <img
                  :src="$store.state[group].form.user.avatar.url"
                  :style="{ borderRadius: '0px', width: '100% !important' }"
                />
                <i @click.prevent="deleteImage" class="el-icon-delete avatar-uploader-icon"></i>
              </div>
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
            <small slot="helperText" id="emailHelp" class="form-text text-muted">عکس پروفایل حساب شما</small>
          </base-input>
        </div>

        <div class="col-md-8">
          <div class="row">
            <div class="col-md-4">
              <md-field :class="getValidationClass('first_name')">
                <label>نام</label>
                <md-input v-model="info.first_name" :maxlength="$v.first_name.$params.maxLength.max" />
                <i class="md-icon tim-icons icon-badge"></i>
                <span class="md-helper-text">برای مثال : علی رضا</span>
              </md-field>
            </div>

            <div class="col-md-4">
              <md-field :class="getValidationClass('last_name')">
                <label>نام خانوادگی</label>
                <md-input v-model="info.last_name" :maxlength="$v.last_name.$params.maxLength.max" />
                <i class="md-icon tim-icons icon-badge"></i>
                <span class="md-helper-text">برای مثال : حسین زاده</span>
              </md-field>
            </div>
            
            <div class="col-md-4">
              <md-field :class="getValidationClass('national_code')">
                <label>کد ملی</label>
                <md-input v-model="info.national_code" :maxlength="$v.national_code.$params.maxLength.max" />
                <i class="md-icon tim-icons icon-single-02"></i>
                <span class="md-helper-text">برای مثال : ۰۹۲۱۲۳۴۵۶۷</span>
              </md-field>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <md-field>
                <label>شماره تلفن</label>
                <md-input v-model="info.phone_number" />
                <i class="md-icon tim-icons icon-email-85"></i>
                <span class="md-helper-text">برای مثال : ۰۹۱۲۳۴۵۶۷۸۹</span>
                <span class="md-error" v-show="!$v.phone_number.required">لطفا شماره تلفن را وارد کنید</span>
              </md-field>
            </div>

            <div class="col-md-6">
              <md-field>
                <label>نام کاربری</label>
                <md-input v-model="info.username" disabled :maxlength="$v.username.$params.maxLength.max" />
                <i class="md-icon tim-icons icon-email-85"></i>
                <span class="md-helper-text">برای مثال : test_username</span>
                <span class="md-error" v-show="!$v.username.required">لطفا نام نقش را وارد کنید</span>
              </md-field>
            </div>
          </div>

          <div class="row">
            <div class="col-md-7">
              <md-field :class="getValidationClass('email')">
                <label>آدرس ایمیل</label>
                <md-input v-model="info.email" :maxlength="$v.email.$params.maxLength.max" />
                <i class="md-icon tim-icons icon-email-85"></i>
                <span class="md-helper-text">برای مثال : info@example.com</span>
              </md-field>
            </div>

            <div class="col-md-5">
              <md-field :class="getValidationClass('gender')">
                <label>جنسیت</label>
                <md-select v-model="info.gender" >
                  <md-option value="false">مونث</md-option>
                  <md-option value="true">مذکر</md-option>
                  <md-option value="null">نا مشخص</md-option>
                </md-select>
                <i class="md-icon tim-icons icon-satisfied"></i>
                <span class="md-helper-text">جنسیت خود را مشخص کنید</span>
              </md-field>
            </div>
          </div>
          <br/>

          <div class="row">
            <div class="col-md-8">
              <md-field>
                <label>آدرس</label>
                <md-input v-model="info.address" :maxlength="$v.address.$params.maxLength.max" />
                <i class="md-icon tim-icons icon-email-85"></i>
                <span class="md-helper-text">برای مثال : خیابان آزادی ، آزادی ۵۲ ، پلاک ۲۳</span>
                <span class="md-error" v-show="!$v.address.required">لطفا آدرس را وارد کنید</span>
              </md-field>
            </div>

            <div class="col-md-4">
              <base-input label="نمایش اطلاعات">
                <el-switch
                  dir="ltr"
                  class="d-flex justify-content-center"
                  v-model="info.is_public_info"
                  active-text="نمایش"
                  active-color="#ff8d72"
                  inactive-text="مخفی">
                </el-switch>
                <small slot="helperText" id="emailHelp" class="form-text text-muted">نمایش اطلاعات شما به عنوان ثبت کننده املاک</small>
              </base-input>
            </div>
          </div>

          <div class="row d-flex justify-content-center mt-3">
            <base-button
              @click="updateInfo"
              :loading="is_update_info_loading"
              size="sm"
              type="warning"
            >
              <transition name="fade" mode="out-in">
                <semipolar-spinner
                  :animation-duration="2000"
                  :size="17"
                  color="#fff"
                  v-if="is_update_info_loading"
                />
                <span v-else class="pull-right ml-2" >
                  <i v-if="attr('is_creating')" class="tim-icons icon-simple-add"></i>
                  <i v-else class="tim-icons icon-pencil"></i>
                </span>
              </transition>
              بروز رسانی اطلاعات کلی
            </base-button>
          </div>
        </div>
      </div>
    </card>

    <card class="text-right animated bounceInBottom delay-last" v-if="is_loaded && office" dir="rtl">
      <span slot="header">
        ویرایش اطلاعات دفتر املاک
      </span>

      <div class="row">
        <div class="col-md-6">
          <md-field :class="getValidationClass('name')">
            <md-input v-model="office.name" :maxlength="$v.office.name.$params.maxLength.max" />
            <label>نام دفتر املاک</label>
            <i class="md-icon tim-icons icon-caps-small"></i>
            <span class="md-helper-text">برای مثال : املاک آسمان</span>
            <span class="md-error" v-show="!$v.office.name.required">لطفا نام دفتر املاک را وارد کنید</span>
          </md-field>
        </div>

        <div class="col-md-6">
          <md-field :class="getValidationClass('username')">
            <label>نام کاربری</label>
            <md-input v-model="office.username" disabled :maxlength="$v.office.username.$params.maxLength.max" />
            <i class="md-icon tim-icons icon-badge"></i>
            <span class="md-helper-text">برای مثال : amlak-aseman</span>
            <span class="md-error" v-show="!$v.office.username.required">لطفا نام کاربری دفتر املاک را وارد کنید</span>
          </md-field>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <md-field>
            <label>منطقه</label>
            <md-select v-model="office.area.id">
              <md-option v-for="item in $store.state.place.area" :key="item.id" :value="item.id">{{ item.name }}</md-option>
            </md-select>
            <i class="md-icon tim-icons icon-map-big"></i>
            <span class="md-helper-text">منطقه این دفتر املاک را مشخص کنید</span>
          </md-field>
        </div>

        <div class="col-md-6">
          <md-field :class="getValidationClass('phone_number')">
            <label>شماره تلفن</label>
            <md-input v-model="office.phone_number" />
            <i class="md-icon tim-icons icon-mobile"></i>
            <span class="md-helper-text">برای مثال : ۰۹۱۲۳۴۵۶۷۸۹</span>
            <span class="md-error" v-show="!$v.office.phone_number.required">لطفا شماره تلفن دفتر املاک را وارد کنید</span>
          </md-field>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <md-field :class="getValidationClass('address')">
            <label>آدرس</label>
            <md-textarea v-model="office.address" md-autogrow :maxlength="$v.office.address.$params.maxLength.max"></md-textarea>
            <i class="md-icon tim-icons icon-square-pin"></i>
            <span class="md-helper-text">آدرس دفتر املاک</span>
            <span class="md-error" v-show="!$v.office.address.required">لطفا آدرس دفتر املاک را وارد کنید</span>
          </md-field>
        </div>

        <div class="col-md-6">
          <md-field :class="getValidationClass('description')">
            <label>توضیحات</label>
            <md-textarea v-model="office.description" md-autogrow :maxlength="$v.office.description.$params.maxLength.max"></md-textarea>
            <i class="md-icon tim-icons icon-paper"></i>
            <span class="md-helper-text">توضیحی مختصر درباره دفتر املاک</span>
          </md-field>
        </div>
      </div>
      <br/>

      <div class="row d-flex justify-content-center mt-3">
        <base-button
          @click="updateOffice"
          :loading="is_update_office_loading"
          size="sm"
          type="warning"
        >
          <transition name="fade" mode="out-in">
            <semipolar-spinner
              :animation-duration="2000"
              :size="17"
              color="#fff"
              v-if="is_update_office_loading"
            />
            <span v-else class="pull-right ml-2" >
              <i v-if="attr('is_creating')" class="tim-icons icon-simple-add"></i>
              <i v-else class="tim-icons icon-pencil"></i>
            </span>
          </transition>
          بروز رسانی اطلاعات دفتر املاک
        </base-button>
      </div>
    </card>

    <card class="text-right animated bounceInBottom delay-last" v-if="is_loaded" dir="rtl">
      <span slot="header">
        بروزرسانی رمز عبور
      </span>

      <div class="row">
        <div class="col-md-4">
          <md-field>
            <label>رمز عبور کنونی</label>
            <md-input type="password" v-model="password.current" :maxlength="100" />
            <!-- <i class="md-icon tim-icons icon-paper"></i> -->
            <span class="md-helper-text">رمز عبور فعلی خود را وارد کنید</span>
          </md-field>
        </div>

        <div class="col-md-4">
          <md-field>
            <label>رمز عبور جدید</label>
            <md-input type="password" v-model="password.main" :maxlength="100" />
            <!-- <i class="md-icon tim-icons icon-paper"></i> -->
            <span class="md-helper-text">رمز عبور جدید را وارد کنید</span>
          </md-field>
        </div>

        <div class="col-md-4">
          <md-field :class="getValidationClass('first_name')">
            <label>تکرار رمز عبور جدید</label>
            <md-input type="password" v-model="password.confirm" :maxlength="100" />
            <!-- <i class="md-icon tim-icons icon-paper"></i> -->
            <span class="md-helper-text">تکرار رمز عبور جدید را وارد کنید</span>
          </md-field>
        </div>
      </div>

      <base-button
        @click="updatePassword"
        size="sm"
        :loading="is_update_password_loading"
        type="warning"
      >
        <transition name="fade" mode="out-in">
          <semipolar-spinner
            :animation-duration="2000"
            :size="17"
            color="#fff"
            v-if="is_update_password_loading"
          />
          <span v-else class="pull-right ml-2" >
            <i v-if="attr('is_creating')" class="tim-icons icon-simple-add"></i>
            <i v-else class="tim-icons icon-pencil"></i>
          </span>
        </transition>
        تغییر رمز عبور
      </base-button>
    </card>

    <div class="row mb-3 w-100" v-if="info.can_has_member">
      <div class="col-12 text-right">
        <div class="pull-right">
          <h3 class="animated bounceInRight delay-first m-0">
            زیر مجموعه های شما
            <i class="tim-icons icon-bullet-list-67"></i>
          </h3>
          <h6 class="text-muted animated bounceInRight delay-secound">در ساختار درخت شکل زیر ، میتوانید کلیه افراد زیر مجموعه و شخص نسل بالاتر خود را مشاهده و بررسی کنید</h6>
        </div>
      </div>
    </div>
    <div class="row w-100" v-if="info.can_has_member">
      <el-tree
        class="rtl group-tree user-members-tree w-100"
        :data="members"
        node-key="id"
        empty-text="هیچ گونه اطلاعاتی یافت نشد :("
        :props="{
          children: 'members',
          label: 'username',
        }"
      >
        <div class="custom-tree-node data-table-row col-12 pr-3" slot-scope="{ node, data }">
          <div class="pull-left d-flex align-items-center">
            <img :src="data.avatar ? data.avatar.thumb : '/images/placeholder-user.png'" />
            <div class="pull-right group-info" v-if="data.full_name.trim()">
              <h4 class="md-list-item-text mb-0" :class="{ 'text-danger' : data.full_name === 'خود شما' }" :style="{ overflow: 'visible' }">
                {{ data.full_name }}
              </h4>
              <p class="text-muted">{{ data.username }}</p>
            </div>
            <div class="pull-right group-info" v-else>
              <h4 class="md-list-item-text mb-0" :style="{ overflow: 'visible' }">
                {{ data.username }}
              </h4>
            </div>

            <transition-group name="list" class="operation-cell pull-right">
              <span
                v-for="item in data.roles.filter( (role, index) => index < 3)"
                :key="item.id"
                class="badge badge-default ml-1 hvr-grow-shadow hvr-icon-grow">
                <i class="tim-icons icon-single-02 hvr-icon"></i>
                {{ item.display_name }}
              </span>

              <el-dropdown v-if="data.roles.length > 3" :key="data.roles.map((c) => c.id).join(',')">
                <span class="el-dropdown-link badge badge-default">
                  باقی نقش ها <i class="el-icon-arrow-down el-icon--right"></i>
                </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item
                    v-for="item in data.roles.filter( (role, index) => index < 3)"
                    :key="item.id">
                    {{ item.display_name }}
                  </el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </transition-group>
          </div>
        </div>
      </el-tree>
    </div>

    <transition name="fade">
      <div class="main-panel-loading" v-if="!is_loaded">
        <fingerprint-spinner
          :animation-duration="1000"
          :size="100"
          color="#fff"
        />
      </div>
    </transition>
  </div>
</template>
<script>
import { FlipClock } from '@mvpleung/flipclock';
import { validationMixin } from 'vuelidate'
import { required, maxLength } from 'vuelidate/lib/validators'
import binding, { bind } from '../../mixins/binding';
import createMixin from '../../mixins/createMixin'
import {SemipolarSpinner, FingerprintSpinner} from 'epic-spinners'
import persianJs from 'persianjs'

export default {
  components: {
    FlipClock,
    SemipolarSpinner,
    FingerprintSpinner
  },
  mixins: [
    validationMixin,
    binding,
    createMixin
  ],
  metaInfo: {
    title: 'اطلاعات کاربری',
  },
  validations: {
    first_name: {
      maxLength: maxLength(20)
    },
    last_name: {
      maxLength: maxLength(30)
    },
    email: {
      maxLength: maxLength(100)
    },
    national_code: {
      maxLength: maxLength(10)
    },
    username: {
      maxLength: maxLength(30)
    },
    address: {
      maxLength: maxLength(100)
    },
    phone_number: {
      required
    },
    office: {
      name: {
        required,
        maxLength: maxLength(50)
      },
      username: {
        required,
        maxLength: maxLength(30)
      },
      description: {
        maxLength: maxLength(250)
      },
      phone_number: {
        required
      },
      address: {
        required,
        maxLength: maxLength(100)
      }, 
    }
  },
  data() {
    return {
      type: 'user',
      group: 'user',
      label: 'اطلاعات کاربری',

      info: {
        first_name: '',
        last_name: '',
        email: '',
        username: '',
        address: '',
        phone_number: '',
        national_code: '',
        gender: null,
        can_has_member: false,
        is_public_info: true,
        avatar: {
          file: null,
          url: ''
        }
      },
      office: null,
      members: [
        // 
      ],
      parent: null,
      password: {
        current: '',
        main: '',
        confirm: ''
      },

      is_loaded: false,
      is_query_loading: false,
      is_update_info_loading: false,
      is_update_office_loading: false,
      is_update_password_loading: false,
    }
  },
  mounted()
  {
    axios.post('/graphql/auth', {
      query: `{
        me {
          ${this.allQuery}
          parent {
            id first_name last_name
            address phone_number
            username avatar { id file_name thumb}
            roles { id display_name }
          }

          workplace {
            id name username description address phone_number area { id }
          }
          
          members {
            id first_name last_name full_name
            username avatar { id file_name thumb}
            roles { id display_name }
            
            members {
              id first_name last_name full_name
              username avatar { id file_name thumb}
              roles { id display_name }

              members {
                id first_name last_name full_name
                username avatar { id file_name thumb}
                roles { id display_name }
              }
            }
          }
        }
      }`
    })
    .then(({data}) => {
      this.info = data.data.me

      data.data.me.members = [{
        id: this.$store.state.me.id,
        avatar: this.$store.state.me.avatar,
        first_name: this.$store.state.me.first_name,
        last_name: this.$store.state.me.last_name,
        full_name: 'خود شما',
        username: this.$store.state.me.username,
        roles: [],
        members: data.data.me.members
      }]

      if ( data.data.me.parent )
      {
        data.data.me.parent.members = data.data.me.members
        data.data.me.members = [data.data.me.parent]
      }

      this.members = data.data.me.members
      this.parent = data.data.me.parent

      if ( this.info.avatar )
        this.$store.state.user.form.user.avatar.url = this.info.avatar.small

      if ( this.info.workplace )
      {
        this.$store.dispatch('getData', {
          group: 'place',
          type: 'area',
          query: `areas(per_page: 20) {
            data { id name coordinates { lat lng } city { id name } created_at updated_at }
            total trash chart { month count }
          }`
        })
    
        if ( !this.info.workplace.area )
          this.info.workplace.area = { id: null }

        this.office = this.info.workplace
      }

      this.is_loaded = true
    })
  },
  methods:
  {
    validate()
    {
      return true
    },
    updateInfo()
    {
      this.type = 'user'
      this.is_update_info_loading = true

      this.setAttr('selected', { id: this.$store.state.me.id })

      this.storeInServer({
        callback: ({data}) => {
          this.$store.commit('setMeInfo', data)
          this.is_update_info_loading = false
        },
        errorResolver: () => this.is_update_info_loading = false
      })
    },
    updateOffice()
    {
      this.type = 'office'
      this.is_update_office_loading = true

      this.setAttr('selected', { id: this.office.id })

      this.storeInServer({
        callback: ({data}) => {
          this.type = 'user'
          this.is_update_office_loading = false
        },
        errorResolver: () => {
          this.type = 'user'
          this.is_update_office_loading = false
        }
      })
    },
    updatePassword()
    {
      this.is_update_password_loading = true

      axios.post('/graphql/auth', {
        query: `mutation {
          updateUserPassword (
            current_password: "${this.password.current}",
            password: "${this.password.main}",
            password_confirmation: "${this.password.confirm}"
          ) {
            status
            message
          }
        }`
      })
      .then(({data}) =>
      {
        this.is_update_password_loading = false

        if ( data.data.updateUserPassword.status === 400 )
        {
          return this.$swal.fire({
            title: 'تغییری نکرد',
            text: data.data.updateUserPassword.message,
            type: 'error',
            timer: 2000,
          })
        }

        this.$swal.fire({
          title: 'تغییر کرد',
          text: 'رمز عبور با موفقیت تغییر کرد ، لطفا دوباره وارد حساب خود شوید',
          type: 'success',
          timer: 2000,
        })

        setTimeout(() => {
          localStorage.removeItem('JWT');
          window.location.replace('/login')
        }, 2000);
      })
      .catch(error => {
        this.is_update_password_loading = false
        this.errorResolver(error)
      })
    },
    getVariables()
    {
      if ( this.type === 'user' ) {
        return {
          first_name: this.info.first_name,
          last_name: this.info.last_name,
          address: this.info.address,
          phone_number: this.info.phone_number ? persianJs(this.info.phone_number).persianNumber().toString() : null,
          username: this.info.username,
          email: this.info.email,
          national_code: this.info.national_code,
          gender: this.info.gender === 'true' ? true : this.info.gender === 'false' ? false : null,
          is_public_info: this.info.is_public_info,
          avatar: null
        }
      }

      return {
        name: this.office.name,
        username: this.office.username,
        description: this.office.description,
        address: this.office.address,
        phone_number: this.office.phone_number,
        area_id: this.office.area.id
      }
    }
  },
  computed: {
    allQuery()
    {
      if ( this.type === 'user' ) {
        return `
          id
          first_name
          last_name
          full_name
          username
          address
          phone_number
          email
          national_code
          gender
          can_has_member
          is_public_info
          avatar {
            id file_name thumb small
          }
        `
      }

      return `
        name username description address phone_number
        area { id name }
      `
    }
  }
}
</script>

<style>
.user-members-tree img {
  margin: 6px 14px !important;
}
</style>