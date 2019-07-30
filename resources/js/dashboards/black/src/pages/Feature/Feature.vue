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
    
    <template #title-body="slotProps">
      <el-popover
        placement="top-end"
        width="300"
        trigger="hover"
        :disabled="typeof slotProps.row.title === 'string' ? slotProps.row.title.length <= 50 : false"
        :content="slotProps.row.title"
      >
        <p slot="reference" class="text-center" :style="{ overflow: 'visible' }">
          <i v-if="slotProps.row.icon" :class="`ml-2 fas fa-${slotProps.row.icon}`"></i>
          {{ slotProps.row.title | truncate(50) }}
        </p>
      </el-popover>
    </template>
    
    <template #is_detailable-body="slotProps">
      <table class="spec-feature-table">
        <tbody>
          <tr class="text-right" :class="slotProps.row.is_detailable ? 'text-success' : 'text-danger'">
            <td>
              <i class="tim-icons" :class="slotProps.row.is_detailable ? 'icon-check-2' : 'icon-simple-remove'"></i>
            </td>
            <td>
              {{ slotProps.row.is_detailable ? 'نمایش' : 'عدم نمایش' }}
            </td>
          </tr>
        </tbody>
      </table>
    </template>

    <template #estate_types-body="slotProps">
      <transition-group name="list">
        <el-popover
          v-for="item in slotProps.row.estate_types.filter( (i, index) => index < 3)"
          :key="item.id"
          placement="top-end"
          width="300"
          trigger="hover"
          :disabled="typeof item.title === 'string' ? item.title.length <= 20 : false"
          :content="item.title"
        >
          <span
            slot="reference"
            class="badge badge-default ml-1 hvr-grow-shadow hvr-icon-grow">
            <i class="tim-icons icon-bullet-list-67 hvr-icon"></i>
            {{ item.title | truncate(20) }}
          </span>
        </el-popover>

        <el-dropdown v-if="slotProps.row.estate_types.length > 3" :key="slotProps.row.estate_types.map((c) => c.id).join(',')">
          <span class="el-dropdown-link badge badge-default">
            باقی واگذری ها <i class="el-icon-arrow-down el-icon--right"></i>
          </span>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item
              v-for="item in slotProps.row.estate_types.filter( (category, index) => index > 3)"
              :key="item.id">
              {{ item.title }}
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </transition-group>
    </template>

    <template slot="modal">
      <md-field :class="getValidationClass('title')">
        <label>عنوان</label>
        <md-input v-model="title" :maxlength="$v.title.$params.maxLength.max" />
        <i class="md-icon tim-icons icon-caps-small"></i>
        <span class="md-helper-text">برای مثال : آسانسور ، پارکنیگ و ...</span>
        <span class="md-error" v-show="!$v.title.required">لطفا عنوان را وارد کنید</span>
      </md-field>
      <br/>
      <md-field :class="getValidationClass('description')">
        <label>توضیحات</label>
        <md-textarea v-model="description" md-autogrow :maxlength="$v.description.$params.maxLength.max"></md-textarea>
        <i class="md-icon tim-icons icon-paper"></i>
        <span class="md-helper-text">توضیحی مختصر درباره این امکان</span>
      </md-field>
      <br/>
      <md-field>
        <label>آیکون</label>
        <md-select v-model="icon" >
          <md-optgroup v-for="group in $store.state.icons" :key="group.label" :label="group.label">
            <md-option v-for="icon in group.icons" :key="icon" :value="icon">
              {{ icon }} <i :class="`fas fa-${icon}`"></i>
            </md-option>
          </md-optgroup>
        </md-select>
        <i class="md-icon fa-icon" :class="`fas fa-${icon}`"></i>
        <span class="md-helper-text">آیکون را مشخص کنید</span>
      </md-field>
      <br/>
      <md-field>
        <label>نوع ملک ها</label>
        <md-select v-model="estate_types" multiple>
          <md-option v-for="item in $store.state.feature.estate_type" :key="item.id" :value="item.id">{{ item.title }}</md-option>
        </md-select>
        <i class="md-icon tim-icons icon-chart-pie-36"></i>
        <span class="md-helper-text">نوع ملک های این امکان را مشخص کنید</span>
      </md-field>
      <br/>
      
      <div class="row">
        <base-input class="w-100" label="وضعیت نمایش">
          <el-switch
            dir="ltr"
            class="d-flex justify-content-center"
            v-model="is_detailable"
            active-text="قابل نمایش"
            active-color="#ff8d72"
            inactive-text="عدم نمایش">
          </el-switch>
          <small slot="helperText" id="emailHelp" class="form-text text-muted">در صورت قابل نمایش بودن ، این آپشن بر روی کارت های املاک نمایش داده خواهد شد</small>
        </base-input>
      </div>
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
    title: 'امکانات املاک',
  },
  data() {
    return {
      plural: 'features',
      type: 'feature',
      group: 'feature',
      label: 'امکان',
    }
  },
  validations: {
    title: {
      required,
      maxLength: maxLength(50)
    },
    description: {
      maxLength: maxLength(255)
    },
  },
  computed: {
    title: bind('title'),
    description: bind('description'),
    estate_types: bind('estate_types'),
    icon: bind('icon'),
    is_detailable: bind('is_detailable'),

    getFields() {
      return [
        {
          field: 'title',
          label: 'عنوان امکان',
          icon: 'icon-caps-small'
        }, {
          field: 'description',
          label: 'توضیحات امکان',
          icon: 'icon-paper'
        }, {
          field: 'estate_types',
          label: 'نوع ملک ها',
          icon: 'icon-chart-pie-36'
        }, {
          field: 'is_detailable',
          label: 'وضعیت نمایش',
          icon: 'icon-bulb-63'
        }
      ]
    },
    allQuery() {
      return `title description icon is_detailable estate_types { id title }`
    }
  },
  mounted() {
    this.$store.dispatch('getData', {
      group: 'feature',
      type: 'estate_type',
      query: `estate_types(per_page: 20) {
        data { id title description icon assignments { id title } created_at created_at updated_at }
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