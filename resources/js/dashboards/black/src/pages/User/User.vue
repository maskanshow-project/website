<template>
  <div>
    <datatable
      class="spec-rows-table"
      :type="type"
      :group="group"
      :label="label"
      :fields="getFields"
      
      :methods="{
        create: create,
        edit: edit,
        store: store,
        update: update
      }"
      :canCreate="false" 
      ref="datatable"
      :ignoreOperations="ignoreOperations"
    >
      <template #avatar-body="slotProps">
        <img class="tilt" :src="slotProps.row.avatar ? slotProps.row.avatar.thumb : '/images/placeholder-user.png'" />
      </template>

      <template #plan-body="slotProps">
        <table class="table-p-0">
          <tbody>
            <tr v-if="slotProps.row.remaining_registered_count">
              <td>
                <i class="tim-icons icon-simple-add"></i>
              </td>
              <td class="text-right">
                {{ slotProps.row.remaining_registered_count }} آگهی
              </td>
            </tr>
            <tr v-if="slotProps.row.remaining_hits_count">
              <td>
                <i class="tim-icons icon-tap-02"></i>
              </td>
              <td class="text-right">
                {{ slotProps.row.remaining_hits_count }} ملک
              </td>
            </tr>
            <tr v-if="slotProps.row.validity_end_at">
              <td>
                <i class="tim-icons icon-time-alarm"></i>
              </td>
              <td class="text-right">
                {{ slotProps.row.validity_end_at | ago }}
              </td>
            </tr>
          </tbody>
        </table>
      </template>

      <template #status-body="slotProps">
        <i class="tim-icons icon-check-2 text-success ml-2"></i>  
        <el-switch
          :disabled="can(`active-${type}`)"
          @change="accept(slotProps.index, $event)"
          v-model="slotProps.row.can_has_member"
          active-color="#13ce66"
          inactive-color="#ff4949"
        ></el-switch>
        <i class="tim-icons icon-simple-remove text-danger mr-2"></i>
      </template>

      <template #info-body="slotProps">
        <table class="spec-feature-table">
          <tbody>
            <tr v-if="slotProps.row.full_name.trim()">
              <td>
                <i class="tim-icons icon-single-02"></i>
              </td>
              <td>
                {{ slotProps.row.full_name }}
              </td>
            </tr>

            <tr>
              <td>
                <i class="tim-icons icon-badge"></i>
              </td>
              <td>
                {{ slotProps.row.username }}
              </td>
            </tr>

            <tr v-if="!can('see-details-user')">
              <td>
                <i class="tim-icons icon-email-85"></i>
              </td>
              <td>
                <a :href="`mailto:${slotProps.row.email}`">{{ slotProps.row.email }}</a>
              </td>
            </tr>

            <tr v-if="!can('see-phone-number-user') && slotProps.row.phone_number">
              <td>
                <i class="tim-icons icon-mobile"></i>
              </td>
              <td>
                <a :href="`telto:${slotProps.row.phone_number}`">{{ slotProps.row.phone_number }}</a>
              </td>
            </tr>
          </tbody>
        </table>
      </template>

      <template #roles-body="slotProps">
        <transition-group name="list">
          <span
            v-for="item in slotProps.row.roles.filter( (role, index) => index < 3)"
            :key="item.id"
            class="badge badge-default ml-1 hvr-grow-shadow hvr-icon-grow">
            <i class="tim-icons icon-single-02 hvr-icon"></i>
            {{ item.display_name }}
          </span>

          <el-dropdown v-if="slotProps.row.roles.length > 3" :key="slotProps.row.roles.map((c) => c.id).join(',')">
            <span class="el-dropdown-link badge badge-default">
              باقی نقش ها <i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item
                v-for="item in slotProps.row.roles.filter( (role, index) => index < 3)"
                :key="item.id">
                {{ item.display_name }}
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </transition-group>
      </template>
      
      <template #custom-buttons>
        <transition name="fade">
          <base-button
            @click="accept_multiple"
            v-show="attr('selected_items').length >= 1"
            :disabled="can(`active-${type}`)"
            size="sm"
            :type="acceptType ? 'success' : 'warning'"
            class="pull-left mr-2"
          >
            {{ acceptType ? 'تایید' : 'رد' }}
            <ICountUp
              :style="{display: 'inline'}"
              :endVal="attr('selected_items').length"
              :options="{
                useEasing: true,
                useGrouping: true,
              }"
            />
            مورد انتخاب شده
            <i class="tim-icons" :class="acceptType ? 'icon-check-2' : 'icon-refresh-01'"></i>
          </base-button>
        </transition>

        <transition name="fade">
          <el-checkbox-group v-model="selected_roles" v-show="attr('selected_items').length === 0" :style="{
            position: 'absolute',
            left: '0px',
            top: '0px'
          }">
            <el-checkbox-button v-for="role in $store.state.user.role" :label="role.id" :key="role.id">{{ role.display_name }}</el-checkbox-button>
          </el-checkbox-group>
        </transition>
      </template>

      <template #custom-operations="slotProps">
        <base-dropdown
          v-if="
               !can('change-credit-user')
            || !can('empty-auth-code-user')
            || !can('create-access-code')
            || !can('reset-password-user')
            || !can('see-sessions-user')
          "
          class="dropup user-operations-dropdown"
          title-classes="btn btn-success"
        >
          <div
            class="p-2"
            @click="manageCredit(slotProps.index, slotProps.row)"
            v-if="!can('change-credit-user')"
          >
            <i class="tim-icons icon-credit-card"></i>
            تغییر اعتبار
          </div>

          <div
            class="p-2"
            @click="emptyAuthCode(slotProps.index, slotProps.row)"
            v-if="!can('empty-auth-code-user')"
          >
            <i class="tim-icons icon-lock-circle"></i>
            حذف کد دسترسی سیستم
          </div>

          <div
            class="p-2"
            @click="createAccessCode(slotProps.index, slotProps.row)"
            v-if="!can('create-access-code')"
          >
            <i class="tim-icons icon-key-25"></i>
            ساختن کد دسترسی جدید
          </div>

          <div
            class="p-2"
            @click="resetPassword(slotProps.index, slotProps.row)"
            v-if="!can('reset-password-user')"
          >
            <i class="tim-icons icon-refresh-02"></i>
            تغییر رمز عبور
          </div>

          <div
            class="p-2"
            @click="seeSessions(slotProps.index, slotProps.row)"
            v-if="!can('reset-password-user')"
          >
            <i class="tim-icons icon-tv-2"></i>
            مشاهده نشست ها
          </div>
        </base-dropdown>
      </template>

      <template #modal>
        <div class="row d-flex justify-content-center">
          <div class="col-md-6">
            <base-input label="تصویر پروفایل کاربر">
              <el-upload
                class="avatar-uploader"
                action="/"
                :auto-upload="false"
                :show-file-list="false"
                :on-change="addImage">
                <div v-if="$store.state[group].form[type].avatar.url">
                  <img :src="$store.state[group].form[type].avatar.url" class="avatar" />
                  <i @click.prevent="deleteImage" class="el-icon-delete avatar-uploader-icon"></i>
                </div>
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
              </el-upload>
              <small slot="helperText" id="emailHelp" class="form-text text-muted">تصویر مورد نظر خود را انتخاب کنید</small>
            </base-input>
          </div>
        </div>

        <div class="row">
          <div class="col-6">
            <md-field :class="getValidationClass('first_name')">
              <label>نام</label>
              <md-input v-model="first_name" :maxlength="$v.first_name.$params.maxLength.max" />
              <i class="md-icon tim-icons icon-badge"></i>
              <span class="md-helper-text">برای مثال : امیر</span>
              <span class="md-error" v-show="!$v.first_name.required">لطفا نام نقش را وارد کنید</span>
            </md-field>
          </div>
          <div class="col-6">
            <md-field :class="getValidationClass('last_name')">
              <label>نام خانوادگی</label>
              <md-input v-model="last_name" :maxlength="$v.last_name.$params.maxLength.max" />
              <i class="md-icon tim-icons icon-badge"></i>
              <span class="md-helper-text">برای مثال : امیری نژاد</span>
              <span class="md-error" v-show="!$v.last_name.required">لطفا نام نقش را وارد کنید</span>
            </md-field>
          </div>
        </div>

        <md-field :class="getValidationClass('email')">
          <label>آدرس ایمیل</label>
          <md-input v-model="email" :maxlength="$v.email.$params.maxLength.max" />
          <i class="md-icon tim-icons icon-email-85"></i>
          <span class="md-helper-text">برای مثال : test@example.com</span>
          <span class="md-error" v-show="!$v.email.required">لطفا نام نقش را وارد کنید</span>
        </md-field>

        <div class="row">
          <div class="col-md-6">
            <md-field :class="getValidationClass('phone_number')">
              <label>شماره تلفن</label>
              <md-input v-model="phone_number" />
              <i class="md-icon tim-icons icon-email-85"></i>
              <span class="md-helper-text">برای مثال : ۰۹۱۲۳۴۵۶۷۸۹</span>
              <span class="md-error" v-show="!$v.phone_number.required">لطفا شماره تلفن را وارد کنید</span>
            </md-field>
          </div>

          <div class="col-md-6">
            <md-field :class="getValidationClass('username')">
              <label>نام کاربری</label>
              <md-input v-model="username" :maxlength="$v.username.$params.maxLength.max" />
              <i class="md-icon tim-icons icon-email-85"></i>
              <span class="md-helper-text">برای مثال : test_username</span>
              <span class="md-error" v-show="!$v.username.required">لطفا نام نقش را وارد کنید</span>
            </md-field>
          </div>
        </div>

        <md-field :class="getValidationClass('address')">
          <label>آدرس</label>
          <md-input v-model="address" :maxlength="$v.address.$params.maxLength.max" />
          <i class="md-icon tim-icons icon-email-85"></i>
          <span class="md-helper-text">برای مثال : خیابان آزادی ، آزادی ۵۲ ، پلاک ۲۳</span>
          <span class="md-error" v-show="!$v.address.required">لطفا آدرس را وارد کنید</span>
        </md-field>

        <div class="row">
          <div class="col-md-6">
            <md-field :class="getValidationClass('national_code')">
              <label>کد ملی</label>
              <md-input v-model="national_code" :maxlength="$v.national_code.$params.maxLength.max" />
              <i class="md-icon tim-icons icon-tag"></i>
              <span class="md-helper-text">برای مثال : ۰۹۲۱۰۲۰۳۴۵</span>
              <span class="md-error" v-show="!$v.national_code.required">لطفا آدرس کاربر را وارد کنید</span>
            </md-field>
          </div>

          <div class="col-md-6">
            <md-field :class="getValidationClass('gender')">
              <label>جنسیت</label>
              <md-select v-model="gender" >
                <md-option value="false">مونث</md-option>
                <md-option value="true">مذکر</md-option>
                <md-option value="null">نا مشخص</md-option>
              </md-select>
              <i class="md-icon tim-icons icon-satisfied"></i>
              <span class="md-helper-text">جنسیت خود را مشخص کنید</span>
            </md-field>
          </div>
        </div>

        <base-input label="نقش ها" :style="{ marginTop: '25px' }">
          <el-transfer
            v-model="roles"
            filterable
            :filter-method="filterMethod"
            filter-placeholder="جستجو نقش ..."
            :titles="['موجود', 'اعطا شده']"
            :button-texts="['گرفتن نقش', 'اعطاء نقش']"
            @change="handleRolePermissions"
            :format="{
              noChecked: '${total}',
              hasChecked: '${checked}/${total}'
            }"
            :data="$store.state.user.role.map(value => { return { key: value.id, label: value.display_name, description: value.description } } )">

            <template #default="props">
              <span>
                <p>{{ props.option.label }}</p>
                <span class="text-muted">{{ props.option.description }}</span>
              </span>
            </template>

          </el-transfer>
          <span class="text-muted">نقش های مورد نظر خود انتخاب و آن ها را اعطاء کنید</span>
        </base-input>

        <base-input label="سطح دسترسی ها" :style="{ marginTop: '25px' }">
          <el-transfer
            v-model="permissions"
            filterable
            :filter-method="filterMethod"
            filter-placeholder="جستجو دسترسی ..."
            :titles="['موجود', 'اعطا شده']"
            :button-texts="['گرفتن دسترسی', 'اعطاء دسترسی']"
            :format="{
              noChecked: '${total}',
              hasChecked: '${checked}/${total}'
            }"
            :data="$store.state.permissions_list.map(value => { return { key: value.id, label: value.display_name, description: value.description, disabled: value.disabled } } )">

            <template #default="props">
              <span>
                <p>{{ props.option.label }}</p>
                <span class="text-muted">{{ props.option.description }}</span>
              </span>
            </template>

          </el-transfer>
          <span class="text-muted">سطح دسترسی های مورد نظر خود انتخاب و آن ها را اعطاء کنید</span>
        </base-input>
      </template>
    </datatable>

    <md-dialog :md-active.sync="is_open" class="text-right" dir="rtl">
      <md-dialog-title>
        <h2 class="modal-title">
          مدیریت اعتبار {{ selected_user.full_name && selected_user.full_name.trim() ? selected_user.full_name : selected_user.username }}
        </h2>
        <p>از طریق فرم زیر میتوانید میزان اعتبار کاربر را افزایش یا کاهش دهید</p>
      </md-dialog-title>

      <div class="md-dialog-content">
        <div class="p-2">
          <form @submit.prevent>
            <md-field>
              <label>تعداد ملک</label>
              <md-input type="number" v-model="form_data.count" max="10000" />
              <i class="md-icon tim-icons icon-tap-02"></i>
              <span class="md-helper-text" v-if="form_data.count">{{ form_data.count | numToPersian }} ملک</span>
              <span class="md-helper-text" v-else>تعداد ملک های قابل بازدید توسط کاربر</span>
            </md-field>
            <br/>

            <md-field>
              <label>تعداد آگهی</label>
              <md-input type="number" v-model="form_data.registered" max="1000" />
              <i class="md-icon tim-icons icon-tap-02"></i>
              <span class="md-helper-text"></span>
              <span class="md-helper-text" v-if="form_data.registered">{{ form_data.registered | numToPersian }} آگهی</span>
              <span class="md-helper-text" v-else>تعداد ملک های قابل ثبت توسط کاربر</span>
            </md-field>
            <br/>

            <md-field>
              <label>تعداد روز</label>
              <md-input type="number" v-model="form_data.days" max="100" />
              <i class="md-icon tim-icons icon-watch-time"></i>
              <span class="md-helper-text">تعداد روز های قابل استفاده از پلن مالی</span>
            </md-field>
            <br/>

            <base-input class="w-100" label="نوع مدیریت اعتبار">
              <el-switch
                dir="ltr"
                class="d-flex justify-content-center"
                v-model="form_data.type"
                active-text="افزایش اعتبار"
                active-color="#ff8d72"
                inactive-text="کاهش اعتبار">
              </el-switch>
              <small slot="helperText" id="emailHelp" class="form-text text-muted">با انتخاب گزینه بالا مشخص میکنید که اعتبار حساب افزایش پیدا کند یا کاهش</small>
            </base-input>
          </form>
        </div>
      </div>

      <md-dialog-actions>
        <base-button
          class="ml-2"
          type="danger"
          size="sm"
          @click="is_open = false"
        >
          <i class="tim-icons icon-simple-remove"></i>
          لغو
        </base-button>
        
        <base-button
          size="sm" 
          :loading="attr('is_mutation_loading')"
          type="success"
          @click="increaseCredit"
        >
          <transition name="fade" mode="out-in">
            <semipolar-spinner
              :animation-duration="2000"
              :size="17"
              color="#fff"
              v-if="attr('is_mutation_loading')"
            />
            <span v-else class="pull-right ml-2" >
              <i class="tim-icons icon-simple-add"></i>
            </span>
          </transition>
          تغییر اعتبار
        </base-button>
      </md-dialog-actions>
    </md-dialog>
    
    <md-dialog :md-active.sync="is_show_sessions" class="text-right" dir="rtl">
      <md-dialog-title>
        <h2 class="modal-title">
          نشست های {{ selected_user.full_name && selected_user.full_name.trim() ? selected_user.full_name : selected_user.username }}
        </h2>
        <p>از طریق فرم زیر میتوانید نشست های فعال این حساب را مشاهده کنید</p>
      </md-dialog-title>

      <div class="md-dialog-content">
        <div class="p-2">
          <base-table
            class="w-100"
            :tableData="sessions"
            :has_animation="false"
            :type="type"
            :group="group"
            :label="label"
            :fields="[
              {
                field: 'os',
                label: 'سیستم عامل',
                icon: 'icon-badge',
              }, {
                field: 'device',
                label: 'مرورگر/دیوایس',
                icon: 'icon-paper',
              }, {
                field: 'created_at',
                label: 'تاریخ قفل شدن',
                icon: 'icon-refresh-02',
              }
            ]"
            :methods="{}"
            :canSelect="false"
            :canCreate="false"
            :has_loaded="true"
            :has_times="false"
            :has_operation="false"
          >
            <template #os-body="props">
              {{ props.row.os.name }} {{ props.row.os.version }}
            </template>
            
            <template #device-body="props">
              <span v-if="props.row.device.type">
                {{ props.row.device.type }} {{ props.row.device.vendor }} {{ props.row.device.model }}
              </span>
              <span v-else>
                {{ props.row.browser.name }} {{ props.row.browser.version }}
              </span>
            </template>

            <template #created_at-body="props">
              {{ props.row.created_at | ago }}
            </template>
          </base-table>
        </div>
      </div>

      <md-dialog-actions>
        <base-button
          class="ml-2"
          type="danger"
          size="sm"
          @click="is_show_sessions = false"
        >
          <i class="tim-icons icon-simple-remove"></i>
          بستن
        </base-button>
      </md-dialog-actions>
    </md-dialog>
  </div>
</template>

<script>
import Datatable from '../../components/BaseDatatable.vue'
import BaseTable from '../../components/BaseTable'
import ICountUp from 'vue-countup-v2'

import createMixin from '../../mixins/createMixin'
import initDatatable from '../../mixins/initDatatable'
import parser from 'ua-parser-js'

import Binding, { bind } from '../../mixins/binding'

import { validationMixin } from 'vuelidate'
import { required, maxLength } from 'vuelidate/lib/validators'
import _ from 'lodash'
import voca from 'voca'
import filtersHelper from '../../mixins/filtersHelper';
import moment from 'moment'

export default {
  components: {
    Datatable,
    BaseTable,
    ICountUp
  },
  mixins: [
    initDatatable,
    createMixin,
    Binding,
    validationMixin,
    filtersHelper
  ],
  metaInfo: {
    title: 'کاربران',
  },
  validations: {
    first_name : {
      maxLength: maxLength(20)
    },
    last_name : {
      maxLength: maxLength(30)
    },
    address : {
      maxLength: maxLength(250)
    },
    email : {
      required,
      maxLength: maxLength(100)
    },
    username: {
      required,
      maxLength: maxLength(30)
    },
    address: {
      required,
      maxLength: maxLength(100)
    },
    phone_number: {
      required,
    },
    national_code : {
      maxLength: maxLength(10)
    },
  },
  data() {
    return {
      plural: 'users',
      type: 'user',
      group: 'user',
      label: 'کاربر',
      ignoreOperations: ['000000000000'],

      is_open: false,
      selected_user: {
        // 
      },
      form_data: {
        count: 0,
        registered: 0,
        days: 0,
        type: true
      },
      selected_index: null,
      queryFilters: '',
      selected_roles: [],
      sessions: [],
      is_show_sessions: false
    }
  },
  mounted() {
    this.$store.dispatch('getData', {
      group: 'user',
      type: 'role',
      query: `roles {
        data {
          id name display_name permissions { id } created_at updated_at
        }
        total trash chart { month count }
      }`
    })
  },
  methods: {
    filterMethod(query, item) {
      return item.label.toLowerCase().indexOf(query.toLowerCase()) > -1;
    },
    handleRolePermissions(value, direction, movedKeys)
    {
      let allPermissions = this.$store.state.user.form.user.permissions.value

      movedKeys.forEach(value => {
        _.find(this.$store.state.user.role, ['id', value]).permissions.forEach(value => {
          
          _.find(this.$store.state.permissions_list, ['id', value.id]).disabled = direction === 'right' ? true : false

          if ( direction === 'right' )
            allPermissions = [...allPermissions, value.id]
            
          else
            allPermissions = allPermissions.filter(item => item !== value.id)
        })
      })

      if ( direction === 'left' )
      {
        value.forEach(value => {
          _.find(this.$store.state.user.role, ['id', value]).permissions.forEach(value => {
            
            _.find(this.$store.state.permissions_list, ['id', value.id]).disabled = true

            allPermissions = [...allPermissions, value.id]
          })
        })
      }

      this.$store.commit('setFormData', {
        group: this.group,
        type: this.type,
        field: 'permissions',
        value: allPermissions
      })
    },
    getRowData(row)
    {
      this.setAttr('is_query_loading', true)

      return axios.post('/graphql/auth', {
        query: `{
          singleData: ${this.type} (id: "${row.id}") {
            national_code gender
            address phone_number
            permissions { id }
            roles { id }
          }
        }`
      })
    },
    afterEdit(row)
    {
      this.setAttr('is_query_loading', false)

      this.$store.state.permissions_list.forEach(p => p.disabled = false)

      let allPermissions = []

      row.roles.forEach(value => {
        _.find(this.$store.state.user.role, ['id', value.id]).permissions.forEach(value => {

          allPermissions.push(value.id)
          _.find(this.$store.state.permissions_list, ['id', value.id]).disabled = true
        })
      })

      this.$store.commit('setFormData', {
        group: this.group,
        type: this.type,
        field: 'permissions',
        value: [...allPermissions, ...row.permissions.map(i => i.id)]
      })
    },
    isBefore(date) {
      return moment(date).isBefore( moment() )
    },
    validate() {
      return true
    },
    emptyAuthCode(index, row) {
      this.setAttr('is_query_loading', true)

      axios.post(`/graphql/auth`, {
        query: `
        mutation {
          emptyAuthCodeUser(user: "${row.id}") {
            status
            message
          }
        }`
      })
      .then(({data}) => {
        this.setAttr('is_query_loading', false)
        
        this.$notify({
          title: data.data.emptyAuthCodeUser.status === 200 ? 'پاک شد' : 'پاک نشد',
          message: data.data.emptyAuthCodeUser.message,
          timeout: 3000,
          type: data.data.emptyAuthCodeUser.status === 200 ? 'success' : 'danger',
          verticalAlign: 'top',
          horizontalAlign: 'left',
        })
      })
      .catch( error => {
        this.setAttr('is_query_loading', false)
        console.log(error)
      });
    },
    createAccessCode(index, row) {
      this.setAttr('is_query_loading', true)

      axios.post(`/graphql/auth`, {
        query: `
        mutation {
          createAccessCode(user: "${row.id}") {
            code
            status
            message
          }
        }`
      })
      .then(({data}) => {
        this.setAttr('is_query_loading', false)

        this.$swal.fire({
          title: data.data.createAccessCode.status === 200 ? `کد دسترسی : ${data.data.createAccessCode.code}` : null,
          text: data.data.createAccessCode.message,
          type: data.data.createAccessCode.status === 200 ? 'success' : 'error',
          showConfirmButton: true,
          confirmButtonText: 'باشه'
        })
      })
      .catch( error => {
        this.setAttr('is_query_loading', false)
        console.log(error)
      });
    },
    resetPassword(index, row) {
      this.$swal.fire({
        input: 'password',
        inputPlaceholder: 'رمز عبور',
        showCancelButton: true,
        title: `لطفا رمز عبور مورد نظر خود را وارد کنید`,
        type: 'warning',
        confirmButtonColor: 'linear-gradient(to bottom left, #00f2c3, #0098f0)',
        confirmButtonColor: '#0098f0',
        cancelButtonColor: '#d33',
        confirmButtonText: 'تغییر رمز عبور',
        cancelButtonText: 'منصرف شدم'
      })
      .then(({value}) => {
        if (value) {
          this.setAttr('is_query_loading', true)

          axios.post(`/graphql/auth`, {
            query: `
            mutation {
              updateUserPassword (
                user: "${row.id}",
                password: "${value}",
                password_confirmation: "${value}"
              ) {
                status
                message
              }
            }`
          })
          .then(({data}) => {
            this.setAttr('is_query_loading', false)

            if ( data.data.updateUserPassword.status === 400 )
            {
              return this.$swal.fire({
                title: 'تغییری نکرد',
                text: data.data.updateUserPassword.message,
                type: 'error',
                timer: 2000,
                showConfirmButton: false
              })
            }

            this.$swal.fire({
              title: 'تغییر کرد',
              text: 'رمز عبور کاربر مورد نظر با موفقیت بروزرسانی شد',
              type: 'success',
              timer: 2000,
              showConfirmButton: false
            })
          })
          .catch( error => {
            this.setAttr('is_query_loading', false)
            
            this.errorResolver(error)
          });
        }
      })
    },
    seeSessions(index, row) {
      this.setAttr('is_query_loading', true)

      axios.post(`/graphql/auth`, {
        query: `{
          user(id: "${row.id}") {
            sessions {
              id
              user_agent
              created_at
            }
          }
        }`
      })
      .then(({data}) => {
        this.sessions = data.data.user.sessions.map(i => {
          
          const ua = parser(i.user_agent)

          return {
            id: i.id,
            browser: ua.browser,
            device: ua.device,
            os: ua.os,
            created_at: i.created_at
          }
        })

        this.setAttr('is_query_loading', false)
        
        console.log( this.sessions )

        this.selected_user = row
        this.is_show_sessions = true
      })
      .catch( error => {
        this.setAttr('is_query_loading', false)
        console.log(error)
      });
    },
    accept(index, status) {
      this.setAttr('is_query_loading', true)

      axios.post(`/graphql/auth`, {
        query: `
          mutation {
            ${ voca.camelCase(`active ${this.type}`) } (id: "${this.data()[index].id}", status: ${status}) {
              status
              message
            }
          }
        `
      })
      .then(({data}) => {
        this.setAttr('is_query_loading', false)

        this.data()[index].can_has_member = status
        
        this.$notify({
          title: status ? 'تایید شد' : 'رد شد',
          message: `${this.label} مورد نظر با موفقیت ${ status ? 'تایید شد' : 'رد شد' } :)`,
          timeout: 1500,
          type: status ? 'success' : 'danger',
          verticalAlign: 'top',
          horizontalAlign: 'left',
        })
      })
      .catch( error => {
        this.setAttr('is_query_loading', false)
        console.log(error)
      });
    },
    accept_multiple() {
      this.$swal.fire({
        title: `برای ${this.acceptType ? 'تایید' : 'رد'} ${this.label} های انتخاب شده مطمئن هستید ؟`,
        text: `در صورت ${this.acceptType ? 'تایید' : 'رد'} کردن ، تمامی ${this.label} های انتخاب شده به حالت ${this.acceptType ? 'تایید' : 'رد'} شده درخواهند آمد !`,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'linear-gradient(to bottom left, #00f2c3, #0098f0)',
        confirmButtonColor: '#0098f0',
        cancelButtonColor: '#d33',
        confirmButtonText: 'بله ، مطمئنم !',
        cancelButtonText: 'نه ، اشتباه شده'
      }).then((result) => {
        if (result.value) {
          this.setAttr('is_query_loading', true)

          var ids = [];
          this.attr('selected_items').forEach( item => {
            ids = [...ids, this.data()[item].id]
          })

          axios.post(`/graphql/auth`, {
            query: `
              mutation {
                ${ voca.camelCase(`active ${this.type}`) } (ids: [${'"' + ids.join('", "') + '"'}], status: ${this.acceptType}) {
                  status
                  message
                }
              }
            `
          })
          .then(({data})=> {
            this.setAttr('is_query_loading', false)

            let used_status = this.acceptType;
            this.attr('selected_items').forEach(index => {
              this.data()[index].can_has_member = used_status
            });

            this.setAttr('selected_items', [], true)
            this.$refs.datatable.selected_items = [];

            this.$swal.fire({
              title: `${this.acceptType ? 'تایید' : 'رد'} شدند`,
              text: `${this.label} هایی که انتخاب کردید با موفقیت ${this.acceptType ? 'تایید' : 'رد'} شدند :)`,
              type: 'success',
              timer: 1000,
              showConfirmButton: false,
            })

          })
          .catch(error => {
            this.setAttr('is_query_loading', false)

            if (error.response) {
              this.$swal.fire({
                title: 'خطایی رخ داد !',
                text: error.response.data.message,
                type: 'error',
                timer: 5000,
                confirmButtonText: 'بسیار خب :('
              })
            } else {
              console.log(error)
            }
          });
        }
      })
    },
    manageCredit(index, row)
    {
      this.selected_user = row
      this.selected_index = index

      this.form_data = {
        count: 0,
        registered: 0,
        days: 0,
        type: true
      }

      this.is_open = true
    },
    increaseCredit()
    {
      axios.post('/graphql/auth', {
        query: `mutation {
          changeCreditUser(
            id: "${this.selected_user.id}",
            count: ${this.form_data.count},
            registered: ${this.form_data.registered},
            days: ${this.form_data.days},
            type: ${this.form_data.type}
          ) {
            status
            message
          }
        }`
      })
      .then(({data}) => {

        if ( data.data.changeCreditUser.status === 400 ) {
          return this.$swal.fire({
            title: 'خطایی رخ داد',
            text: data.data.changeCreditUser.message,
            type: 'error',
            showConfirmButton: false,
            timer: 2000,
          });
        }

        if ( this.form_data.type )
        {
          this.selected_user.remaining_hits_count = this.selected_user.remaining_hits_count * 1 + this.form_data.count * 1
          this.selected_user.remaining_registered_count = this.selected_user.remaining_registered_count * 1 + this.form_data.registered * 1

          this.selected_user.validity_end_at = moment(this.selected_user.validity_end_at)
            .locale('en')
            .add(this.form_data.days, 'days')
            .format('YYYY-MM-DD hh:mm:ss')
        }
        else
        {
          this.selected_user.remaining_hits_count = this.selected_user.remaining_hits_count * 1 - this.form_data.count * 1
          this.selected_user.remaining_registered_count = this.selected_user.remaining_registered_count * 1 - this.form_data.registered * 1
          this.selected_user.validity_end_at = moment(this.selected_user.validity_end_at)
            .locale('en')
            .subtract(this.form_data.days, 'days')
            .format('YYYY-MM-DD hh:mm:ss')
        }

        this.$swal.fire({
          title: this.form_data.type ? 'اضافه شد' : 'کاسته شد',
          text: data.data.changeCreditUser.message,
          type: 'success',
          showConfirmButton: false,
          timer: 1000,
        })

        this.is_open = false
      })
    }
  },
  computed: {
    first_name: bind('first_name'),
    last_name: bind('last_name'),
    avatar: bind('avatar'),
    address: bind('address'),
    email: bind('email'),
    username: bind('username'),
    address: bind('address'),
    phone_number: bind('phone_number'),
    national_code: bind('national_code'),
    gender: bind('gender'),
    roles: bind('roles'),
    permissions: bind('permissions'),

    acceptType() {
      var accept_count = 0, rejact_count = 0;

      this.attr('selected_items').forEach(index => {
        if ( this.data()[index].can_has_member )
          ++accept_count
        else
          ++rejact_count
      });

      return accept_count <= rejact_count;
    },
    getFields() {
      let fields = [
        {
          field: 'avatar',
          label: 'آواتار',
          icon: 'icon-image-02'
        }, {
          field: 'info',
          label: 'اطلاعات',
          icon: 'icon-badge'
        }, {
          field: 'roles',
          label: 'نقش ها',
          icon: 'icon-tap-02'
        }
      ]

      if ( !this.can('active-user') ) {
        fields.push({
          field: 'status',
          label: 'امکان عضوگیری',
          icon: 'icon-refresh-02',
        })
      }

      if ( !this.can('see-credit-user') ) {
        fields.push({
          field: 'plan',
          label: 'پلن مالی',
          icon: 'icon-email-85'
        })
      }

      return fields
    },
    allQuery() {
      return `
        avatar { id file_name thumb medium }
        first_name
        last_name
        full_name
        username
        email
        phone_number
        visited_estate_count
        remaining_hits_count
        remaining_registered_count
        validity_end_at
        can_has_member
        roles {
          id
          display_name
        }
      `
    },
  },
  watch: {
    'selected_roles': function(newVal, oldVal) {

      this.queryFilters = `roles: [${newVal.join(',')}]`

      if ( this.filter('query') )
        this.handleSearch( this.filter('query') )
    
      else
        this.handleSearch()
    }
  },
  beforeRouteLeave (to, from, next) {
    this.$refs.datatable.closePanel()

    setTimeout( () => next(), 700);
  },
}
</script>

<style>
.el-button.el-transfer__button:not(.is-disabled) {
  background-color: #ff8d72;
  border-color: #f56c6c;
}
.el-transfer-panel__list .el-checkbox__label {
  height: 100%;
}

.user-operations-dropdown {
  width: 30px;
}
.user-operations-dropdown button {
  border-radius: 4px;
  width: 30px;
  height: 30px;
  padding: 8px !important;
}
.user-operations-dropdown .dropdown-menu {
  min-height: 0px !important;
  width: 200px;
  text-align: right;
  position: absolute !important;
  bottom: 0px !important;
  left: 40px !important;
  border-radius: 10px !important;
  box-shadow: 0px 2px 15px -4px !important;
  padding: 10px 0px !important;
}
.user-operations-dropdown .dropdown-menu div {
  transition: background 200ms;
}
.user-operations-dropdown .dropdown-menu div:hover {
  cursor: pointer;
  background: #eaeaea;
}
</style>