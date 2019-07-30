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
    ref="datatable"
    :emptyState="emptyState"
  > 
    <template #photos-body="slotProps">
      <div>
        <img class="tilt" :src="slotProps.row.photos && slotProps.row.photos.length !== 0 ? slotProps.row.photos[0].thumb : '/images/placeholder.png'" />
        <span :style="{ marginTop: '-50%', background: slotProps.row.assignment ? slotProps.row.assignment.color : '#f13b3bc7' }" class="d-block position-relative badge badge-danger">
          {{ slotProps.row.assignment ? slotProps.row.assignment.title : '' }}
          {{ slotProps.row.estate_type ? slotProps.row.estate_type.title : '' }}
        </span>
      </div>
    </template>

    <template #address-body="slotProps">
      <el-popover
        placement="top-end"
        width="300"
        trigger="hover"
        :disabled="typeof slotProps.row.address === 'string' ? slotProps.row.address.length <= 45 : false"
        :content="slotProps.row.address"
      >
        <a v-if="!can('see-details-estate')" :href="`/estate/${slotProps.row.id}`" slot="reference">{{ slotProps.row.address + ' ، پلاک ' + slotProps.row.plaque | truncate(50) }}</a>
        <a v-else :href="`/estate/${slotProps.row.id}`" slot="reference">{{ slotProps.row.address | truncate(50) }}</a>
      </el-popover>
    </template>

    <template #landlord-body="slotProps">
      <table class="table-p-0">
        <tbody>
          <tr>
            <td>
              <i class="tim-icons icon-badge"></i>
            </td>
            <td class="text-right">
              <el-popover
                placement="top-end"
                width="300"
                trigger="hover"
                :disabled="typeof slotProps.row.landlord_fullname === 'string' ? slotProps.row.landlord_fullname.length <= 30 : false"
                :content="slotProps.row.landlord_fullname"
              >
                <span slot="reference">{{ slotProps.row.landlord_fullname | truncate(30) }}</span>
              </el-popover>
            </td>
          </tr>
          <tr>
            <td>
              <i class="tim-icons icon-mobile"></i>
            </td>
            <td class="text-right">
              <a :href="`telto:${slotProps.row.landlord_phone_number}`">{{ slotProps.row.landlord_phone_number }}</a>
            </td>
          </tr>
        </tbody>
      </table>
    </template>

    <template #assignment_count-body="slotProps">
      {{ slotProps.row.assignment_count }} دفعه
    </template>

    <template #custom-operations="slotProps">
      <el-tooltip content="تایید واگذاری">
        <base-button class="ml-2" @click="acceptAssignment(slotProps.index, slotProps.row)" type="warning" size="sm" icon>
          <i class="tim-icons icon-check-2"></i>
        </base-button>
      </el-tooltip>
    </template>

    <template #custom-buttons>
      <transition name="fade">
        <base-button
          @click="acceptAssignmentMultiple"
          v-show="attr('selected_items').length >= 1"
          :disabled="can(`accept-assignment-estate`)"
          size="sm"
          type="warning"
          class="pull-left mr-2"
        >
          تایید واگذاری
          <ICountUp
            :style="{display: 'inline'}"
            :endVal="attr('selected_items').length"
            :options="{
              useEasing: true,
              useGrouping: true,
            }"
          />
          ملک انتخاب شده
          <i class="tim-icons icon-check-2"></i>
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
    title: 'املاک واگذار شده',
  },
  data() {
    return {
        type: 'assignmented_estate',
        plural: 'estates',
        group: 'estate',
        label: 'ملک واگذار شده',

        emptyState: {
          icon: 'insert_emoticon',
          title: 'ملک جدیدی اعلام واگذاری نشده است',
          description: 'تمامی ملک ها تایید واگذاری شده اند ، در حال حاضر هیچ ملک جدیدی اعلام واگذاری نشده است'
        }
    }
  },
  methods: {
    acceptAssignment(index) {
      this.$swal.fire({
        title: `برای تایید واگذاری ملک مورد نظر مطمئن هستید ؟`,
        text: `در صورت تایید کردن ، ملک مورد نظر انتخاب شده به حالت تایید شده درخواهند آمد !`,
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
          
          axios.post(`/graphql/auth`, {
            query: `
              mutation {
                acceptAssignmentEstate(estate: ${this.data()[index].id}) {
                  status
                  message
                }
              }
            `
          })
          .then(({data}) => {
            this.setAttr('is_query_loading', false)
            
            if ( data.data.acceptAssignmentEstate.status === 400 )
            {
              return this.$notify({
                title: 'تایید نشد',
                message: data.data.acceptAssignmentEstate.message,
                timeout: 1500,
                type: 'danger',
                verticalAlign: 'top',
                horizontalAlign: 'left',
              })
            }

            this.data().splice(index, 1)
            this.setData( this.data() )

            this.setAttr('counts', { total: this.attr('counts').total - 1 })

            this.$notify({
              title: 'تایید شد',
              message: `ملک مورد نظر با موفقیت تایید واگذاری شد`,
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
    },
    acceptAssignmentMultiple() {
      this.$swal.fire({
        title: `برای تایید واگذاری ملک های مورد نظر مطمئن هستید ؟`,
        text: `در صورت تایید کردن ، ملک های انتخاب شده به حالت تایید شده درخواهند آمد !`,
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

          var ids = [];
          this.attr('selected_items').forEach( item => {
            ids = [...ids, this.data()[item].id]
          })

          axios.post(`/graphql/auth`, {
            query: `
              mutation {
                acceptAssignmentEstate(estates: [${ids.join(',')}]) {
                  status
                  message
                }
              }
            `
          })
          .then(({data})=> {
            this.setAttr('is_query_loading', false)

            if ( data.data.acceptAssignmentEstate.status === 400 )
            {
              return this.$notify({
                title: 'تایید نشد',
                message: data.data.acceptAssignmentEstate.message,
                timeout: 1500,
                type: 'danger',
                verticalAlign: 'top',
                horizontalAlign: 'left',
              })
            }

            let newDataArray = this.$store.state[this.group][this.type].filter((item, index) => {
              return !this.attr('selected_items').includes(index)
            })
            this.setData(newDataArray);

            this.setAttr('counts', {
              total: this.attr('counts').total - this.attr('selected_items').length,
            })

            this.setAttr('selected_items', [], true)
            this.selected_items = [];

            this.$notify({
              title: 'تایید شد',
              message: `ملک های مورد نظر با موفقیت تایید واگذاری شد`,
              timeout: 1500,
              type: 'success',
              verticalAlign: 'top',
              horizontalAlign: 'left',
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
          });
        }
      })
    },
  },
  computed: {
    getFields() {
      let fields = [
        {
          field: 'photos',
          label: 'تصویر ملک',
          icon: 'icon-image-02'
        }, {
          field: 'address',
          label: 'آدرس',
          icon: 'icon-square-pin'
        }
      ]

      if ( !this.can('see-details-estate') ) {
        fields.push({
          field: 'landlord',
          label: 'مشخصات مالک',
          icon: 'icon-single-02'
        })
      }

      fields.push({
        field: 'assignment_count',
        label: 'تعداد دفعات',
        icon: 'icon-chart-bar-32'
      })

      return fields
    },
    
    allQuery() {
      return `
        address
        plaque
        assignment_count
        landlord_fullname
        landlord_phone_number
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
      return `has_assignment_request: true, is_assignmented: false`
    }
  },
  beforeRouteLeave (to, from, next) {
    this.$refs.datatable.closePanel()

    setTimeout( () => next(), 700);
  },
}
</script>
