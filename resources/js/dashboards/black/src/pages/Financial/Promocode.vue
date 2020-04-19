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
    
    <template #cost-body="slotProps">
      {{ slotProps.row.cost | comma }} %
    </template>

    <template #expired_at-body="slotProps">
      <el-tooltip v-if="slotProps.row.expired_at" :content="slotProps.row.expired_at | expired" placement="left">
        <p><i class="tim-icons icon-check-2"></i>{{ slotProps.row.expired_at | ago }}</p>
      </el-tooltip>

      <span v-else class="badge badge-success">
        بدون انقضاء
      </span>
    </template>


    <template #using_data-body="slotProps">
      <el-popover
        v-if="slotProps.row.used_count < slotProps.row.quantity"
        placement="top-start"
        title="شرح استفاده"
        width="200"
        trigger="hover"
        :content="`از ${slotProps.row.quantity} عدد ، تاکنون ${slotProps.row.used_count} بار استفاده شده است`">
        <el-progress
          type="dashboard"
          :width="70"
          slot="reference"
          :percentage="Math.round( slotProps.row.used_count / slotProps.row.quantity * 100 )"
          :color=" [
            {color: '#f56c6c', percentage: 20},
            {color: '#e6a23c', percentage: 40},
            {color: '#5cb87a', percentage: 60},
            {color: '#1989fa', percentage: 80},
            {color: '#6f7ad3', percentage: 100}
          ]"
        ></el-progress>
      </el-popover>

      <el-progress
        v-else
        type="dashboard"
        :width="70"
        :percentage="100"
        status="success"
      ></el-progress>

      <table class="table-p-0" v-if="false">
        <tbody>
          <tr v-if="slotProps.row.quantity">
            <td>
              تعداد
            </td>
            <td>
              {{ slotProps.row.quantity }}
            </td>
          </tr>
          <tr>
            <td>
              میزان استفاده
            </td>
            <td>
              {{ slotProps.row.used_count }}
            </td>
          </tr>
        </tbody>
      </table>
    </template>

    <template slot="modal">
      <md-field :class="getValidationClass('code')">
        <label>کد تخفیف</label>
        <md-input v-model="code" :maxlength="$v.code.$params.maxLength.max" />
        <i class="md-icon tim-icons icon-caps-small"></i>
        <span class="md-helper-text">برای مثال : summer_offer_2020</span>
        <span class="md-error" v-show="!$v.code.required">لطفا کد تخفیف را وارد کنید</span>
      </md-field>
      <br/>
      <md-field :class="getValidationClass('title')">
        <label>عنوان واگذاری</label>
        <md-input v-model="title" :maxlength="$v.title.$params.maxLength.max" />
        <i class="md-icon tim-icons icon-caps-small"></i>
        <span class="md-helper-text">برای مثال : رهن و اجاره</span>
        <span class="md-error" v-show="!$v.title.required">لطفا عنوان واگذاری را وارد کنید</span>
      </md-field>
      <br/>
      <md-field :class="getValidationClass('description')">
        <label>توضیحات واگذاری</label>
        <md-textarea v-model="description" md-autogrow :maxlength="$v.description.$params.maxLength.max"></md-textarea>
        <i class="md-icon tim-icons icon-paper"></i>
        <span class="md-helper-text">توضیحی مختصر درباره واگذاری</span>
      </md-field>
      <br/>
      <md-field :class="getValidationClass('quantity')">
        <label>تعداد</label>
        <md-input type="number" v-model="quantity" />
        <span class="md-suffix ml-2">دفعه</span>
        <i class="md-icon tim-icons icon-chart-pie-36"></i>
        <span class="md-helper-text">حداکثر تعداد دفعات قابل استفاده از کد تخفیف</span>
      </md-field>
      <br/>
      <md-field :class="getValidationClass('cost')">
        <label>ارزش</label>
        <md-input type="number" v-model="cost" />
        <span class="md-suffix ml-2">درصد</span>
        <i class="md-icon tim-icons icon-coins"></i>
        <span class="md-helper-text">ارزش کد تخفیف بر حسب درصد</span>
        <span class="md-error" v-show="!$v.cost.required">لطفا ارزش کد تخفیف را وارد کنید</span>
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
        <span class="md-helper-text">حداکثر تاریخ قابل استفاده از این کد تخفیف</span>
      </md-field>
      <br/>
      <md-field>
        <label>پلن ها</label>
        <md-select v-model="plans" multiple>
          <md-option v-for="item in $store.state.financial.plan" :key="item.id" :value="item.id">{{ item.title }}</md-option>
        </md-select>
        <i class="md-icon tim-icons icon-cloud-download-93"></i>
        <span class="md-helper-text">پلن های مالی قابل سفارش با این کد تخفیف</span>
      </md-field>
      <br/>
    </template>

  </datatable>
</template>

<script>
import Datatable from '../../components/BaseDatatable.vue'
import VuePersianDatetimePicker from 'vue-persian-datetime-picker'

import createMixin from '../../mixins/createMixin'
import initDatatable from '../../mixins/initDatatable'
import Binding, { bind } from '../../mixins/binding'
import { validationMixin } from 'vuelidate'
import { required, maxLength } from 'vuelidate/lib/validators'
import filtersHelper from '../../mixins/filtersHelper'

export default {
  components: {
    Datatable,
    datePicker: VuePersianDatetimePicker
  },
  mixins: [
    initDatatable,
    filtersHelper,
    createMixin,
    validationMixin,
    Binding
  ],
  metaInfo: {
    title: 'کد تخفیف ها',
  },
  data() {
    return {
      plural: 'promocodes',
      type: 'promocode',
      group: 'financial',
      label: 'کدتخفیف',
    }
  },
  validations: {
    code: {
      required,
      maxLength: maxLength(30)
    },
    title: {
      required,
      maxLength: maxLength(50)
    },
    description: {
      maxLength: maxLength(255)
    },
    cost: {
      required
    }
  },
  computed: {
    code: bind('code'),
    title: bind('title'),
    description: bind('description'),
    cost: bind('cost'),
    quantity: bind('quantity'),
    expired_at: bind('expired_at'),
    plans: bind('plans'),

    getFields() {
      return [
        {
          field: 'code',
          label: 'کد تخفیف',
          icon: 'icon-tag'
        }, {
          field: 'title',
          label: 'اطلاعات کد تخفیف',
          icon: 'icon-caps-small'
        }, {
          field: 'using_data',
          label: 'میزان استفاده',
          icon: 'icon-money-coins'
        }, {
          field: 'cost',
          label: 'ارزش',
          icon: 'icon-coins'
        }, {
          field: 'expired_at',
          label: 'تاریخ انقضاء',
          icon: 'icon-time-alarm'
        }
      ]
    },
    allQuery() {
      return `
        code
        title
        description
        cost
        quantity
        used_count
        expired_at
        plans { id }
      `
    }
  },
  mounted() {
    this.$store.dispatch('getData', {
      group: 'financial',
      type: 'plan',
      query: `plans(per_page: 20) {
        data {
          id title description
          visited_estate_count registered_estate_count credit_days_count
          price color created_at updated_at
        }
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