<template>
  <datatable
    :type="type"
    :group="group"
    :label="label"
    :fields="getFields"
    :methods="{
      create: () => false
    }"
    :canCreate="false"
    :canEdit="false"
    :canDelete="false"
    :canSelect="false"
    ref="datatable"
    :emptyState="emptyState"
  >
    
    <template #payer-body="slotProps">
      <!-- <img class="tilt" :src="slotProps.row.payer.avatar ? slotProps.row.payer.avatar.thumb : '/images/placeholder-user.png'" /> -->
      <table class="spec-feature-table">
        <tbody>
          <tr v-if="slotProps.row.payer.full_name.trim()">
            <td>
              <i class="tim-icons icon-single-02"></i>
            </td>
            <td>
              {{ slotProps.row.payer.full_name }}
            </td>
          </tr>
          <tr>
            <td>
              <i class="tim-icons icon-badge"></i>
            </td>
            <td>
              {{ slotProps.row.payer.username }}
            </td>
          </tr>
          <tr v-if="slotProps.row.payer.phone_number">
            <td>
              <i class="tim-icons icon-mobile"></i>
            </td>
            <td>
              <a :href="`telto:${slotProps.row.payer.phone_number}`">{{ slotProps.row.payer.phone_number }}</a>
            </td>
          </tr>
        </tbody>
      </table>
    </template>

    <template #plan-body="slotProps">
      <div v-if="slotProps.row.plan">
        {{ slotProps.row.plan.title }}
        <span class="d-block text-muted" :style="{ fontSize: '12px' }">
          (
            <span v-if="slotProps.row.plan.credit_days_count === 30">
              ماهانه
            </span>
            <span v-else-if="slotProps.row.plan.credit_days_count === 360">
              یکساله
            </span>
            <span v-else-if="slotProps.row.plan.credit_days_count % 30 === 0">
              {{ slotProps.row.plan.credit_days_count / 30 }} ماهه
            </span>
            <span v-else>
              {{ slotProps.row.plan.credit_days_count }} روزه
            </span>
          )
        </span>
      </div>
    </template>

    <template #paid_at-body="slotProps">
      <el-tooltip v-if="slotProps.row.paid_at" :content="slotProps.row.paid_at | time('پرداخت شده')" placement="left">
        <p><i class="tim-icons icon-check-2"></i> {{ slotProps.row.paid_at | ago }}</p>
      </el-tooltip>
      <span v-else class="badge badge-danger">
        پرداخت ناموفق
      </span>
    </template>

    <template #amount-body="slotProps">
      <span>
        {{ slotProps.row.amount | comma }} تومان
      </span>
    </template>

    <template #tracking_code-body="slotProps">
      <b v-if="slotProps.row.tracking_code">
        {{ slotProps.row.tracking_code }}
      </b>
    </template>

  </datatable>
</template>

<script>
import Datatable from '../../components/BaseDatatable.vue'

import initDatatable from '../../mixins/initDatatable'
import filtersHelper from '../../mixins/filtersHelper';

export default {
  components: {
    Datatable
  },
  mixins: [
    initDatatable,
    filtersHelper,
  ],
  metaInfo() {
    return {
      title: !this.can('read-payment') ? 'گزارشات پرداخت' : 'پرداختی های شما',
    }
  },
  data() {
    return {
      plural: 'payments',
      type: 'payment',
      group: 'financial',
      label: 'پرداخت',

      emptyState: {
        icon: 'payment',
        title: 'پرداختی انجام نشده است',
        description: 'هیچ سابقه پرداختی متاسفانه وجود ندارد ، هر پرداختی که در سایت انجام گیرد ، اطلاعات کامل آن را در این قسمت میتوانید مشاهده کنید'
      }
    }
  },
  computed: {
    getFields() {
      let fields = [
        {
          field: 'plan',
          label: 'پلن خریداری شده',
          icon: 'icon-caps-small'
        }, {
          field: 'description',
          label: 'توضیحات',
          icon: 'icon-paper'
        }, {
          field: 'paid_at',
          label: 'تاریخ پرداخت',
          icon: 'icon-molecule-40'
        }, {
          field: 'tracking_code',
          label: 'کد رهگیری',
          icon: 'icon-molecule-40'
        }, {
          field: 'amount',
          label: 'مبلغ',
          icon: 'icon-coins'
        }
      ]
      
      if ( !this.can('read-payment') ) {
        fields.unshift({
          field: 'payer',
          label: 'کاربر',
          icon: 'icon-single-02'
        })
      }

      return fields
    },
    allQuery() {
      return `
        amount
        description
        tracking_code
        paid_at
        plan { id title credit_days_count }
        payer {
          id first_name last_name full_name username phone_number
          avatar { id file_name thumb }
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