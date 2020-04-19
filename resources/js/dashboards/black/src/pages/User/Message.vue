<template>
  <datatable
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
    ref="datatable"
  >
    <template #role-body="slotProps">
      <el-popover
        v-if="slotProps.row.role"
        placement="top-end"
        width="300"
        trigger="hover"
        :disabled="typeof slotProps.row.role.display_name === 'string' ? slotProps.row.role.display_name.length <= 20 : false"
        :content="slotProps.row.role.display_name"
      >
        <span
          slot="reference"
          class="badge badge-default ml-1 hvr-grow-shadow hvr-icon-grow">
          <i class="tim-icons icon-bullet-list-67 hvr-icon"></i>
          {{ slotProps.row.role.display_name | truncate(20) }}
        </span>
      </el-popover>

      <span
        v-else
        slot="reference"
        class="badge badge-warning ml-1 hvr-grow-shadow hvr-icon-grow">
        <i class="tim-icons icon-bullet-list-67 hvr-icon"></i>
        نا مشخص
      </span>
    </template>

    <template #message-body="slotProps">
      <el-popover
        placement="top-end"
        width="300"
        trigger="hover"
        :disabled="typeof slotProps.row.message === 'string' ? slotProps.row.message.length <= 50 : false"
        :content="slotProps.row.message"
      >
        <div class="alert p-2 m-0" :class="`alert-${slotProps.row.type}`" slot="reference">{{ slotProps.row.message | truncate(50) }}</div>
      </el-popover>
    </template>

    <template #expired_at-body="slotProps">
      <el-tooltip :content="slotProps.row.expired_at | expired" placement="left">
        <p><i class="tim-icons icon-time-alarm"></i> {{ slotProps.row.expired_at | ago }}</p>
      </el-tooltip>
    </template>

    <template #modal>
      <md-field :class="getValidationClass('title')">
        <label>عنوان پیام</label>
        <md-input v-model="title"/>
        <i class="md-icon tim-icons icon-caps-small"></i>
        <span class="md-helper-text">عنوانی کوتاه درباره موضوع پیام</span>
        <span class="md-error" v-show="!$v.title.required">لطفا عنوان پیام را وارد کنید</span>
      </md-field>
      <br/>

      <md-field :class="getValidationClass('message')">
        <label>متن پیام</label>
        <md-textarea v-model="message" :maxlength="$v.message.$params.maxLength.max"></md-textarea>
        <i class="md-icon tim-icons icon-paper"></i>
        <span class="md-helper-text">متن پیام خود را در این قسمت وارد کنید</span>
        <span class="md-error" v-show="!$v.message.required">لطفا متن پیام را وارد کنید</span>
      </md-field>
      <br/>
      
      <date-picker
        v-model="expired_at"
        element="expired_at-input"
        type="datetime"
        format="YYYY-MM-DD HH:mm:ss"
      />
      <md-field id="expired_at-input" :class="getValidationClass('expired_at')" :style="{ minWidth: '400px' }">
        <label>تاریخ انقضا</label>
        <md-input v-model="expired_at" />
        <i class="md-icon tim-icons icon-time-alarm"></i>
        <span class="md-helper-text">از تاریخ مورد نظر به بعد پیام برای هیچ کاربری نمایش داده نمیشود</span>
      </md-field>
      <br/>
      <md-field>
        <label>نقش مورد نظر</label>
        <md-select v-model="role_id">
          <md-option v-for="item in $store.state.user.role" :key="item.id" :value="item.id">{{ item.display_name }}</md-option>
        </md-select>
        <i class="md-icon tim-icons icon-single-02"></i>
        <span class="md-helper-text">نقش مورد نظر جهت دریافت این پیام را مشخص کنید</span>
      </md-field>
      <br/>
      <md-field>
        <label>نوع پیام</label>
        <md-select v-model="message_type">
          <md-option value="primary">اولیه <span class="badge badge-primary message-type-label"></span></md-option>
          <md-option value="info">اطلاع رسانی <span class="badge badge-info message-type-label"></span></md-option>
          <md-option value="success">موفقیت <span class="badge badge-success message-type-label"></span></md-option>
          <md-option value="danger">اخطار <span class="badge badge-danger message-type-label"></span></md-option>
          <md-option value="warning">هشدار <span class="badge badge-warning message-type-label"></span></md-option>
          <md-option value="default">پیشفرض <span class="badge badge-default message-type-label"></span></md-option>
        </md-select>
        <i class="md-icon tim-icons icon-bulb-63"></i>
        <span class="md-helper-text">نقش مورد نظر جهت دریافت این پیام را مشخص کنید</span>
      </md-field>
    </template>
  </datatable>
</template>

<script>
import Datatable from '../../components/BaseDatatable.vue'
import createMixin from '../../mixins/createMixin'
import initDatatable from '../../mixins/initDatatable'
import VuePersianDatetimePicker from 'vue-persian-datetime-picker'

import Binding, { bind } from '../../mixins/binding'

import { validationMixin } from 'vuelidate'
import { required, maxLength } from 'vuelidate/lib/validators'
import _ from 'lodash'
import filtersHelper from '../../mixins/filtersHelper'
import moment from 'moment-jalaali'

export default {
  components: {
    Datatable,
    datePicker: VuePersianDatetimePicker
  },
  mixins: [
    initDatatable,
    filtersHelper,
    createMixin,
    Binding,
    validationMixin
  ],
  metaInfo: {
    title: 'پیام ها',
  },
  validations: {
    title: {
      required,
    },
    message: {
      required,
      maxLength: maxLength(500)
    },
  },
  data() {
    return {
        plural: 'messages',
        type: 'message',
        group: 'user',
        label: 'پیام',
    }
  },
  computed: {
    title: bind('title'),
    message: bind('message'),
    message_type: bind('type'),
    expired_at: bind('expired_at'),
    role_id: bind('role_id'),

    now() {
      return moment().format('jYYYY-jM-jD')
    },
    getFields() {
      return [
        {
          field: 'title',
          label: 'عنوان',
          icon: 'icon-caps-small'
        }, {
          field: 'message',
          label: 'پیام',
          icon: 'icon-paper'
        }, {
          field: 'role',
          label: 'نقش',
          icon: 'icon-single-02'
        }, {
          field: 'expired_at',
          label: 'انقضا پیام',
          icon: 'icon-time-alarm'
        },
      ]
    },
    allQuery() {
      return `
        title
        message
        type
        role { id display_name }
        expired_at
      `
    },
  },
  methods: {
    afterCreate()
    {
      this.$store.commit('setFormData', {
        group: this.group,
        type: this.type,
        field: 'role_id',
        value: null
      })
    },
    afterEdit(row)
    {
      this.$store.commit('setFormData', {
        group: this.group,
        type: this.type,
        field: 'role_id',
        value: row.role ? row.role.id : null
      })
    },
  },
  mounted() {
    this.$store.dispatch('getData', {
      group: 'user',
      type: 'role',
      query: `roles(per_page: 20) {
        data { id display_name description permissions { id } created_at updated_at }
        total trash chart { month count }
      }`
    })
  },
  beforeRouteLeave (to, from, next) {
    this.$refs.datatable.closePanel()

    setTimeout( () => next(), 700);
  },
}
</script>

<style>
.message-type-label {
  width: 20px;
  height: 20px;
  display: block !important;
  float: left;
}
</style>
