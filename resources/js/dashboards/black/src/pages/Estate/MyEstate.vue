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
    :abilities="abilities"
    ref="datatable"
  > 
    <template v-slot:photos-body="slotProps">
      <div>
        <img class="tilt" :src="slotProps.row.photos && slotProps.row.photos.length !== 0 ? slotProps.row.photos[0].thumb : '/images/placeholder.png'" />
        <div v-if="!slotProps.row.is_active">
          <el-popover
            v-if="slotProps.row.reject_reason"
            placement="bottom"
            title="علت تایید نشدن ملک :"
            trigger="hover"
            :content="slotProps.row.reject_reason"
          >
            <span slot="reference" :style="{ marginTop: '-50%', background: '#f13b3bc7' }" class="d-block position-relative badge badge-danger">تایید نشده</span>
          </el-popover>
          <span v-else :style="{ marginTop: '-50%', background: '#ff8d72c9' }" class="d-block position-relative badge badge-danger">در انتظار تایید</span>
        </div>
        <span v-else :style="{ marginTop: '-50%', background: slotProps.row.assignment ? slotProps.row.assignment.color : '#f13b3bc7' }" class="d-block position-relative badge badge-danger">
          {{ slotProps.row.assignment ? slotProps.row.assignment.title : '' }}
          {{ slotProps.row.estate_type ? slotProps.row.estate_type.title : '' }}
        </span>
      </div>
    </template>

    <template #title-body="slotProps">
      <el-popover
        placement="top-end"
        width="300"
        trigger="hover"
        :disabled="typeof slotProps.row.title === 'string' ? slotProps.row.title.length <= 50 : false"
        :content="slotProps.row.title"
      >
        <a :href="`/estate/${slotProps.row.id}`" slot="reference">{{ slotProps.row.title | truncate(50) }}</a>
      </el-popover>
    </template>

    <template #address-body="slotProps">
      <el-popover
        placement="top-end"
        width="300"
        trigger="hover"
        :disabled="typeof slotProps.row.address === 'string' ? slotProps.row.address.length <= 50 : false"
        :content="slotProps.row.address"
      >
        <a :href="`/estate/${slotProps.row.id}`" slot="reference">{{ slotProps.row.address | truncate(50) }}</a>
      </el-popover>
    </template>

    <template #price-body="slotProps">
      <table class="table-p-0" v-if="slotProps.row.assignment">
        <tbody>
          <el-popover
            v-if="slotProps.row.assignment.has_rental_price"
            placement="bottom"
            title="اجاره"
            trigger="hover"
            :content="slotProps.row.rental_price | price"
          >
            <tr slot="reference">
              <td>اجاره</td>
              <td>{{ slotProps.row.rental_price | numToText }}</td>
            </tr>
          </el-popover>
          
          <el-popover
            v-if="slotProps.row.assignment.has_mortgage_price"
            placement="bottom"
            title="رهن"
            trigger="hover"
            :content="slotProps.row.mortgage_price | price"
          >
            <tr slot="reference">
              <td>رهن</td>
              <td>{{ slotProps.row.mortgage_price | numToText }}</td>
            </tr>
          </el-popover>

          <el-popover
            v-if="slotProps.row.assignment.has_sales_price"
            placement="bottom"
            title="قیمت"
            trigger="hover"
            :content="slotProps.row.sales_price | price"
          >
            <tr slot="reference">
              <td>قیمت</td>
              <td>{{ slotProps.row.sales_price | numToText }}</td>
            </tr>
          </el-popover>
        </tbody>
      </table>
    </template>

    <template #area-body="slotProps">
      {{ slotProps.row.area | comma }} متر
    </template>
  </datatable>
</template>

<script>
import Datatable from '../../components/BaseDatatable.vue'

import createMixin from '../../mixins/createMixin'
import initDatatable from '../../mixins/initDatatable'
import filtersHelper from '../../mixins/filtersHelper'
import moment from 'moment'

export default {
  components: {
    Datatable
  },
  mixins: [
    initDatatable,
    createMixin,
    filtersHelper
  ],
  metaInfo: {
    title: 'املاک',
  },
  data() {
    return {
        type: 'my_estate',
        plural: 'estates',
        group: 'estate',
        label: 'ملک',
    }
  },
  methods: {
    create() {
      this.setAttr('is_creating', true)
      this.$router.push('/panel/estate/create')
    },
    edit(index, row) {
      this.setAttr('is_creating', false)
      this.$router.push(`/panel/estate/${row.id}/edit`)
    },
  },
  computed: {
    abilities() {
      return {
        create: !this.can('create-estate') || (
            this.$store.state.me.remaining_registered_count > 0
          && this.$store.state.me.validity_end_at
          && !moment(this.$store.state.me.validity_end_at).isBefore( moment() )
        ),
        edit: true,
        delete: true
      }
    },
    getFields() {
      return [
        {
          field: 'photos',
          label: 'تصویر ملک',
          icon: 'icon-image-02'
        }, {
          field: 'title',
          label: 'عنوان',
          icon: 'icon-caps-small'
        }, {
          field: 'address',
          label: 'آدرس',
          icon: 'icon-square-pin'
        }, {
          field: 'price',
          label: 'قیمت',
          icon: 'icon-coins'
        }, {
          field: 'area',
          label: 'متراژ',
          icon: 'icon-map-big'
        }
      ]
    },
    allQuery() {
      return `
        title
        address
        sales_price
        mortgage_price
        rental_price
        area
        reject_reason
        is_active
        assignment {
          id
          title
          color
          has_sales_price
          has_mortgage_price
          has_rental_price
        }
        estate_type {
          id
          title
        }
        photos {
          id
          file_name
          thumb
        }
        `
    },
    queryFilters() {
      return `registered_by_me: true`
    }
  },
  beforeRouteLeave (to, from, next) {
    this.$refs.datatable.closePanel()

    setTimeout( () => next(), 700);
  },
}
</script>

<style>
.table-p-0 * {
  padding: 2px !important;
}
</style>
