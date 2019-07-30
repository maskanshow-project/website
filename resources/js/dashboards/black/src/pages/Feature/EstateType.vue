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

    <template #assignments-body="slotProps">
      <transition-group name="list">
        <el-popover
          v-for="item in slotProps.row.assignments.filter( (i, index) => index < 3)"
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

        <el-dropdown v-if="slotProps.row.assignments.length > 3" :key="slotProps.row.assignments.map((c) => c.id).join(',')">
          <span class="el-dropdown-link badge badge-default">
            باقی واگذری ها <i class="el-icon-arrow-down el-icon--right"></i>
          </span>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item
              v-for="item in slotProps.row.assignments.filter( (category, index) => index > 3)"
              :key="item.id">
              {{ item.title }}
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </transition-group>
    </template>

    <template slot="modal">
      <md-field :class="getValidationClass('title')">
        <label>عنوان نوع ملک</label>
        <md-input v-model="title" :maxlength="$v.title.$params.maxLength.max" />
        <i class="md-icon tim-icons icon-caps-small"></i>
        <span class="md-helper-text">برای مثال : آپارتمان</span>
        <span class="md-error" v-show="!$v.title.required">لطفا عنوان این نوع ملک را وارد کنید</span>
      </md-field>
      <br/>
      <md-field :class="getValidationClass('description')">
        <label>توضیحات نوع ملک</label>
        <md-textarea v-model="description" md-autogrow :maxlength="$v.description.$params.maxLength.max"></md-textarea>
        <i class="md-icon tim-icons icon-paper"></i>
        <span class="md-helper-text">توضیحی مختصر درباره این نوع ملک</span>
      </md-field>
      <br/>
      <md-field>
        <label>آیکون</label>
        <md-select v-model="icon">
          <md-optgroup v-for="group in $store.state.icons" :key="group.label" :label="group.label">
            <md-option v-for="icon in group.icons" :key="icon" :value="icon">
              {{ icon }} <i :class="`fas fa-${icon}`"></i>
            </md-option>
          </md-optgroup>
        </md-select>
        <i class="md-icon fa-icon" :class="`fas fa-${icon}`"></i>
        <span class="md-helper-text">آیکون این نوع ملک را مشخص کنید</span>
      </md-field>
      <br/>
      <md-field>
        <label>واگذری ها</label>
        <md-select v-model="assignments" multiple>
          <md-option v-for="item in $store.state.feature.assignment" :key="item.id" :value="item.id">{{ item.title }}</md-option>
        </md-select>
        <i class="md-icon tim-icons icon-cloud-download-93"></i>
        <span class="md-helper-text">واگذری های این نوع ملک را مشخص کنید</span>
      </md-field>
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
    title: 'انواع ملک ها',
  },
  data() {
    return {
      plural: 'estate_types',
      type: 'estate_type',
      group: 'feature',
      label: 'نوع ملک',
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
    assignments: bind('assignments'),
    icon: bind('icon'),

    getFields() {
      return [
        {
          field: 'title',
          label: 'عنوان نوع ملک',
          icon: 'icon-caps-small'
        }, {
          field: 'description',
          label: 'توضیحات نوع ملک',
          icon: 'icon-paper'
        }, {
          field: 'assignments',
          label: 'واگذری ها',
          icon: 'icon-cloud-download-93'
        }
      ]
    },
    allQuery() {
      return `
        title
        description
        icon
        assignments {
          id
          title
        }
      `
    }
  },
  mounted() {
    this.$store.dispatch('getData', {
      group: 'feature',
      type: 'assignment',
      query: `assignments(per_page: 20) {
        data { id title description color has_sales_price has_mortgage_price has_rental_price created_at created_at updated_at }
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

.spec-datatable .data-table-row ul {
  min-height: 120px;
}

</style>
