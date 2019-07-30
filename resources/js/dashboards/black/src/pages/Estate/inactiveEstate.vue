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
    :canDelete="false"
    :canEdit="false"
    :canCreate="false"
    :emptyState="emptyState"
    ref="datatable"
  > 
    <template v-slot:photos-body="slotProps">
      <div>
        <img class="tilt" :src="slotProps.row.photos && slotProps.row.photos.length !== 0 ? slotProps.row.photos[0].thumb : '/images/placeholder.png'" />
        <span :style="{ marginTop: '-50%', background: slotProps.row.assignment ? slotProps.row.assignment.color : '#f13b3bc7' }" class="d-block position-relative badge badge-danger">
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

    <template #custom-operations="slotProps">
      <el-tooltip content="تایید ملک">
        <base-button
          class="ml-2"
          :disabled="can(`active-estate`)"
          @click="accept(slotProps.index, slotProps.row, true)"
          :type="can(`active-estate`) ? 'info' : 'success'"
          size="sm"
          icon
        >
          <i class="tim-icons icon-check-2"></i>
        </base-button>
      </el-tooltip>

      <el-tooltip content="رد ملک">
        <base-button
          class="ml-2"
          :disabled="can(`active-estate`)"
          @click="accept(slotProps.index, slotProps.row, false)"
          :type="can(`active-estate`) ? 'info' : 'danger'"
          size="sm"
          icon
        >
          <i class="tim-icons icon-simple-remove"></i>
        </base-button>
      </el-tooltip>
    </template>

    <template #custom-buttons>
      <transition name="fade">
        <base-button
          @click="accept_multiple(true)"
          v-show="attr('selected_items').length >= 1"
          :disabled="can(`active-estate`)"
          size="sm"
          type="success"
          class="pull-left mr-2"
        >
          تایید
          <ICountUp
            :style="{display: 'inline'}"
            :endVal="attr('selected_items').length"
            :options="{
              useEasing: true,
              useGrouping: true,
            }"
          />
          مورد انتخاب شده
          <i class="tim-icons icon-check-2"></i>
        </base-button>
      </transition>

      <transition name="fade">
        <base-button
          @click="accept_multiple(false)"
          v-show="attr('selected_items').length >= 1"
          :disabled="can(`active-estate`)"
          size="sm"
          type="warning"
          class="pull-left mr-2"
        >
          رد
          <ICountUp
            :style="{display: 'inline'}"
            :endVal="attr('selected_items').length"
            :options="{
              useEasing: true,
              useGrouping: true,
            }"
          />
          مورد انتخاب شده
          <i class="tim-icons icon-refresh-01"></i>
        </base-button>
      </transition>
    </template>
  </datatable>
</template>

<script>
import Datatable from '../../components/BaseDatatable.vue'
import ICountUp from 'vue-countup-v2'

import createMixin from '../../mixins/createMixin'
import initDatatable from '../../mixins/initDatatable'
import filtersHelper from '../../mixins/filtersHelper';

export default {
  components: {
    Datatable,
    ICountUp
  },
  mixins: [
    initDatatable,
    createMixin,
    filtersHelper
  ],
  metaInfo: {
    title: 'املاک تایید نشده',
  },
  data() {
    return {
        type: 'inactive_estate',
        plural: 'estates',
        group: 'estate',
        label: 'ملک تایید نشده',

        emptyState: {
          icon: 'insert_emoticon',
          title: 'ملک جدیدی ثبت نشده است',
          description: 'تمامی ملک های ثبت شده تایید شده اند ، ملک جدیدی جهت تایید وجود ندارد'
        }
    }
  },
  methods: {
    accept(index, row, status) {
      this.$swal.fire({
        title: `برای ${status ? 'تایید' : 'رد'} ملک مورد نظر مطمئن هستید ؟`,
        text: `در صورت ${status ? 'تایید' : 'رد'} کردن ، ملک مورد نظر به حالت ${status ? 'تایید' : 'رد'} شده درخواهد آمد !`,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'linear-gradient(to bottom left, #00f2c3, #0098f0)',
        confirmButtonColor: '#0098f0',
        cancelButtonColor: '#d33',
        confirmButtonText: 'بله ، مطمئنم !',
        cancelButtonText: 'نه ، اشتباه شده'
      }).then((result) => {
        if (result.value) {

          if ( !status )
          {
            this.$swal.fire({
              title: `علت شما برای رد کردن این ملک چیست ؟`,
              type: 'warning',
              input: 'textarea',
              inputPlaceholder: 'در این قسمت علت خود را بنویسید ...',
              showCancelButton: true,
              confirmButtonColor: 'linear-gradient(to bottom left, #00f2c3, #0098f0)',
              confirmButtonColor: '#0098f0',
              cancelButtonColor: '#d33',
              confirmButtonText: 'ثبت دلیل و رد ملک !',
              cancelButtonText: 'منصرف شدم'
            }).then((result) => {
              if ( result.value ) {
                this.setAttr('is_query_loading', true)
      
                axios.post(`/graphql/auth`, {
                  query: `
                    mutation {
                      activeEstate(id: ${this.data()[index].id}, status: false, reason: "${result.value}") {
                        status
                        message
                      }
                    }
                  `
                })
                .then(({data}) => {
                  this.setAttr('is_query_loading', false)
          
                  this.data().splice(index, 1)
                  this.setData( this.data() )
      
                  this.setAttr('counts', { total: this.attr('counts').total - 1 })
                  
                  this.$notify({
                    title: 'تایید شد',
                    message: `ملک مورد نظر با موفقیت تایید شد :)`,
                    timeout: 1500,
                    type: 'success',
                    verticalAlign: 'top',
                    horizontalAlign: 'left',
                  })
                })
                .catch( error => {
                  this.setAttr('is_query_loading', false)
                  console.log(error)
                });
              }
            })
          }
          else
          {
            this.setAttr('is_query_loading', true)
      
            axios.post(`/graphql/auth`, {
              query: `
                mutation {
                  activeEstate(id: ${this.data()[index].id}, status: true) {
                    status
                    message
                  }
                }
              `
            })
            .then(({data}) => {
              this.setAttr('is_query_loading', false)
      
              this.data().splice(index, 1)
              this.setData( this.data() )
  
              this.setAttr('counts', { total: this.attr('counts').total - 1 })
              
              this.$notify({
                title: 'تایید شد',
                message: `ملک مورد نظر با موفقیت تایید شد :)`,
                timeout: 1500,
                type: 'success',
                verticalAlign: 'top',
                horizontalAlign: 'left',
              })
            })
            .catch( error => {
              this.setAttr('is_query_loading', false)
              console.log(error)
            });
          }
        }
      })
    },
    accept_multiple(status) {
      this.$swal.fire({
        title: `برای ${status ? 'تایید' : 'رد'} ملک های انتخاب شده مطمئن هستید ؟`,
        text: `در صورت ${status ? 'تایید' : 'رد'} کردن ، تمامی ملک های انتخاب شده به حالت ${status ? 'تایید' : 'رد'} شده درخواهند آمد !`,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'linear-gradient(to bottom left, #00f2c3, #0098f0)',
        confirmButtonColor: '#0098f0',
        cancelButtonColor: '#d33',
        confirmButtonText: 'بله ، مطمئنم !',
        cancelButtonText: 'نه ، اشتباه شده'
      }).then((result) => {
        if (result.value) {
          if ( !status )
          {
            this.$swal.fire({
              title: `علت شما برای رد کردن ملک ها چیست ؟`,
              type: 'warning',
              input: 'textarea',
              inputPlaceholder: 'در این قسمت علت خود را بنویسید ...',
              showCancelButton: true,
              confirmButtonColor: 'linear-gradient(to bottom left, #00f2c3, #0098f0)',
              confirmButtonColor: '#0098f0',
              cancelButtonColor: '#d33',
              confirmButtonText: 'ثبت دلیل و رد ملک ها !',
              cancelButtonText: 'منصرف شدم'
            }).then((result) => {
              if ( result.value ) {
                
                this.setAttr('is_query_loading', true)
  
                var ids = [];
                this.attr('selected_items').forEach( item => {
                  ids = [...ids, this.data()[item].id]
                })
      
                axios.post(`/graphql/auth`, {
                  query: `
                    mutation {
                      activeEstate(ids: [${ids.join(',')}], status: false, reason: "${result.value}") {
                        status
                        message
                      }
                    }
                  `
                })
                .then(({data})=> {
                  this.setAttr('is_query_loading', false)
      
                  let newDataArray = this.$store.state[this.group][this.type].filter((item, index) => {
                    return !this.attr('selected_items').includes(index)
                  })
                  this.setData(newDataArray);
      
                  this.setAttr('counts', {
                    total: this.attr('counts').total - this.attr('selected_items').length,
                  })
      
                  this.setAttr('selected_items', [], true)
                  this.$refs.datatable.selected_items = [];
      
                  this.$swal.fire({
                    title: `${status ? 'تایید' : 'رد'} شدند`,
                    text: `${this.label} هایی که انتخاب کردید با موفقیت ${status ? 'تایید' : 'رد'} شدند :)`,
                    type: 'success',
                    timer: 1000,
                    showConfirmButton: false,
                  })
      
                })
                .catch(error => {
                  this.setAttr('is_query_loading', false)
      
                  if (error.response) {
                    this.$swal.fire({
                      title: 'خطایی رخ داد !',
                      text: error.response.data.message,
                      type: 'error',
                      timer: 5000,
                      confirmButtonText: 'بسیار خب :('
                    })
                  } else {
                    console.log(error)
                  }
                })
              }
            })
          }
          else
          {
            this.setAttr('is_query_loading', true)
  
            var ids = [];
            this.attr('selected_items').forEach( item => {
              ids = [...ids, this.data()[item].id]
            })
  
            axios.post(`/graphql/auth`, {
              query: `
                mutation {
                  activeEstate(ids: [${ids.join(',')}], status: true) {
                    status
                    message
                  }
                }
              `
            })
            .then(({data})=> {
              this.setAttr('is_query_loading', false)
  
              let newDataArray = this.$store.state[this.group][this.type].filter((item, index) => {
                return !this.attr('selected_items').includes(index)
              })
              this.setData(newDataArray);
  
              this.setAttr('counts', {
                total: this.attr('counts').total - this.attr('selected_items').length,
              })
  
              this.setAttr('selected_items', [], true)
              this.$refs.datatable.selected_items = [];
  
              this.$swal.fire({
                title: `${status ? 'تایید' : 'رد'} شدند`,
                text: `${this.label} هایی که انتخاب کردید با موفقیت ${status ? 'تایید' : 'رد'} شدند :)`,
                type: 'success',
                timer: 1000,
                showConfirmButton: false,
              })
  
            })
            .catch(error => {
              this.setAttr('is_query_loading', false)
  
              if (error.response) {
                this.$swal.fire({
                  title: 'خطایی رخ داد !',
                  text: error.response.data.message,
                  type: 'error',
                  timer: 5000,
                  confirmButtonText: 'بسیار خب :('
                })
              } else {
                console.log(error)
              }
            })
          }
        }
      })
    },
  },
  computed: {
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
      return `is_active: false, has_reject_reason: false`
    }
  },
  beforeRouteLeave (to, from, next) {
    this.$refs.datatable.closePanel()

    setTimeout( () => next(), 700);
  },
}
</script>
