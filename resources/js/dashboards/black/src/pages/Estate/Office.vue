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
    :canCreate="false"
    ref="datatable"
  >
    <template v-slot:owner-body="slotProps">
      <table class="spec-feature-table">
        <tbody>
          <tr class="mt-2" v-if="slotProps.row.owner">
            <td>
              <img class="tilt" :src="slotProps.row.owner.avatar ? slotProps.row.owner.avatar.thumb : '/images/placeholder-user.png'" />
            </td>
            <td class="text-right">
              <el-popover
                placement="top-end"
                width="300"
                trigger="hover"
                :disabled="typeof slotProps.row.owner.full_name === 'string' ? slotProps.row.owner.full_name.length <= 30 : false"
                :content="slotProps.row.owner.full_name"
              >
                <span slot="reference">{{ slotProps.row.owner.full_name | truncate(30) }}</span>
              </el-popover>

              <span class="badge badge-warning">{{ slotProps.row.owner.username }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </template>

    <template #info-body="slotProps">
      <table class="spec-feature-table">
        <tbody>
          <tr>
            <td>
              <i class="tim-icons icon-caps-small"></i>
            </td>
            <td>
              {{ slotProps.row.name }}
            </td>
          </tr>
          <tr>
            <td>
              <i class="tim-icons icon-badge"></i>
            </td>
            <td>
              <a :href="`/${slotProps.row.username}`">{{ slotProps.row.username }}</a>
            </td>
          </tr>
        </tbody>
      </table>
    </template>

    <template #detail-body="slotProps">
      <table class="spec-feature-table">
        <tbody>
          <tr v-if="slotProps.row.area">
            <td>
              <i class="tim-icons icon-map-big"></i>
            </td>
            <td>
              {{ slotProps.row.area.name }}
            </td>
          </tr>
          <tr>
            <td>
              <i class="tim-icons icon-square-pin"></i>
            </td>
            <td>
              {{ slotProps.row.address }}
            </td>
          </tr>
          <tr>
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

    <template slot="modal">
      <md-field :class="getValidationClass('name')">
        <md-input v-model="name" :maxlength="$v.name.$params.maxLength.max" />
        <label>نام دفتر املاک</label>
        <i class="md-icon tim-icons icon-caps-small"></i>
        <span class="md-helper-text">برای مثال : املاک آسمان</span>
        <span class="md-error" v-show="!$v.name.required">لطفا نام دفتر املاک را وارد کنید</span>
      </md-field>
      <br/>

      <md-field :class="getValidationClass('username')">
        <label>نام کاربری</label>
        <md-input v-model="username" :maxlength="$v.username.$params.maxLength.max" />
        <i class="md-icon tim-icons icon-badge"></i>
        <span class="md-helper-text">برای مثال : amlak-aseman</span>
        <span class="md-error" v-show="!$v.username.required">لطفا نام کاربری دفتر املاک را وارد کنید</span>
      </md-field>
      <br/>

      <md-field :class="getValidationClass('phone_number')">
        <label>شماره تلفن</label>
        <md-input v-model="phone_number" />
        <i class="md-icon tim-icons icon-mobile"></i>
        <span class="md-helper-text">برای مثال : ۰۹۱۲۳۴۵۶۷۸۹</span>
        <span class="md-error" v-show="!$v.phone_number.required">لطفا شماره تلفن دفتر املاک را وارد کنید</span>
      </md-field>
      <br/>

      <md-field>
        <label>منطقه</label>
        <md-select v-model="area_id">
          <md-option>بدون منطقه</md-option>
          <md-option v-for="item in $store.state.place.area" :key="item.id" :value="item.id">{{ item.name }}</md-option>
        </md-select>
        <i class="md-icon tim-icons icon-map-big"></i>
        <span class="md-helper-text">منطقه این دفتر املاک را مشخص کنید</span>
      </md-field>
      <br/>

      <md-field :class="getValidationClass('address')">
        <label>آدرس</label>
        <md-textarea v-model="address" md-autogrow :maxlength="$v.address.$params.maxLength.max"></md-textarea>
        <i class="md-icon tim-icons icon-square-pin"></i>
        <span class="md-helper-text">آدرس دفتر املاک</span>
        <span class="md-error" v-show="!$v.address.required">لطفا آدرس دفتر املاک را وارد کنید</span>
      </md-field>
      <br/>

      <md-field :class="getValidationClass('description')">
        <label>توضیحات</label>
        <md-textarea v-model="description" md-autogrow :maxlength="$v.description.$params.maxLength.max"></md-textarea>
        <i class="md-icon tim-icons icon-paper"></i>
        <span class="md-helper-text">توضیحی مختصر درباره دفتر املاک</span>
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
    Binding,
    validationMixin
  ],
  metaInfo: {
    title: 'دفاتر املاک',
  },
  data() {
    return {
        type: 'office',
        plural: 'offices',
        group: 'user',
        label: 'دفتر املاک',
    }
  },
  validations: {
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
  },
  computed: {
    name: bind('name'),
    username: bind('username'),
    description: bind('description'),
    address: bind('address'),
    phone_number: bind('phone_number'),
    area_id: bind('area_id'),
    
    getFields() {
      return [
        {
          field: 'owner',
          label: 'مالک',
          icon: 'icon-caps-small'
        }, {
          field: 'info',
          label: 'اطلاعات',
          icon: 'icon-caps-small'
        }, {
          field: 'detail',
          label: 'مشخصات',
          icon: 'icon-paper'
        }, {
          field: 'description',
          label: 'توضیحات',
          icon: 'icon-paper'
        }
      ]
    },
    
    allQuery() {
      return `
        name username description address phone_number
        area { id name }
        owner { id first_name last_name full_name username }
      `
    },
  },
  methods: {
    afterEdit(row)
    {
      this.$store.commit('setFormData', {
        group: this.group,
        type: this.type,
        field: 'area_id',
        value: row.area ? row.area.id : null
      })
    },
  },
  mounted() {
    this.$store.dispatch('getData', {
      group: 'place',
      type: 'area',
      query: `areas(per_page: 20) {
        data { id name coordinates { lat lng } city { id name } created_at updated_at }
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
