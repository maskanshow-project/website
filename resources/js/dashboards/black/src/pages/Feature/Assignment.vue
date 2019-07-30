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
    
    <template #color-body="slotProps">
      <span class="badge badge-primary color-badge p-2" :style="{ background: slotProps.row.color }" v-if="slotProps.row.color">
        {{ slotProps.row.color }}
      </span>
    </template>

    <template #prices-body="slotProps">
      <table class="table-p-0">
        <tbody>
          <tr>
            <td>
              <i class="tim-icons icon-bank"></i>
            </td>
            <td class="text-right" :class="slotProps.row.has_sales_price ? 'text-success' : 'text-danger'">
              {{ slotProps.row.has_sales_price ? 'دارای' : 'بدون' }} قیمت کل
            </td>
          </tr>
          <tr>
            <td>
              <i class="tim-icons icon-key-25"></i>
            </td>
            <td class="text-right" :class="slotProps.row.has_mortgage_price ? 'text-success' : 'text-danger'">
              {{ slotProps.row.has_mortgage_price ? 'دارای' : 'بدون' }} قیمت رهن
            </td>
          </tr>
          <tr>
            <td>
              <i class="tim-icons icon-refresh-02"></i>
            </td>
            <td class="text-right" :class="slotProps.row.has_rental_price ? 'text-success' : 'text-danger'">
              {{ slotProps.row.has_rental_price ? 'دارای' : 'بدون' }} قیمت اجاره
            </td>
          </tr>
        </tbody>
      </table>
    </template>
    
    <template slot="modal">
      <div class="row">
        <div class="col-md-8">
          <md-field :class="getValidationClass('title')">
            <label>عنوان واگذاری</label>
            <md-input v-model="title" :maxlength="$v.title.$params.maxLength.max" />
            <i class="md-icon tim-icons icon-caps-small"></i>
            <span class="md-helper-text">برای مثال : رهن و اجاره</span>
            <span class="md-error" v-show="!$v.title.required">لطفا عنوان واگذاری را وارد کنید</span>
          </md-field>
        </div>

        <div class="col-md-4">
          <md-field :class="getValidationClass('color')">
            <el-color-picker v-model="color"></el-color-picker>
            <span class="md-helper-text pt-2">رنگ لیبل واگذاری</span>
          </md-field>
        </div>
      </div>
      <br/>
      <md-field :class="getValidationClass('description')">
        <label>توضیحات واگذاری</label>
        <md-textarea v-model="description" md-autogrow :maxlength="$v.description.$params.maxLength.max"></md-textarea>
        <i class="md-icon tim-icons icon-paper"></i>
        <span class="md-helper-text">توضیحی مختصر درباره واگذاری</span>
      </md-field>
      <br/>
      <div class="row">
        <base-input class="w-100" label="وضعیت قیمت کل">
          <el-switch
            dir="ltr"
            class="d-flex justify-content-center"
            v-model="has_sales_price"
            active-text="دارای قیمت کل"
            active-color="#ff8d72"
            inactive-text="بدون قیمت کل">
          </el-switch>
          <small slot="helperText" id="emailHelp" class="form-text text-muted">مشخص کننده وضعیت قیمت کل ، مثلا برای نوع واگذاری خرید و فروش</small>
        </base-input>
        <br/>

        <base-input class="w-100" label="وضعیت قیمت رهن">
          <el-switch
            dir="ltr"
            class="d-flex justify-content-center"
            v-model="has_mortgage_price"
            active-text="دارای قیمت رهن"
            active-color="#ff8d72"
            inactive-text="بدون قیمت رهن">
          </el-switch>
          <small slot="helperText" id="emailHelp" class="form-text text-muted">مشخص کننده وضعیت قیمت رهن ، مثلا برای نوع واگذاری رهن کامل و رهن و اجاره</small>
        </base-input>
        <br/>

        <base-input class="w-100" label="وضعیت قیمت اجاره">
          <el-switch
            dir="ltr"
            class="d-flex justify-content-center"
            v-model="has_rental_price"
            active-text="دارای قیمت اجاره"
            active-color="#ff8d72"
            inactive-text="بدون قیمت اجاره">
          </el-switch>
          <small slot="helperText" id="emailHelp" class="form-text text-muted">مشخص کننده وضعیت قیمت اجاره ، مثلا برای نوع واگذاری رهن و اجاره یا اجاره سوییت</small>
        </base-input>
      </div>
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

export default {
  components: {
    Datatable
  },
  mixins: [
    initDatatable,
    createMixin,
    validationMixin,
    Binding
  ],
  metaInfo: {
    title: 'انواع واگذاری ها',
  },
  data() {
    return {
      plural: 'assignments',
      type: 'assignment',
      group: 'feature',
      label: 'واگذاری',
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
  },
  computed: {
    title: bind('title'),
    description: bind('description'),
    color: bind('color'),
    has_sales_price: bind('has_sales_price'),
    has_mortgage_price: bind('has_mortgage_price'),
    has_rental_price: bind('has_rental_price'),

    getFields() {
      return [
        {
          field: 'title',
          label: 'عنوان واگذاری',
          icon: 'icon-caps-small'
        }, {
          field: 'description',
          label: 'توضیحات واگذاری',
          icon: 'icon-paper'
        }, {
          field: 'prices',
          label: 'وضعیت قیمت ها',
          icon: 'icon-money-coins'
        }, {
          field: 'color',
          label: 'رنگ',
          icon: 'icon-palette'
        }
      ]
    },
    allQuery() {
      return `color title description has_sales_price has_mortgage_price has_rental_price created_at`
    }
  },
  beforeRouteLeave (to, from, next) {
    this.$refs.datatable.closePanel()

    setTimeout( () => next(), 700);
  },
}
</script>

<style>
.el-color-picker, .el-color-picker__trigger {
  width: 100%;
}
.el-color-picker__color-inner {
  border-radius: 10px 10px 0px 0px;
  margin-bottom: -1px;
}
.el-color-picker__color {
  border: none;
}
.el-color-picker__trigger {
  padding: 0px;
  height: 39px;
  border: none;
}
</style>
