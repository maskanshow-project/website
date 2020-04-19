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
    <template #modal>
      <md-field :class="getValidationClass('phone_number')">
        <label>شماره تلفن</label>
        <md-input v-model="phone_number"/>
        <i class="md-icon tim-icons icon-mobile"></i>
        <span class="md-helper-text">برای مثال : ۰۹۱۲۳۴۵۶۷۸۹</span>
        <span class="md-error" v-show="!$v.phone_number.required">لطفا شماره تلفن را وارد کنید</span>
      </md-field>
      <br/>

      <md-field :class="getValidationClass('description')">
        <label>علت ثبت</label>
        <md-textarea v-model="description" md-autogrow :maxlength="$v.description.$params.maxLength.max"></md-textarea>
        <i class="md-icon tim-icons icon-paper"></i>
        <span class="md-helper-text">توضیحی مختصر درباره علت ثبت به عنوان لیست سیاه</span>
      </md-field>
      <br/>
    </template>

  </datatable>
</template>

<script>
import Datatable from '../../components/BaseDatatable.vue'
import createMixin from '../../mixins/createMixin'
import initDatatable from '../../mixins/initDatatable'

import Binding, { bind } from '../../mixins/binding'

import { validationMixin } from 'vuelidate'
import { required, maxLength } from 'vuelidate/lib/validators'
import _ from 'lodash'

export default {
  components: {
    Datatable
  },
  mixins: [
    initDatatable,
    createMixin,
    Binding,
    validationMixin
  ],
  metaInfo: {
    title: 'لیست سیاه شماره تلفن ها',
  },
  validations: {
    phone_number: {
      required,
    },
    description: {
      maxLength: maxLength(250)
    },
  },
  data() {
    return {
        plural: 'blacklistPhoneNumbers',
        type: 'blacklist_phone_number',
        group: 'user',
        label: 'شماره  لیست سیاه',
    }
  },
  computed: {
    phone_number: bind('phone_number'),
    description: bind('description'),

    getFields() {
      return [
        {
          field: 'phone_number',
          label: 'شماره تلفن',
          icon: 'icon-mobile'
        }, {
          field: 'description',
          label: 'توضیحات',
          icon: 'icon-paper'
        },
      ]
    },
    
    allQuery() {
      return `
        phone_number
        description
      `
    },
  },
  beforeRouteLeave (to, from, next) {
    this.$refs.datatable.closePanel()

    setTimeout( () => next(), 700);
  },
}
</script>