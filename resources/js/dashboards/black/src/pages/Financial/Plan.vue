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
    
    ref="datatable">
    
    <template #features-body="slotProps">
      <table>
        <tbody>
          <tr>
            <td>
              <i class="tim-icons icon-tap-02"></i>
            </td>
            <td class="text-right">
              {{ slotProps.row.visited_estate_count }} ملک
            </td>
          </tr>
          <tr>
            <td>
              <i class="tim-icons icon-time-alarm"></i>
            </td>
            <td class="text-right">
              {{ slotProps.row.credit_days_count }} روز
            </td>
          </tr>
        </tbody>
      </table>
    </template>
    
    <template #price-body="slotProps">
      <span class="badge badge-primary color-badge p-2" :style="{ background: slotProps.row.color }" v-if="slotProps.row.color">
        {{ slotProps.row.price | comma }} تومان
      </span>
      <span class="badge p-2" v-else>
        {{ slotProps.row.price | comma }} تومان
      </span>
    </template>

    <template slot="modal">
      <div class="row">
        <div class="col-md-8">
          <md-field :class="getValidationClass('title')">
            <label>عنوان پلن مالی</label>
            <md-input v-model="title" :maxlength="$v.title.$params.maxLength.max" />
            <i class="md-icon tim-icons icon-caps-small"></i>
            <span class="md-helper-text">برای مثال : طرح طلایی</span>
            <span class="md-error" v-show="!$v.title.required">لطفا عنوان را وارد کنید</span>
          </md-field>
        </div>

        <div class="col-md-4">
          <md-field :class="getValidationClass('color')">
            <el-color-picker v-model="color"></el-color-picker>
            <span class="md-helper-text pt-2">رنگ پلن مالی</span>
          </md-field>
        </div>
      </div>
      <br/>
      <md-field :class="getValidationClass('description')">
        <label>توضیحات پلن مالی</label>
        <md-textarea v-model="description" md-autogrow :maxlength="$v.description.$params.maxLength.max"></md-textarea>
        <i class="md-icon tim-icons icon-paper"></i>
        <span class="md-helper-text">توضیحی مختصر درباره پلن مالی</span>
      </md-field>
      <br/>
      <md-field :class="getValidationClass('visited_estate_count')">
        <label>تعداد ملک</label>
        <md-input type="number" v-model="visited_estate_count" />
        <span class="md-suffix ml-2">ملک</span>
        <i class="md-icon tim-icons icon-tap-02"></i>
        <span class="md-helper-text">تعداد ملک های قابل مشاهده توسط خریداران</span>
        <span class="md-error" v-show="!$v.visited_estate_count.required">لطفا حداکثر تعداد ملک را وارد کنید</span>
      </md-field>
      <br/>
      <md-field :class="getValidationClass('registered_estate_count')">
        <label>تعداد آگهی</label>
        <md-input type="number" v-model="registered_estate_count" />
        <span class="md-suffix ml-2">آگهی</span>
        <i class="md-icon tim-icons icon-tap-02"></i>
        <span class="md-helper-text">تعداد ملک های قابل ثبت توسط خریداران</span>
        <span class="md-error" v-show="!$v.registered_estate_count.required">لطفا حداکثر تعداد اگهی را وارد کنید</span>
      </md-field>
      <br/>
      <md-field :class="getValidationClass('credit_days_count')">
        <label>محدودیت زمانی</label>
        <md-input type="number" v-model="credit_days_count" />
        <span class="md-suffix ml-2">روز</span>
        <i class="md-icon tim-icons icon-time-alarm"></i>
        <span class="md-helper-text">حداکثر زمان قابل استفاده از پلن مالی</span>
        <span class="md-error" v-show="!$v.credit_days_count.required">لطفا محدودیت زمانی را وارد کنید</span>
      </md-field>
      <br/>
      <md-field :class="getValidationClass('price')">
        <label>قیمت</label>
        <md-input type="number" v-model="price" />
        <span class="md-suffix ml-2">تومان</span>
        <i class="md-icon tim-icons icon-coins"></i>
        <span class="md-helper-text"></span>
        <span class="md-helper-text" v-if="price">{{ price | numToPersian }} تومان</span>
        <span class="md-helper-text" v-else>هزینه استفاده از پلن مالی</span>
        <span class="md-error" v-show="!$v.credit_days_count.required">لطفا قیمت وارد کنید</span>
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
import filtersHelper from '../../mixins/filtersHelper';

export default {
  components: {
    Datatable
  },
  mixins: [
    initDatatable,
    createMixin,
    validationMixin,
    filtersHelper,
    Binding
  ],
  metaInfo: {
    title: 'پلن های مالی',
  },
  data() {
    return {
      plural: 'plans',
      type: 'plan',
      group: 'financial',
      label: 'پلن مالی',
    }
  },
  validations: {
    title: {
      required,
      maxLength: maxLength(50)
    },
    description: {
      maxLength: maxLength(250)
    },
    price: {
      required
    },
    visited_estate_count: {
      required
    },
    registered_estate_count: {
      required
    },
    credit_days_count: {
      required
    },
  },
  computed: {
    title: bind('title'),
    color: bind('color'),
    description: bind('description'),
    price: bind('price'),
    visited_estate_count: bind('visited_estate_count'),
    registered_estate_count: bind('registered_estate_count'),
    credit_days_count: bind('credit_days_count'),

    getFields() {
      return [
        {
          field: 'title',
          label: 'عنوان پلن',
          icon: 'icon-caps-small'
        }, {
          field: 'description',
          label: 'توضیحات پلن',
          icon: 'icon-paper'
        }, {
          field: 'features',
          label: 'ویژگی های پلن',
          icon: 'icon-molecule-40'
        }, {
          field: 'price',
          label: 'هزینه پلن',
          icon: 'icon-coins'
        }
      ]
    },
    allQuery() {
      return `
        title
        description
        visited_estate_count
        registered_estate_count
        credit_days_count
        price
        color
      `
    }
  },
  beforeRouteLeave (to, from, next) {
    this.$refs.datatable.closePanel()

    setTimeout( () => next(), 700);
  },
}
</script>