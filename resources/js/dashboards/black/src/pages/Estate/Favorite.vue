<template>
  <datatable
    :type="type"
    :group="group"
    :label="label"
    :fields="getFields"
    
    :methods="{
      deleteSingle: deleteFavorite
    }"
    :canCreate="false"
    :canEdit="false"
    :abilities="abilities"
    ref="datatable"
  > 
    <template v-slot:photos-body="slotProps">
      <div>
        <img class="tilt" :src="slotProps.row.photos && slotProps.row.photos.length !== 0 ? slotProps.row.photos[0].thumb : '/images/placeholder.png'" />
        <span v-if="!slotProps.row.is_active" :style="{ marginTop: '-50%', background: '#f13b3bc7' }" class="d-block position-relative badge badge-danger">پیش نویس</span>
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
import filtersHelper from '../../mixins/filtersHelper';

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
    title: 'املاک نشان شده',
  },
  data() {
    return {
        type: 'favorite',
        plural: 'favorites',
        group: 'estate',
        label: 'ملک نشان شده',
    }
  },
  methods: {
    deleteFavorite(index, row) {
      this.$swal.fire({
        title: `برای حذف ملک مورد نظر از لیست نشان شده ها مطمین هستید ؟`,
        text: "در صورت پاک کردن ، ملک مورد نظر به طور کامل از لیست نشان شده های شما حذف خواهد شد",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'linear-gradient(to bottom left, #00f2c3, #0098f0)',
        confirmButtonColor: '#0098f0',
        cancelButtonColor: '#d33',
        confirmButtonText: 'بله ، مطمئنم !',
        cancelButtonText: 'نه ، اشتباه شده'
      }).then((result) => {
        if (result.value) {
          
          this.setAttr('is_query_loading', true)
    
          axios.post('/graphql/auth', {
            query: `mutation {
              removeFavorite(estate: "${row.id}") {
                status
                message
              }
            }`
          })
          .then(({data}) => {
            this.setAttr('is_query_loading', false)
            
            this.data().splice(index, 1)
            this.setData( this.data() )
            
            this.setAttr('counts', {
              total: this.attr('counts').total - 1,
              trash: this.attr('counts').trash + 1,
            })

            this.$swal.fire({
              title: 'حذف شد',
              text: `${this.label} "${row.name ? row.name : row.title ? row.title : 'انتخاب شده'}" با موفقیت حذف شد :)`,
              type: 'success',
              showConfirmButton: false,
              timer: 1000,
            })
          })
        }
      })
    }
  },
  computed: {
    abilities() {
      return {
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
  },
  beforeRouteLeave (to, from, next) {
    this.$refs.datatable.closePanel()

    setTimeout( () => next(), 700);
  },
}
</script>