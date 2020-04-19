<template>
  <datatable
    class="spec-datatable"
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
    
    <template v-slot:estate_type-body="slotProps">
      <el-popover
        v-if="slotProps.row.estate_type"
        placement="top-end"
        width="300"
        trigger="hover"
        :disabled="typeof slotProps.row.estate_type.title === 'string' ? slotProps.row.estate_type.title.length <= 20 : false"
        :content="slotProps.row.estate_type.title"
      >
        <span
          slot="reference"
          class="badge badge-default ml-1 hvr-grow-shadow hvr-icon-grow">
          <i class="tim-icons icon-bullet-list-67 hvr-icon"></i>
          {{ slotProps.row.estate_type.title | truncate(20) }}
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

    <template slot="modal">
      <md-field :class="getValidationClass('title')">
        <label>عنوان جدول</label>
        <md-input v-model="title" :maxlength="$v.title.$params.maxLength.max" />
        <i class="md-icon tim-icons icon-caps-small"></i>
        <span class="md-helper-text">میتوانید از نام خود گروه استفاده کنید</span>
        <span class="md-error" v-show="!$v.title.required">لطفا عنوان جدول را وارد کنید</span>
      </md-field>
      <br/>
      <md-field :class="getValidationClass('description')">
        <label>توضیحات جدول</label>
        <md-textarea v-model="description" md-autogrow :maxlength="$v.description.$params.maxLength.max"></md-textarea>
        <i class="md-icon tim-icons icon-paper"></i>
        <span class="md-helper-text">توضیحی مختصر درباره جدول</span>
      </md-field>
      <br/>
      <md-field>
        <label>نوع ملک</label>
        <md-select v-model="estate_type_id">
          <md-option v-for="item in $store.state.feature.estate_type" :key="item.id" :value="item.id">{{ item.title }}</md-option>
        </md-select>
        <i class="md-icon tim-icons icon-chart-pie-36"></i>
        <span class="md-helper-text">نوع ملک مرتبط با این جدول را مشخص کنید</span>
      </md-field>
    </template>

    <template #custom-operations="slotProps">
      <el-tooltip content="مدیریت جدول">
        <base-button class="m-0" @click.once="manageTable(slotProps.index, slotProps.row)" type="success" size="sm" icon>
          <i class="tim-icons icon-bullet-list-67"></i>
        </base-button>
      </el-tooltip>
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
    title: 'جداول مشخصات',
  },
  data() {
    return {
      plural: 'specs',
      type: 'spec',
      group: 'feature',
      label: 'جدول مشخصات',

      defaultProps: {
        children: 'childs',
        label: 'title',
      },
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
  mounted() {
    this.$store.dispatch('getData', {
      group: 'feature',
      type: 'estate_type',
      query: `estate_types(per_page: 20) {
        data {
          id title description icon
          assignments { id title }
          created_at updated_at
        }
        total trash chart { month count }
      }`
    })
  },
  methods: {
    manageTable(index, row)
    {
      this.setAttr('is_creating', false)

      this.$router.push(`/panel/specification/${row.id}`)
    },
    changeSelectedCategories() {
      this.$store.commit('setFormData', {
        group: this.group,
        type: this.type,
        field: 'categories',
        value: this.$refs.categories.getCheckedKeys()
      })
    },
    afterCreate()
    {
      this.$store.commit('setFormData', {
        group: this.group,
        type: this.type,
        field: 'estate_type_id',
        value: null
      })
    },
    afterEdit(row)
    {
      this.$store.commit('setFormData', {
        group: this.group,
        type: this.type,
        field: 'estate_type_id',
        value: row.estate_type ? row.estate_type.id : null
      })
    },
  },
  computed: {
    title: bind('title'),
    description: bind('description'),
    estate_type_id: bind('estate_type_id'),

    getFields() {
      return [
        {
          field: 'title',
          label: 'عنوان جدول',
          icon: 'icon-caps-small'
        }, {
          field: 'description',
          label: 'توضیحات',
          icon: 'icon-paper'
        }, {
          field: 'estate_type',
          label: 'نوع ملک',
          icon: 'icon-app'
        }
      ]
    },
    allQuery() {
      return `
        title
        description
        estate_type {
          id
          title
        }
      `
    }
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
