<template>
  <div :style="{ position: 'relative', zIndex: 10 }">

    <line-chart
      v-if="is_loaded"
      class="mb-4"
      style="height: 140px"
      :gradient-color="['red', 'blue' , 'green']"
      :gradient-stops="[ 1, 0.4, 0 ]"
      :chart-data="charts.chartData"
      :extra-options="charts.extraOptions"
    >
    </line-chart>

    <div class="row" dir="rtl">
      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInRight delay-secound">
          <i class="tim-icons dashboard-header-cards-icon icon-bank" :style="{ background: '#ff8f8f', color: '#ff3d3d' }"></i>
          <h5 class="card-category" :style="{ color: '#ff3d3d' }">تعداد ملک بازدید شده</h5>
          <h3 class="card-title">
            <ICountUp
              v-if="can('see-details-estate')"
              :endVal="this.$store.state.me.visited_estate_count"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' ملک'
              }"
            />
            <span v-else>نا مشخص</span>
          </h3>
          <p class="card-text text-muted" :style="{fontSize: '10px'}">تعداد ملک هایی که تا کنون بازدید کرده اید</p>
        </card>
      </div>

      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInRight delay-last">
          <i class="tim-icons dashboard-header-cards-icon icon-chart-bar-32" :style="{ background: '#85ffdb', color: '#20c997' }"></i>
          <h5 class="card-category" :style="{ color: '#20c997' }">تعداد آگهی ثبت شده</h5>
          <h3 class="card-title">
            <ICountUp
              :endVal="this.$store.state.me.registered_estate_count"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' آگهی'
              }"
            />
          </h3>
          <p class="card-text text-muted" :style="{fontSize: '10px'}">تعداد آگهی هایی که تا کنون ثبت کرده اید</p>
        </card>
      </div>

      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInLeft delay-secound">
          <i class="tim-icons dashboard-header-cards-icon icon-bulb-63" :style="{ background: '#fbc190', color: '#fd7e14' }"></i>
          <h5 class="card-category" :style="{ color: '#fd7e14' }">تعداد ملک قابل بازدید</h5>
          <h3 class="card-title">
            <ICountUp
              v-if="can('see-details-estate')"
              :endVal="this.$store.state.me.remaining_hits_count"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' ملک'
              }"
            />
            <span v-else>نامحدود</span>
          </h3>
          <p class="card-text text-muted" :style="{fontSize: '10px'}">تعداد ملک هایی که میتوانید بازدید کنید</p>
        </card>
      </div>

      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInLeft delay-last">
          <i class="tim-icons dashboard-header-cards-icon icon-pencil" :style="{ background: '#7cb6f5', color: '#007bff' }"></i>
          <h5 class="card-category" :style="{ color: '#007bff' }">تعداد آگهی قابل ثبت</h5>
          <h3 class="card-title">
            <ICountUp
              v-if="can('create-estate')"
              :endVal="this.$store.state.me.remaining_registered_count"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' آگهی'
              }"
            />
            <span v-else>نامحدود</span>
          </h3>
          <p class="card-text text-muted" :style="{fontSize: '10px'}">تعداد آگهی که میتوانید ثبت کنید</p>
        </card>
      </div>
    </div>

    <card class="row ml-0 account-expire-timer animated bounceInRight delay-last" v-if="$store.state.me.validity_end_at">
      <div class="text-right pull-right w-100">
        <i class="tim-icons icon-time-alarm ml-2"></i>
        <span class="d-block mb-2" :style="{ fontSize: '18px' }">
          <b>هدف ما رضایت هرچه بیشتر شماست</b>
        </span>
        {{ isBefore($store.state.me.validity_end_at) ? 'پلن شما منقضی شده است ، از قسمت پایین همین صفحه میتوانید اعتبار خود را شارژ نمایید' : 'زمان انقضاء پلن مالی شما در ساعت شمار رو به رو مشخص شده است' }}
      </div>

      <flip-clock
        v-if="!isBefore($store.state.me.validity_end_at)"
        :options="{
          label: false,
          digit: expirationTime,
          countdown: true,
          clockFace: 'DailyCounter',
        }"
      />

      <flip-clock
        v-else
        :options="{
          label: false,
          digit: 0,
          countdown: true,
          clockFace: 'DailyCounter',
        }"
      />
    </card>

    <div class="row text-right ml-0 w-100 animated bounceInRight delay-last" dir="rtl">
      <base-alert
        v-for="message in messages"
        :key="message.id"
        :type="message.type"
        class="col-md-12"
        dismissible
      >
        <span class="d-block mb-2" :style="{ fontSize: '18px' }">
          <b>{{ message.title }}</b>
        </span>
        {{ message.message }}
      </base-alert>
    </div>

    <div class="row text-right" v-if="articles && articles.length && false">
      <div class="col-md-12 text-right">
        <h2 class="animated bounceInRight delay-first mb-0">
          آخرین مقالات
          <i class="tim-icons icon-single-copy-04" :style="{fontSize: '20px'}"></i>
        </h2>
        <h6 class="text-muted animated bounceInRight delay-secound">آخرین مقالاتی که در وبلاگ ثبت کرده اید</h6>
      </div>
    </div>

    <div class="row owl-carousel" v-if="articles && articles.length && false">
      <card v-for="article in articles" :key="article.id" class="text-right">
        <img slot="image" class="card-img-top" :src="article.image ? article.image.small : '/images/placeholder.png'" alt="article image"/>
        <h4 class="card-title">
          {{ article.title | truncate(60) }}
          <span class="d-block badge badge-info mt-2 ml-1 hvr-grow-shadow hvr-icon-grow" v-if="article.reading_time">
            <i class="tim-icons icon-bullet-list-67 hvr-icon"></i>
            مطالعه در{{ article.reading_time }} دقیقه
          </span>
        </h4>
        <p class="card-text text-muted">{{ article.description | truncate(100) }}</p>

        <transition-group name="list">
          <span
            v-for="item in article.subjects.filter( (category, index) => index < 3)"
            :key="item.id"
            class="badge badge-default ml-1 hvr-grow-shadow hvr-icon-grow">
            <i class="tim-icons icon-bullet-list-67 hvr-icon"></i>
            {{ item.title }}
          </span>

          <el-dropdown v-if="article.subjects.length > 3" :key="article.subjects.map((c) => c.id).join(',')">
            <span class="el-dropdown-link badge badge-default">
              باقی گروه ها <i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item
                v-for="item in article.subjects.filter( (category, index) => index < 3)"
                :key="item.id">
                {{ item.title }}
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </transition-group>
      </card>
    </div>

    <div class="row text-right" v-if="favorites && favorites.length && false">
      <div class="col-md-12 text-right">
        <h2 class="animated bounceInRight delay-first mb-0">
          آخرین املاک نشان شده
          <i class="tim-icons icon-single-copy-04" :style="{fontSize: '20px'}"></i>
        </h2>
        <h6 class="text-muted animated bounceInRight delay-secound">آخرین املاکی که در وبسایت نشان کرده اید</h6>
      </div>
    </div>

    <div class="row owl-carousel" v-if="favorites && favorites.length && false">
      <card v-for="favorite in favorites" :key="favorite.id" class="text-right">
        <!-- <img slot="image" class="card-img-top" :src="favorite.image ? article.image.small : '/images/placeholder.png'" alt="article image"/> -->
        <h4 class="card-title">
          {{ favorite.title | truncate(60) }}
        </h4>
        <p class="card-text text-muted">{{ favorite.description | truncate(100) }}</p>
      </card>
    </div>

    <div class="row text-right" v-if="plans && plans.length">
      <div class="col-md-12 text-right">
        <h2 class="animated bounceInRight delay-first mb-0">
          پلن های موجود
          <i class="tim-icons icon-single-copy-04" :style="{fontSize: '20px'}"></i>
        </h2>
        <h6 class="text-muted animated bounceInRight delay-secound">پلن های موجود جهت افزایش اعتبار حساب</h6>
      </div>
    </div>

    <div class="row owl-carousel" v-if="plans && plans.length">
      <card v-for="plan in plans" :key="plan.id" class="plan-card text-right" :style="{ border: `1px solid ${plan.color}`, height: '380px' }">
        <h2 class="card-title text-center">
          <b :style="{ color: isLight(plan.color) ? darken(plan.color, .6) : lighten(plan.color, .6) }">{{ plan.title | truncate(10) }}</b>

          <span :style="{ color: isLight(plan.color) ? '#6c757d' : '#ffffff', fontSize: '14px' }">
            (
              <span v-if="plan.credit_days_count === 30">
                ماهانه
              </span>
              <span v-else-if="plan.credit_days_count === 360">
                یکساله
              </span>
              <span v-else-if="plan.credit_days_count % 30 === 0">
                {{ plan.credit_days_count / 30 }} ماهه
              </span>
              <span v-else>
                {{ plan.credit_days_count }} روزه
              </span>
            )
          </span>
        </h2>

        <div class="plan-price text-center">
          <h2 class="m-0" :style="{ color: isLight(plan.color) ? darken(plan.color, .6) : lighten(plan.color, .6) }">
            {{ plan.price / 1000 }}
          </h2>
          <span :style="{ color: isLight(plan.color) ? '#6c757d' : '#ffffff' }">هزار تومان</span>
        </div>
        
        <p class="text-center mt-5">
          قابلیت مشاهده <span :style="{ color: isLight(plan.color) ? darken(plan.color, .3) : plan.color }">{{ plan.visited_estate_count }}</span> ملک
        </p>
        <p class="text-center">
          قابلیت ثبت <span :style="{ color: isLight(plan.color) ? darken(plan.color, .3) : plan.color }">{{ plan.registered_estate_count }}</span> ملک
        </p>

        <el-popover
          placement="top-end"
          width="250"
          trigger="hover"
          :disabled="typeof plan.description === 'string' ? plan.description.length <= 120 : false"
          :title="plan.title"
          :content="plan.description"
        >
          <p slot="reference" class="card-text text-muted text-center mt-3">{{ plan.description | truncate(120) }}</p>
        </el-popover>

        <div class="d-flex justify-content-center text-center mt-2">
          <base-button
            @click="buyPlan(plan)"
            size="sm"
            :style="{
              color: isLight(plan.color) ? '#555' : '#fff',
              borderRadius: '30px',
              background: `linear-gradient(to right, ${plan.color}, ${darken(plan.color, .1)}) !important`,
              boxShadow: `0px 5px 10px -4px ${plan.color}, 0px 4px 6px -5px #000 !important`
            }"
            class="pull-left buy-plan-button"
          >
            <i class="tim-icons icon-cart"></i>
            خرید و افزایش اعتبار
          </base-button>
        </div>

        <span
          v-if="$store.state.me.plan && $store.state.me.plan.id == plan.id"
          class="badge badge-success plan-active-label" 
          :style="{
            background: isLight(plan.color) ? darken(plan.color, .6) : lighten(plan.color, .6),
            color: plan.color
          }"
        >
          فعال
        </span>

        <div
          class="circle-color"
          :style="{
            background: `linear-gradient(to right, ${plan.color}, ${darken(plan.color, .1)})`,
            boxShadow: `0px 5px 10px -4px ${plan.color}, 0px 4px 6px -5px #000 !important`
          }"></div>
      </card>
    </div>

    <md-dialog :md-active.sync="is_open" class="text-right" dir="rtl">
      <md-dialog-title>
        <h2 class="modal-title">
          خرید 
          {{ selected_plan.title }}

          <span class="text-muted" :style="{ fontSize: '14px' }">
            (
              <span v-if="selected_plan.credit_days_count === 30">
                ماهانه
              </span>
              <span v-else-if="selected_plan.credit_days_count === 360">
                یکساله
              </span>
              <span v-else-if="selected_plan.credit_days_count % 30 === 0">
                {{ selected_plan.credit_days_count / 30 }} ماهه
              </span>
              <span v-else>
                {{ selected_plan.credit_days_count }} روزه
              </span>
            )
          </span>
        </h2>
        <p>از طریق فرم زیر میتوانید کد تخفیف خود را اعمال کرده و به درگاه پرداخت متصل باشید</p>
      </md-dialog-title>

      <div class="md-dialog-content">
        <div class="p-2">
          <form @submit.prevent>
            <div dir="ltr" class="promocode-input">
              <el-input dir="rtl" placeholder="اگر کد تخفیف دارید وارد کنید" maxlength="30" v-model="promocode" class="input-with-select">
                <el-button 
                  @click="checkPromocode"
                  slot="prepend"
                  icon="el-icon-scissors"
                  :style="{
                    background: '#ff8d72',
                    color: '#fff',
                    boxShadow: '0px 5px 10px -4px #ff8d72, 0px 4px 6px -5px #000 !important',
                    borderRadius: '30px',
                    outline: 'none'
                  }"
                >
                  اعمال کد تخفیف
                </el-button>
              </el-input>
            </div>
            <br/>

            <el-input
              type="textarea"
              :autosize="{ minRows: 4, maxRows: 6}"
              maxlength="250"
              placeholder="اگر توضیحی درباره پرداخت خود دارید وارد کنید"
              v-model="payment_description">
            </el-input>
            <br/>

            <el-button 
              @click="createPayment"
              class="w-100 mt-3"
              :style="{
                background: '#67c23a',
                color: '#fff',
                boxShadow: '0px 5px 10px -4px #67c23a, 0px 4px 6px -5px #000 !important',
                borderRadius: '30px',
                outline: 'none'
              }"
            >
              جهت پرداخت مبلغ
              <b>
                <ICountUp
                  :style="{display: 'inline'}"
                  :endVal="final_price"
                  :options="{
                    useEasing: true,
                    useGrouping: true,
                  }"
                />
              </b>
              تومان کلیک کنید
            </el-button>
          </form>
        </div>
      </div>

      <md-dialog-actions v-if="false">
        <base-button
          class="ml-2"
          type="danger"
          size="sm"
          @click="is_open = false"
        >
          <i class="tim-icons icon-simple-remove"></i>
          لغو
        </base-button>
        
        <base-button
          size="sm" 
          :loading="is_mutation_loading"
          type="success"
          @click="createPayment"
        >
          <transition name="fade" mode="out-in">
            <semipolar-spinner
              :animation-duration="2000"
              :size="17"
              color="#fff"
              v-if="is_mutation_loading"
            />
            <span v-else class="pull-right ml-2" >
              <i class="tim-icons icon-credit-card"></i>
            </span>
          </transition>
          پرداخت صورت حساب
        </base-button>
      </md-dialog-actions>
    </md-dialog>

    <transition name="loading">
      <div class="query-loader" v-if="is_query_loading">
        <half-circle-spinner
          :animation-duration="800"
          :size="40"
          color="#fff"
        />
      </div>
    </transition>
  </div>
</template>
<script>
import BaseTable from '../components/BaseTable'
import BaseAlert from '../components/BaseAlert'
import LineChart from '../components/Charts/LineChart'
import ICountUp from 'vue-countup-v2'
import { FlipClock } from '@mvpleung/flipclock';
import {SemipolarSpinner, HalfCircleSpinner, FingerprintSpinner} from 'epic-spinners'

import moment from 'moment'
import Color from 'color'
import filtersHelper from '../mixins/filtersHelper'
import createMixin from '../mixins/createMixin';

export default {
  components: {
    BaseTable,
    BaseAlert,
    LineChart,
    ICountUp,
    FlipClock,
    SemipolarSpinner, HalfCircleSpinner, FingerprintSpinner
  },
  mixins: [
    createMixin,
    filtersHelper,
  ],
  metaInfo: {
    title: 'داشبورد',
  },
  data() {
    return {
      type: 'site_info',
      group: 'setting',

      charts: {
        extraOptions: {
          maintainAspectRatio: false,
          legend: {
            display: false
          },
          responsive: true,
          tooltips: {
            backgroundColor: '#f5f5f5',
            titleFontColor: '#333',
            bodyFontColor: '#666',
            bodySpacing: 4,
            xPadding: 12,
            mode: "nearest",
            intersect: 0,
            position: "nearest"
          },
          scales: {
            yAxes: [{
              display: false,
              gridLines: {
                drawBorder: false,
                color: 'rgba(29,140,248,0.0)',
                zeroLineColor: "transparent",
              },
              ticks: {
                // suggestedMin: 50,
                // suggestedMax: 110,
                padding: 20,
                fontColor: "#fff"
              }
            }],
        
            xAxes: [{
              barPercentage: 1.6,
              gridLines: {
                drawBorder: false,
                color: 'rgba(220,53,69,0.1)',
                zeroLineColor: "transparent",
              },
              ticks: {
                padding: 20,
                fontColor: "#fff"
              }
            }]
          }
        },
        chartData: {
          labels: [],
          datasets: [{
            label: `تعداد ملک بازدید شده `,
            fill: true,
            borderColor: '#F9631A',
            borderWidth: 2,
            borderDash: [],
            borderDashOffset: 0.0,
            pointBackgroundColor: '#F9634A',
            pointBorderColor: 'rgba(255,255,255,0)',
            pointHoverBackgroundColor: '#F963AA',
            pointBorderWidth: 20,
            pointHoverRadius: 4,
            pointHoverBorderWidth: 15,
            pointRadius: 4,
            data: [],
          }, {
            label: `تعداد ملک ثبت شده `,
            fill: true,
            borderColor: '#ff3d3d',
            borderWidth: 2,
            borderDash: [],
            borderDashOffset: 0.0,
            pointBackgroundColor: '#ff3d9d',
            pointBorderColor: 'rgba(255,255,255,0)',
            pointHoverBackgroundColor: '#ff3d9ds',
            pointBorderWidth: 20,
            pointHoverRadius: 4,
            pointHoverBorderWidth: 15,
            pointRadius: 4,
            data: [],
          }]
        }
      },
      articles: [],
      plans: [],
      favorites: [],
      messages: [],

      promocode: null,
      promocode_error: '',
      has_promocode_error: false,
      is_loaded: false,
      final_price: 0,
      is_open: false,
      is_query_loading: false,
      is_mutation_loading: false,
      payment_description: '',
      selected_plan: {
        // 
      }
    }
  },
  computed: {
    viewCredit()
    {
      if ( !this.$store.state.me.plan.id )
        return 0

      if ( this.$store.state.me.remaining_hits_count > this.$store.state.me.plan.visited_estate_count )
        return 100

      else
        return Math.round( this.$store.state.me.remaining_hits_count / this.$store.state.me.plan.visited_estate_count * 100 )
    },
    registerCredit()
    {
      if ( !this.$store.state.me.plan.id )
        return 0

      if ( this.$store.state.me.remaining_registered_count > this.$store.state.me.plan.registered_estate_count )
        return 100

      else
        return Math.round( this.$store.state.me.remaining_registered_count / this.$store.state.me.plan.registered_estate_count * 100 )
    },
    expirationTime()
    {
      return ( moment( this.$store.state.me.validity_end_at ) * 1 - moment() * 1 ) / 1000
    },
  },
  methods: {
    isBefore(date) {
      return moment(date).isBefore( moment() )
    },
    isLight(color) {
      if (!color)
        return true
      
      return Color(color).isLight();
    },
    isDark(color) {
      if (!color)
        return false

      return Color(color).isDark();
    },
    darken(color, depth = 0.5) {
      if (!color)
        return '#000'

      return Color(color).darken(depth);
    },
    lighten(color, depth = 0.5) {
      if (!color)
        return '#fff'

      return Color(color).lighten(depth);
    },
    can(permission) {
      return !this.$store.state.permissions.includes(permission)
    },
    buyPlan(plan)
    {
      this.promocode = ''
      this.has_promocode_error = false
      this.selected_plan = plan
      this.final_price = plan.price
      this.is_open = true
    },
    checkPromocode()
    {
      if ( this.promocode !== '' )
      {
        this.is_query_loading = true

        axios.post('/graphql/auth', {
          query: `mutation {
            validatePromocode(code: "${this.promocode}", plan: ${this.selected_plan.id})
            {
              count
              status
              message
            }
          }`
        })
        .then(({data}) => {
          this.is_query_loading = false

          if ( data.data.validatePromocode.status === 400 )
          {
            this.promocode = null

            return this.$swal.fire({
              title: `اعمال نشد`,
              text: data.data.validatePromocode.message,
              type: 'error',
              timer: 1000,
              showConfirmButton: false,
            })
          }

          this.final_price = this.selected_plan.price - ( this.selected_plan.price * data.data.validatePromocode.count / 100 )

          this.$swal.fire({
            title: `اعمال شد`,
            text: `کد تخفیف مورد نظر شما با موفقیت اعمال شد`,
            type: 'success',
            timer: 1000,
            showConfirmButton: false,
          })
        })
        .catch(error => {
          this.is_query_loading = false

          this.promocode = null

          return this.$swal.fire({
            title: `معتبر نیست`,
            text: 'کد تخفیف مورد نظر شما معتبر نیست یا منقضی شده است',
            type: 'error',
            timer: 1000,
            showConfirmButton: false,
          })
        })
      }
    },
    createPayment()
    {
      this.is_mutation_loading = true

      axios.post('/graphql/auth', {
        query: `mutation {
          createPayment(
            plan: ${this.selected_plan.id},
            promocode: "${this.promocode}",
            description: "${this.payment_description}"
          ) {
            code
            amount
          }
        }`
      })
      .then(({data}) => {

        this.$swal.fire({
          title: `چند لحظه صبر کنید`,
          text: `تا چند لحظه دیگر به درگاه پرداخت هدایت خواهید شد ، لطفا شکیبا باشید`,
          type: 'success',
          timer: 3000,
          showConfirmButton: false,
        })

        setTimeout(() => window.location = `/api/v1/payment/${data.data.createPayment.code}`, 3000);
      })
      .catch(error => {
        this.is_query_loading = false
        this.is_mutation_loading = false

        this.promocode = null

        this.errorResolver(error)
      })
    }
  },
  mounted()
  {
    if ( this.$route.query.message )
    {
      setTimeout(() => {
        this.$swal.fire({
          title: this.$route.query.message_title || '',
          html: this.$route.query.message,
          type: this.$route.query.message_type || 'error',
          // timer: 3000,
          showConfirmButton: true,
          confirmButtonText: 'باشه'
        })
        .then(({value}) => this.$router.push('/panel') )
      }, 4000);
    }

    require('owl.carousel/dist/owl.carousel.js')

    // articles {
    //   data {
    //     id title description reading_time image { id file_name small } subjects { id title }
    //   }
    // }

    // favorites {
    //   data {
    //     id title address sales_price mortgage_price rental_price area is_active
    //     assignment { id title color has_sales_price has_mortgage_price has_rental_price }
    //     estate_type { id title }
    //     photos { id file_name thumb }
    //   }
    // }

    axios.post('/graphql/auth', {
      query: `{
        plans {
          data {
            id title description color price visited_estate_count registered_estate_count credit_days_count
          }
        }

        me { messages { id title message type } }

        visitedEstates { month count }
        registeredEstates { count }
      }`
    })
    .then(({data}) => {
      this.charts.chartData.labels = data.data.visitedEstates.map(i => i.month)
      this.charts.chartData.datasets[0].data = data.data.visitedEstates.map(i => i.count)
      this.charts.chartData.datasets[1].data = data.data.registeredEstates.map(i => i.count)
      // this.favorites = data.data.favorites.data
      // this.articles = data.data.articles.data
      this.plans = data.data.plans.data
      this.messages = data.data.me.messages

      this.is_loaded = true
    })
    .then(() => {
      $('.owl-carousel').owlCarousel({
        rtl: true,
        nav: false,
        loop: false,
        margin: 10,
        responsive: {
            0: { items:1 },
            600: { items:2 },
            1000: { items: 3 },
            1200: { items: 4 }
        }
      })
    })
  },
};
</script>

<style>

.plan-card {
  overflow: hidden;
}
.plan-card h2, .plan-card p, .plan-card .plan-price {
  position: relative;
  z-index: 10;
}
.plan-card .circle-color {
  position: absolute;
  top: -550px;
  left: 50%;
  transform: translateX(-50%);
  width: 500px;
  height: 700px;
  border-radius: 50%;
}

.card .card-image img {
  max-height: 150px;
}

.promocode-input .el-input-group__prepend {
  border-radius: 30px 0px 0px 30px;
}
.promocode-input input {
  border-radius: 0px 30px 30px 0px;
  border-left: none;
}
.el-textarea textarea {
  border-radius: 20px;
}

.plan-price h2 {
  font-size: 50px;
  /* color: #ff1d5e !important; */
  font-weight: bold;
}
.plan-price span {
  display: block;
  margin-top: -12px;
  margin-bottom: 15px;
}
.buy-plan-button {
  position: absolute !important;
  bottom: 15px !important;
}
.dashboard-header-cards-icon {
  margin: 0px;
  padding: 8px;
  border-radius: 50%;
  float: right;
  font-weight: bold !important;
}
.dashboard-header-cards-icon + h5 {
  margin-top: 8px;
  font-weight: bold;
}
.plan-active-label {
  position: absolute;
  top: 3px;
  right: -22px;
  z-index: 100;
  width: 80px;
  transform: rotate(35deg);
}

.account-expire-timer {
  height: 80px;
  color: #fff;
  background: linear-gradient(to left, #ff1d5e, #f87900, #f83600) !important;
}
.account-expire-timer i.tim-icons {
  margin: 0px;
  float: right;
  font-size: 30px;
  background: linear-gradient(to right, #ffd400, #ffc300);
  box-shadow: 0px 3px 10px -4px #ffc107, 0px 3px 6px -5px #000;
  padding: 10px;
  border-radius: 50%;
}
.account-expire-timer .flip-clock {
  position: absolute;
  left: 15px;
  width: auto;
}
.account-expire-timer .flip-clock-wrapper ul li a div div.inn {
  background: #373737;
  color: #f0f0f0;
}

.row.owl-carousel.owl-loaded.owl-drag {
  margin: 0px;
}

@media screen and (max-width: 800px) {

  .account-expire-timer {
    height: 160px;
  }
  .account-expire-timer .flip-clock {
    left: 50%;
    transform: translateX(-50%);
    bottom: 10px;
  }
}

@media screen and (max-width: 480px) {

  .account-expire-timer {
    height: 180px;
  }
  .account-expire-timer .flip-clock {
    transform: translateX(-50%) scale(.6);
    bottom: 5px;
  }
}
</style>