<template>
  <div :style="{ position: 'relative', zIndex: 10 }">
    <div class="row" v-if="is_loaded">
      <div class="col-12 text-right">
        <div class="pull-right">
          <h1 class="animated bounceInRight delay-first" :style="{ color: '#fff', fontWeight: 'bold', textShadow: '0px 3px 15px #333' }">
            <span v-if="$route.params.id" dir="rtl">
              ویرایش <span :style="{ color: '#ff3d3d', fontSize: '24px' }">{{ form_data.title }}</span>
            </span>
            <span v-else>
              ثبت <span :style="{ color: '#ff3d3d' }">ملک</span> جدید
            </span>
            <i class="header-nav-icon tim-icons icon-align-left-2" :style="{fontSize: '25px'}"></i>
          </h1>
          <h6 class="header-description animated bounceInRight delay-secound">از طریق فرم زیر میتوانید به صورت کامل اطلاعات ملک خود را به ثبت برسانید</h6>
        </div>
        <div class="pull-left animated bounceInDown delay-last">
          <base-button @click="$router.push('/panel/estate/mine')" size="sm" type="warning" class="pull-left">
            <i class="tim-icons icon-double-left"></i>
            بازگشت
          </base-button>
        </div>
      </div>
    </div>

    <md-tabs class="animated bounceInUp delay-last" v-show="is_loaded" md-active-tab="tab-info" @md-changed="changeTab" v-if="canCreateEstate">
      <md-tab id="tab-images" md-label="تصاویر و ویدیو" dir="ltr">
        <div class="row">
          <div class="col-md-5" :style="{ borderLeft: '1px solid #c7c7c7' }">
            <md-field :class="getValidationClass('aparat_video')">
              <label>لینک ویدیو آپارات</label>
              <!-- <md-input v-model="aparat_video" :maxlength="$v.aparat_video.$params.maxLength.max" /> -->
              <md-input v-model="form_data.aparat_video" />
              <i class="md-icon tim-icons icon-video-66"></i>
              <span class="md-helper-text">برای مثال : https://www.aparat.com/v/TJcin</span>
            </md-field>

            <br/>

            <div id="aparat_video_frame" v-if="is_loaded_video">
              <script type="text/JavaScript" :src="`https://www.aparat.com/embed/${form_data.aparat_video.replace('https://www.aparat.com/v/', '').trim()}?data[rnddiv]=aparat_video_frame&data[responsive]=yes`">
              </script>
            </div>
          </div>

          <div class="col-md-7">
            <label>تصاویر ملک</label>

            <el-upload
              class="upload-photo-gallery"
              action="https://jsonplaceholder.typicode.com/posts/"
              :auto-upload="false"
              list-type="picture-card"
              :file-list="form_data.photos"
              :on-change="handleSelectPhotos"
              :on-remove="handleRemove">
              <i class="el-icon-plus"></i>
            </el-upload>
            <el-dialog :visible.sync="dialogVisible">
              <img width="100%" :src="dialogImageUrl" alt="">
            </el-dialog>
          </div>
        </div>
      </md-tab>

      <md-tab id="tab-address-map" md-label="آدرس و موقعیت">
        <div class="row">
          <div class="col-md-6">
            <md-field>
              <label>منطقه</label>
              <md-select v-model="area_id">
                <md-option v-for="area in areas" :key="area.id" :value="area.id">{{ area.name }}</md-option>
              </md-select>
              <span class="md-helper-text">لطفا نام منطقه این ملک را از لیست انتخاب کنید</span>
            </md-field>
          </div>

          <div class="col-md-6">
            <md-field>
              <label>خیابان</label>
              <md-select v-model="street_id">
                <md-option v-if="streets.length === 0">
                  {{ area_id ? 'در این منطقه هیچ خیابانی ثبت نشده است' : 'لطفا ابتدا منطقه مورد نظر خود را انتخاب کنید' }}
                </md-option>
                <md-option v-for="street in streets" :key="street.id" :value="street.id">{{ street.name }}</md-option>
              </md-select>
              <span class="md-helper-text">لطفا نام خیابان این ملک از لیست انتخاب کنید</span>
            </md-field>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-9">
            <md-field>
              <label>آدرس</label>
              <md-input v-model="form_data.address" :maxlength="100" />
              <i class="md-icon tim-icons icon-tag"></i>
              <span class="md-helper-text">برای مثال : خیابان بهارستان ، بهار ۴۳</span>
            </md-field>
          </div>

          <div class="col-md-3">
            <md-field>
              <label>پلاک</label>
              <md-input type="number" v-model="form_data.plaque" />
              <i class="md-icon tim-icons icon-tag"></i>
              <span class="md-helper-text">برای مثال : ۴۳</span>
            </md-field>
          </div>
        </div>
        <br/>

        <div class="row">
          <transition name="fade">
            <div class="col-md-12" v-if="form_data.address">
              آدرس نهایی‌ :

              <transition name="fade">
                <span v-if="area">{{ area.name }} ،</span>
              </transition>
              <transition name="fade">
                <span v-if="street">خیابان {{ street.name }} ، </span>
              </transition>
              {{ form_data.address }}
              <transition name="fade">
                <span v-if="form_data.plaque">، پلاک {{ form_data.plaque }}</span>
              </transition>
            </div>
          </transition>
        </div>

        <div class="row p-4" v-if="form_data.coordinates && form_data.coordinates.lat && is_loaded" >
          <l-map
            v-show="form_data.coordinates && form_data.coordinates.lat"
            ref="map"
            style="height: 300px; width: 100%"
            :options="{
              key: 'web.8HU2Ho1RdkaEAT79wJPPdc1ddkgRH0iPIcSqBThP'
            }"
            :zoom="zoom"
            :center.sync="form_data.coordinates"
            :zoomAnimation="true"
            :zoomAnimationThreshold="10"
            :fadeAnimation="true"
            :markerZoomAnimation="true"
            :noBlockingAnimations="true"
            @update:zoom="zoomUpdated"
            @update:center="centerUpdated"
            @update:bounds="boundsUpdated"
          >
            <l-tile-layer url="http://{s}.tile.osm.org/{z}/{x}/{y}.png"></l-tile-layer>
            <l-marker draggable :lat-lng.sync="form_data.coordinates">
              <l-icon
                :icon-size="[40, 50]"
                :icon-anchor="[20, 50]"
                icon-url="/location_marker.svg" >
              </l-icon>   
            </l-marker>
            <!-- <l-circle-marker ref="location" :lat-lng="location" v-if="location" :radius="8" color="#0043ff" /> -->
            <l-control position="bottomright">
              <el-tooltip content="موقعیت من">
                <base-button @click="location ? $refs.map.setCenter({ lat: location[0], lng: location[1] }) : false" class="pt-2" type="success" size="sm" icon round>
                  <i class="material-icons m-0 hvr-icon" :style="{ paddingTop: '5px' }">my_location</i>
                </base-button>
              </el-tooltip>

              <el-tooltip content="موقعیت ملک">
                <base-button @click="$refs.map.setCenter({ lat: form_data.coordinates.lat, lng: form_data.coordinates.lng })" class="pt-2" type="warning" size="sm" icon round>
                  <i class="material-icons m-0 hvr-icon">room</i>
                </base-button>
              </el-tooltip>
            </l-control>
          </l-map>
        </div>
      </md-tab>

      <md-tab id="tab-advantages" md-label="امکانات ملک">
        <base-input label="امکانات ملک" v-if="features && features.length">
          <el-checkbox-group v-model="form_data.features" class="row features-checkbox">
            <el-checkbox
              class="col-md-3 m-0"
              v-for="item in features"
              :value="item.id"
              :key="item.id"
              :label="item.id"
            >
              {{ item.title }}
            </el-checkbox>
          </el-checkbox-group>
          <small slot="helperText" class="form-text text-muted">لطفا از بخش بالا کلیه امکانات ملک خود را مشخص کنید</small>
          <br/>
        </base-input>

        <div class="row">
          <div class="col-md-6 advantages">
            <md-chips v-model="form_data.advantages" md-placeholder="افزودن مزیت ...">
              <label>مزایای ملک</label>
              <span class="md-helper-text">مزیت های ملک خود را در این قسمت وارد کنید</span>
            </md-chips>
          </div>
          <div class="col-md-6 disadvantages">
            <md-chips v-model="form_data.disadvantages" md-placeholder="افزودن عیب ...">
              <label>معایب ملک</label>
              <span class="md-helper-text">معایب ملک خود را در این قسمت وارد کنید</span>
            </md-chips>
          </div>
        </div>
        <br/>

        <md-chips v-model="form_data.tags" md-placeholder="افزودن کلمه کلیدی ...">
          <label>کلمات کلیدی ملک</label>
          <span class="md-helper-text">کلمات کلیدی مرتبط با ملک خود را وارد کنید</span>
        </md-chips>
        <br/>

        <md-field>
          <label>توضیح کوتاه</label>
          <md-textarea v-model="form_data.description" md-autogrow :maxlength="250"></md-textarea>
          <i class="md-icon tim-icons icon-align-center"></i>
          <span class="md-helper-text">توضیحی مختصر درباره این ملک</span>
        </md-field>
        <br/>
      </md-tab>

      <md-tab id="tab-specifications" md-label="مشخصات ملک" :md-disabled="!form_data.spec">
        <div v-if="form_data.spec">
          <div v-for="(header, index) in form_data.spec.headers" :key="header.id">
            <h3 class="mb-0">
              <i class="text-warning tim-icons icon-molecule-40"></i>
              {{ header.title }}
            </h3>
            <span class="text-muted mb-3">{{ header.description }}</span>

            <div class="row">
              <div
                :class="getWidthClass(row, indexRow, header.rows.length) ? 'col-md-12' : 'col-md-6'
                "
                v-for="(row, indexRow) in header.rows"
                :key="row.id"
              >
                <br/>
                <md-chips
                  v-model="specifications[row.id]"
                  v-if="row.defaults.length === 0 && row.is_multiple"
                  md-placeholder="افزودن ..."
                >
                  <label>{{ row.title }} <span v-if="row.is_required" class="text-danger">*</span></label>
                  <span class="md-helper-text">{{ row.description }}</span>
                </md-chips>

                <md-field v-else>
                  <label>{{ row.title }} <span v-if="row.is_required" class="text-danger">*</span></label>
                  <span class="md-prefix">{{ row.prefix }}</span>

                  <md-input
                    v-model="specifications[row.id]"
                    v-if="row.defaults.length === 0 && !row.is_multiple"
                  />

                  <md-select
                    :multiple="row.is_multiple"
                    v-model="specifications[row.id]"
                    v-if="row.defaults.length !== 0"
                  >
                    <md-option v-for="value in row.defaults" :key="value.id" :value="value.id">{{ value.value }}</md-option>
                  </md-select>

                  <span class="md-suffix">{{ row.postfix }}</span>
                  <span class="md-helper-text" :title="row.help">{{ row.help }}</span>
                </md-field>
              </div>
            </div>

            <hr v-if="index !== form_data.spec.headers.length - 1" />
          </div>

          <transition name="fade">
            <md-empty-state
              v-show="form_data.spec.headers.length === 0"
              md-icon="search"
              md-label="متاسفانه هیچ داده ای یافت نشد :("
              md-description="لطفا یک جدول معتبر انتخاب کنید که دارای سطر و ردیف باشد">
            </md-empty-state>
          </transition>
        </div>
      </md-tab>

      <md-tab id="tab-info" md-label="درباره ملک">
        <div class="row">
          <div class="col-md-6">
            <md-field>
              <label>عنوان ملک</label>
              <md-input v-model="form_data.title" :maxlength="50" />
              <i class="md-icon tim-icons icon-tag"></i>
              <span class="md-helper-text">برای مثال : ملک لاکچری ۱۰۰ متری هاشمیه</span>
            </md-field>
          </div>

          <div class="col-md-6">
            <md-field>
              <label>کد ملک</label>
              <md-input v-model="form_data.code" :maxlength="20" />
              <i class="md-icon tim-icons icon-badge"></i>
              <span class="md-helper-text">برای مثال : EST-1002</span>
            </md-field>
          </div>
        </div>
        <br/>

        <div class="row">
          <div class="col-md-6">
            <md-field>
              <label>نام مالک</label>
              <md-input v-model="form_data.landlord_fullname" :maxlength="50" />
              <i class="md-icon tim-icons icon-tag"></i>
              <span class="md-helper-text">برای مثال : امیر امیری</span>
            </md-field>
          </div>

          <div class="col-md-6">
            <md-field>
              <label>شماره تلفن مالک</label>
              <md-input v-model="form_data.landlord_phone_number" :maxlength="20" />
              <i class="md-icon tim-icons icon-badge"></i>
              <span class="md-helper-text">برای مثال : ۰۹۱۲۳۴۵۶۷۸۹</span>
            </md-field>
          </div>
        </div>
        <br/>

        <div class="row">
          <div class="col-md-6">
            <md-field>
              <label>نوع واگذاری</label>
              <md-select v-model="form_data.assignment.id">
                <md-option v-for="item in $store.state.feature.assignment" :key="item.id" :value="item.id">{{ item.title }}</md-option>
              </md-select>
              <span class="md-helper-text">لطفا نوع واگذاری این ملک رو مشخص کنید</span>
            </md-field>
          </div>

          <div class="col-md-6">
            <md-field>
              <label>نوع ملک</label>
              <md-select v-model="form_data.estate_type.id">
                <md-option v-for="item in $store.state.feature.estate_type" :key="item.id" :value="item.id">{{ item.title }}</md-option>
              </md-select>
              <span class="md-helper-text">لطفا نوع این ملک رو مشخص کنید</span>
            </md-field>
          </div>
        </div>
        <br/>

        <div class="row">
          <div class="col-md-6">
            <md-field>
              <label>متراژ</label>
              <md-input v-model="form_data.area" />
              <span class="md-suffix ml-2">متر</span>
              <i class="md-icon tim-icons icon-map-big"></i>
              <span class="md-helper-text" v-if="form_data.area">{{ form_data.area | numToPersian }} متر</span>
              <span class="md-helper-text" v-else>مساحت ملک بر اساس متر مربع</span>
            </md-field>
          </div>

          <div class="col-md-6">
            <remote-select
              v-if="is_loaded"
              type="labels"
              label="لیبل ملک"
              icon="icon-molecule-40"
              placeholder="لطفا لیبل ملک خود را انتخاب کنید"
              fields="title color"
              v-model="form_data.label.id"
              :defaults="[form_data.label]"
            >
              <template #content="props">
                <span :style="{ borderRight: `3px solid ${props.item.color}` }" class="pr-2">{{ props.item.title }}</span>
              </template>
            </remote-select>
          </div>
        </div>
        <br/>

        <div class="row">
          <div :class="priceFieldWithClass" v-if="getSelectedAssignment.has_sales_price">
            <md-field>
              <label>مبلغ کل</label>
              <md-input v-model="form_data.sales_price" />
              <span class="md-suffix ml-2">تومان</span>
              <i class="md-icon tim-icons icon-tag"></i>
              <span class="md-helper-text" v-if="form_data.sales_price">{{ form_data.sales_price | numToPersian }} تومان</span>
              <span class="md-helper-text" v-else>ارزش کل ملک بر حسب تومان</span>
            </md-field>
          </div>

          <div :class="priceFieldWithClass" v-if="getSelectedAssignment.has_mortgage_price">
            <md-field>
              <label>مبلغ رهن</label>
              <md-input v-model="form_data.mortgage_price" />
              <span class="md-suffix ml-2">تومان</span>
              <i class="md-icon tim-icons icon-tag"></i>
              <span class="md-helper-text" v-if="form_data.mortgage_price">{{ form_data.mortgage_price | numToPersian }} تومان</span>
              <span class="md-helper-text" v-else>مبلغ قابل پرداخت جهت ودیعه ملک</span>
            </md-field>
          </div>

          <div :class="priceFieldWithClass" v-if="getSelectedAssignment.has_rental_price">
            <md-field>
              <label>مبلغ اجاره</label>
              <md-input v-model="form_data.rental_price" />
              <span class="md-suffix ml-2">تومان</span>
              <i class="md-icon tim-icons icon-tag"></i>
              <span class="md-helper-text" v-if="form_data.rental_price">{{ form_data.rental_price | numToPersian }} تومان</span>
              <span class="md-helper-text" v-else>مبلغ قابل پرداخت جهت اجاره ملک</span>
            </md-field>
          </div>

          <base-input class="col-md-4" label="تمایل به همکاری" v-if="$store.state.roles.includes('consultant') && getSelectedAssignment">
            <el-switch
              dir="ltr"
              v-model="form_data.want_cooperation"
              active-text="تمایل دارم"
              active-color="#ff8d72"
              inactive-text="تمایل ندارم">
            </el-switch>
            <small slot="helperText" class="form-text text-muted">درصورت عدم تمایل ، لیبل مربوطه روی ملک شما در سایت قرار خواهد گرفت</small>
          </base-input>
        </div>
      </md-tab>
    </md-tabs>
    <card v-else v-show="is_loaded">
      <md-empty-state
        v-if="form_data.is_mine"
        md-icon="error"
        md-label="اعتبار شما به پایان رسیده است"
        md-description="اعتبار شما برای ثبت ملک جدید به پایان رسیده است ، لطفا پس از هدایت به داشبورد با کلیک بر روی دکمه زیر اقدام به شارژ اعتبار خود بنمایید">
        <router-link to="/panel">ورود به داشبورد</router-link>
      </md-empty-state>

      <md-empty-state
        v-else
        md-icon="error"
        md-label="آگهی متعلق به شما نیست"
        md-description="متاسفانه ملکی که قصد ویرایش آن را دارید متعلق به شما نیست و شما دسترسی لازم جهت ویرایش این ملک را ندارید ، لطفا از طریق لینک زیر وارد بخش املاک متعلق به خود شوید">
        <router-link to="/panel/estate/mine">ورود به ملک های من</router-link>
      </md-empty-state>
    </card>

    <div class="row animated bounceInBottom delay-last" v-if="is_loaded">
      <base-button
        v-if="activeTab !== 'tab-variations'"
        :loading="attr('is_mutation_loading')"
        size="sm"
        type="success"
        class="pull-left mt-3"
        @click="update()"
      >
        <transition name="fade" mode="out-in">
          <semipolar-spinner
            :animation-duration="2000"
            :size="17"
            color="#fff"
            v-if="attr('is_mutation_loading')"
          />
          <span v-else class="pull-right ml-2" >
            <i v-if="attr('is_creating')" class="tim-icons icon-simple-add"></i>
            <i v-else class="tim-icons icon-pencil"></i>
          </span>
        </transition>
        ذخیره
      </base-button>

      <base-button @click="$router.push('/panel/estate/mine')" size="sm" type="danger" class="pull-left mt-3 ml-2">
        <i class="tim-icons icon-simple-remove"></i>
        بازگشت
      </base-button>
    </div>

    <transition name="fade">
      <div class="main-panel-loading" v-if="!is_loaded">
        <fingerprint-spinner
          :animation-duration="1000"
          :size="100"
          color="#fff"
        />
      </div>
    </transition>

    <transition name="loading">
      <div class="query-loader" v-if="attr('is_query_loading')">
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
import RemoteSelect from '../../components/RemoteSelect'
import BaseTable from '../../components/BaseTable'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import Binding, { bind } from '../../mixins/binding'
import { validationMixin } from 'vuelidate'
import vuexHelper from '../../mixins/vuexHelper'
import { required, maxLength } from 'vuelidate/lib/validators'
import createMixin from '../../mixins/createMixin'
import filtersHelper from '../../mixins/filtersHelper'
import deleteMixin from '../../mixins/deleteMixin'
import {SemipolarSpinner, HalfCircleSpinner, FingerprintSpinner} from 'epic-spinners'
import persianJs from 'persianjs'
import moment from 'moment'
import numeral from 'numeral'

import { LMap, LTileLayer, LMarker, LIcon, LControl, LCircleMarker } from 'vue2-leaflet';
import 'leaflet/dist/leaflet.css'

export default {
  components: {
    BaseTable,
    RemoteSelect,
    LMap, LTileLayer, LMarker, LIcon, LControl, LCircleMarker,
    SemipolarSpinner, HalfCircleSpinner, FingerprintSpinner
  },
  mixins: [
    Binding,
    validationMixin,
    vuexHelper,
    createMixin,
    filtersHelper,
    deleteMixin
  ],
  metaInfo() {
    return {
      title: this.$route.params.id ? this.form_data.title : 'ثبت ملک',
    }
  },
  data() {
    return {
      zoom: 12,
      center: [36.2605, 59.6168],
      bounds: null,
      location: null,

      checkAll: false,
      isIndeterminate: true,

      type: 'estate',
      group: 'estate',

      form_data: {
        title: '',
        code: '',
        landlord_fullname: '',
        landlord_phone_number: '',
        description: '',
        aparat_video: '',
        address: '',
        plaque: '',
        area: 0,
        want_cooperation: true,
        is_mine: true,

        sales_price: null,
        rental_price: null,
        mortgage_price: null,

        coordinates: null,
        tags: [
          // 
        ],
        photos: [
          // 
        ],
        features: [
          // 
        ],
        advantages: [
          // 
        ],
        disadvantages: [
          // 
        ],
        label: {
          // 
        },
        assignment: {
          // 
        },
        estate_type: {
          // 
        },
        spec: null
      },
      specifications: {
        // 
      },
      deleted_images: [
        // 
      ],
      
      is_loaded: false,
      is_loaded_video: false,
      dialogImageUrl: '',
      dialogVisible: false,
      
      areas: [
        // 
      ],
      streets: [
        // 
      ],
      features: [
        // 
      ],
      area_id: null,
      street_id: null,
      activeTab: 'tab-info',
    }
  },
  validations: {
    title: {
      required,
      maxLength: maxLength(50)
    },
    code: {
      maxLength: maxLength(20)
    },
    description: {
      maxLength: maxLength(255)
    },
    aparat_video: {},
    label: {},
    status: {},
    review: {},
    note: {
      maxLength: maxLength(255)
    },
    advantages: {},
    disadvantages: {},
  },
  computed: {
    canCreateEstate() {
      return !this.can('create-estate') || (
           this.$store.state.me.remaining_registered_count > 0
        && this.$store.state.me.validity_end_at
        && !moment(this.$store.state.me.validity_end_at).isBefore( moment() )
        && this.form_data.is_mine
      )
    },
    allQuery()
    {
      return `
        id is_mine code title description aparat_video
        address plaque
        landlord_fullname landlord_phone_number
        street { id name area { id } } coordinates { lat lng }
        sales_price mortgage_price rental_price area
        advantages disadvantages
        label { id title color }
        tags { name }
        photos { id file_name thumb small }
        assignment { id } estate_type { id features { id title icon } }
        features { id title icon }
        want_cooperation
        spec {
          id title headers {
            id title description
            rows {
              id title description help
              prefix postfix is_multiple is_required
              data { id data values { id value } }
              defaults { id value }
            }
          }
        }
      `
    },
    getSelectedAssignment()
    {
      const data = this.$store.state.feature.assignment.filter(i => i.id === this.form_data.assignment.id)

      return data.length ? data[0] : false 
    },
    priceFieldWithClass()
    {
      const data = this.getSelectedAssignment
      const isConsultant = this.$store.state.roles.includes('consultant')

      if (data.length === 0)
        return isConsultant ? 'col-md-8' : 'col-md-12'

      let count = 0

      if ( data.has_sales_price ) ++count

      if ( data.has_mortgage_price ) ++count

      if ( data.has_rental_price ) ++count

      if ( isConsultant )
        return count === 2 ? 'col-md-4' : 'col-md-8'

      else
        return count === 2 ? 'col-md-6' : 'col-md-12'
    },
    area() {
      return _.find(this.areas, { 'id': this.area_id })
    },
    street() {
      return _.find(this.streets, { 'id': this.street_id })
    },
  },
  mounted()
  {
    axios.post('/graphql/auth', {
      query: `{ areas (per_page: 20) { data { id name coordinates { lat lng } } } }`
    })
    .then(({data}) => this.areas = _.orderBy( data.data.areas.data , ['name'], ['asc']))

    this.$store.dispatch('getData', {
      group: 'feature',
      type: 'assignment',
      query: `assignments(per_page: 20) {
        data { id title description color has_sales_price has_mortgage_price has_rental_price created_at updated_at }
        total trash chart { month count }
      }`
    })

    this.$store.dispatch('getData', {
      group: 'feature',
      type: 'estate_type',
      query: `estate_types(per_page: 20) {
        data { id title description assignments { id title } icon created_at updated_at }
        total trash chart { month count }
      }`
    })

    this.$watchLocation({ enableHighAccuracy: true })
    .then(({lat, lng}) => this.location = [lat, lng])

    if ( this.$route.params.id )
    {
      this.setAttr('selected', { id: this.$route.params.id })
      this.setAttr('is_creating', false)

      axios.post('/graphql/auth', {
        query: `{
          estate (id: "${this.$route.params.id}") { ${this.allQuery} }
        }`
      })
      .then(({data}) => {
        if ( data.data.estate.spec && data.data.estate.spec.headers.length )
          this.handleSpecification(data.data.estate.spec.headers)
        else if ( !data.data.estate.spec )
            data.data.estate.spec = { headers: [] }

        if ( data.data.estate.aparat_video )
          data.data.estate.aparat_video = `https://www.aparat.com/v/${data.data.estate.aparat_video}`
        
        if ( data.data.estate.tags.length !== 0 )
          data.data.estate.tags = data.data.estate.tags.map(i => i.name)

        if ( !data.data.estate.label )
          data.data.estate.label = { id: null }

        if ( data.data.estate.features && data.data.estate.features.length )
          data.data.estate.features = data.data.estate.features.map(i => i.id)

        if ( !data.data.estate.assignment )
          data.data.estate.assignment = { id: null }


        if ( !data.data.estate.estate_type )
          data.data.estate.estate_type = { id: null }

        else
          this.features = data.data.estate.estate_type.features

        if ( data.data.estate.street )
        {
          this.streets = [ data.data.estate.street ]
          this.street_id = data.data.estate.street.id
          this.area_id = data.data.estate.street.area.id
        }

        if ( !data.data.estate.spec )
          data.data.estate.spec = { id: null }
        
        if ( data.data.estate.photos.length !== 0 )
        {
          data.data.estate.photos = data.data.estate.photos.map(i => {
            return {
              id: i.id,
              name: i.file_name,
              url: i.small
            }
          })
        }
        
        this.form_data = data.data.estate;
      })
      .then(() => setTimeout(() => this.is_loaded = true, 1000))
      .catch( error => console.log(error.response) )
    }
    else 
    {
      setTimeout(() => {
        this.form_data.coordinates = {
          lat: 36.2605,
          lng: 59.6168
        }
      }, 1000);

      this.setAttr('selected', { id: null })
      this.setAttr('is_creating', true)
      this.is_loaded = true
    }
  },
  methods: {
    zoomUpdated (zoom) {
      this.zoom = zoom
    },
    centerUpdated (center) {
      this.center = center
    },
    boundsUpdated (bounds) {
      this.bounds = bounds
    },
    changeTab(activeTab)
    {
      // console.log( activeTab )
    },
    handleRemove(file, fileList)
    {
      this.form_data.photos = fileList
      this.deleted_images = [ ...this.deleted_images, file ]
    },
    handleSelectPhotos(file, fileList)
    {
      this.form_data.photos = _.uniqBy([ ...this.form_data.photos, file ], 'name')
    },
    getWidthClass(row, index, count)
    {
      if ( count % 2 === 1 && index === count - 1)
        return true

      return row.defaults.length === 0 && row.is_multiple
    },
    handlePictureCardPreview(file)
    {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    handleChangeSpec(row_id, value)
    {
      this.specifications[row_id] = value
    },
    validate()
    {
      return true;
    },
    getVariables()
    {
      return {
        title: this.form_data.title,
        code: this.form_data.code,
        landlord_fullname: this.form_data.landlord_fullname,
        landlord_phone_number: this.form_data.landlord_phone_number ? ( Number.isInteger( this.form_data.landlord_phone_number ) ? this.form_data.landlord_phone_number : persianJs(this.form_data.landlord_phone_number).persianNumber() * 1 ) : null,
        description: this.form_data.description,
        area: this.form_data.area ? ( Number.isInteger( this.form_data.area ) ? this.form_data.area : persianJs(this.form_data.area).persianNumber() * 1 ) : null,
        
        street_id: this.street_id,
        address: this.form_data.address,
        plaque: this.form_data.plaque ? this.form_data.plaque : null,
        lat: this.form_data.coordinates.lat,
        lng: this.form_data.coordinates.lng,

        label_id: this.form_data.label.id ? this.form_data.label.id : null,
        assignment_id: this.form_data.assignment.id ? this.form_data.assignment.id : null,
        estate_type_id: this.form_data.estate_type.id ? this.form_data.estate_type.id : null,

        rental_price: this.form_data.rental_price ? ( Number.isInteger( this.form_data.rental_price ) ? this.form_data.rental_price : persianJs(this.form_data.rental_price).persianNumber() * 1 ) : null,
        mortgage_price: this.form_data.mortgage_price ? ( Number.isInteger( this.form_data.mortgage_price ) ? this.form_data.mortgage_price : persianJs(this.form_data.mortgage_price).persianNumber() * 1 ) : null,
        sales_price: this.form_data.sales_price ? ( Number.isInteger( this.form_data.sales_price ) ? this.form_data.sales_price : persianJs(this.form_data.sales_price).persianNumber() * 1 ) : null,
        
        tags: this.form_data.tags,
        advantages: this.form_data.advantages,
        disadvantages: this.form_data.disadvantages,
        aparat_video: this.form_data.aparat_video,
        deleted_images: this.deleted_images.filter(i => i.id).map(i => i.id),
        specs: this.getSpecificationsInfo(),
        features: this.form_data.features,
        want_cooperation: this.form_data.want_cooperation,
        photos: null
      };
    },
    getSpecificationsInfo()
    {
      if ( !this.form_data.spec || !this.form_data.spec.headers.length )
        return null

      const rows = _.flatten( this.form_data.spec.headers.map(i => i.rows) )

      let specs = []

      for (let id in this.specifications)
      {
        let row = rows.filter(i => i.id == id)[0]

        if ( row.defaults.length === 0 && row.is_multiple )
          specs[id] = { data: JSON.stringify( this.specifications[id] ) }

        else if ( row.defaults.length === 0 && !row.is_multiple )
          specs[id] = { data: this.specifications[id] }

        else if ( row.defaults.length !== 0 && row.is_multiple )
          specs[id] = { values: this.specifications[id] }

        else
          specs[id] = { value: this.specifications[id] }

        specs[id].id = id
        specs[id].is_required = row.is_required
      }

      return Object.values( specs )
    },
    changeFormData(fd)
    {
      if ( this.form_data.photos.filter(i => i.raw).length === 0 )
        return fd;

      let map = { images: ['variables.photos'] }

      this.form_data.photos.filter(i => i.raw).forEach((image, index) => {
        fd.append(`images[]`, image.raw) 
      })

      fd.delete('map')
      fd.append('map' , JSON.stringify(map))

      return fd;
    },
    IsJsonString(str)
    {
      try {
          JSON.parse(str);
      } catch (e) {
          return false;
      }
      return true;
    },
    handleSpecification(headers)
    {
      this.specifications = {}

      _.flatten( headers.map(i => i.rows) ).map(i => {

        if ( i.data == null)
          this.specifications[i.id] = i.is_multiple ? [] : null
        else
        {
          this.specifications[i.id] = i.data.values.length === 0 ? i.data.data : i.data.values.map(i => i.id)
          
          if ( i.defaults.length === 0 && i.is_multiple )
            this.specifications[i.id] = i.data.data ? this.IsJsonString(i.data.data) ? JSON.parse( i.data.data ) : [i.data.data] : []

          else if ( i.defaults.length !== 0 && !i.is_multiple )
          {
            if ( Array.isArray(this.specifications[i.id]) )
              this.specifications[i.id] = this.specifications[i.id][0] ? this.specifications[i.id][0] : null

            else 
              this.specifications[i.id] = this.specifications[i.id] ? this.specifications[i.id] : null
          }

          else if ( i.defaults.length !== 0 && i.is_multiple )
            this.specifications[i.id] = i.data.values.map(i => i.id)
        }
      })
      // console.log( this.specifications )
    },
    update()
    {
      this.type = 'estate'
      this.label = 'ملک'

      this.storeInServer({
        callback: ({data}) => {
          this.deleted_images = []
          
          const estate = this.$store.state.estate.estate.filter(i => i.id === data.id)
          const my_estate = this.$store.state.estate.my_estate.filter(i => i.id === data.id)

          if ( !this.$route.params.id && !this.can('create-estate') )
            this.$store.state.me.remaining_registered_count = this.$store.state.me.remaining_registered_count - 1

          if ( my_estate.length !== 0 )
          {
            my_estate[0].title = data.title
            my_estate[0].photos = data.photos
            my_estate[0].address = data.address
            my_estate[0].rental_price = data.rental_price
            my_estate[0].mortgage_price = data.mortgage_price
            my_estate[0].sales_price = data.sales_price
            my_estate[0].area = data.area
            my_estate[0].created_at = data.created_at
            my_estate[0].updated_at = data.updated_at
            my_estate[0].is_active = !this.can('create-estate')
            my_estate[0].reject_reason = null
          }
          else
          {
            let arr = this.$store.state.estate.my_estate
            
            if ( arr.length )
            {
              arr.unshift({
                id: data.id,
                title: data.title,
                photos: data.photos,
                address: data.address,
                rental_price: data.rental_price,
                mortgage_price: data.mortgage_price,
                sales_price: data.sales_price,
                area: data.area,
                created_at: data.created_at,
                updated_at: data.updated_at,
                is_active: !this.can('create-estate'),
                reject_reason: null,
              })
            }
          }

          if ( estate.length !== 0 )
          {
            estate[0].title = data.title
            estate[0].photos = data.photos
            estate[0].address = data.address
            estate[0].rental_price = data.rental_price
            estate[0].mortgage_price = data.mortgage_price
            estate[0].sales_price = data.sales_price
            estate[0].area = data.area
            estate[0].created_at = data.created_at
            estate[0].updated_at = data.updated_at
            estate[0].is_active = false
            estate[0].reject_reason = null
          }
          else
          {
            let arr = this.data()
            
            if ( arr.length )
            {
              arr.unshift({
                id: data.id,
                title: data.title,
                photos: data.photos,
                address: data.address,
                rental_price: data.rental_price,
                mortgage_price: data.mortgage_price,
                sales_price: data.sales_price,
                area: data.area,
                created_at: data.created_at,
                updated_at: data.updated_at,
                is_active: false,
                reject_reason: null,
              })
              this.setData( arr )
            }
          }

          this.$router.push(`/panel/estate/mine`)  
        }
      })
    },
  },
  watch: {
    'form_data.aparat_video': function(newVal, oldVal)
    {
      this.is_loaded_video = false
      setTimeout(() => this.is_loaded_video = true, 100);
    },
    'form_data.estate_type.id': function(newVal, oldVal)
    {
      if ( ! this.is_loaded ) return

      axios.post('/graphql/auth', {
        query: `{
          estate_type (id: ${newVal}) {
            spec {
              id title headers {
                id title description
                rows {
                  id title description help
                  prefix postfix is_multiple is_required
                  defaults { id value }
                }
              }
            }

            features {
              id title icon
            }
          }
        }`
      })
      .then(({data}) => {
        this.handleSpecification(data.data.estate_type.spec ? data.data.estate_type.spec.headers : [])
        this.form_data.spec = data.data.estate_type.spec;
        this.features = _.orderBy( data.data.estate_type.features , ['title'], ['asc'])
      })
      .catch( error => console.log(error.response) )
    },
    area_id: function(newVal, oldVal)
    {
      if ( !this.is_loaded ) return

      this.form_data.coordinates = this.area.coordinates
      this.$refs.map.setCenter( this.area.coordinates )

      axios.post('/graphql/auth', {
        query: `{
          streets(area: ${newVal}) { data { id name coordinates { lat lng } } }
        }`
      })
      .then(({data}) => {
        this.street_id = null
        this.streets = data.data.streets.data
      })
    },
    street_id: function(newVal, oldVal)
    {
      if ( !this.is_loaded || !newVal ) return

      this.form_data.coordinates = this.street.coordinates
      this.$refs.map.setCenter( this.street.coordinates )
    }
  },
}
</script>

<style scope>
#tab-specifications .md-helper-text {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  width: 100%;
}
#tab-specifications .md-suffix, #tab-specifications .md-prefix {
  background: linear-gradient(to bottom right, #ff8d72, #f56c6c);
  padding: 0px 10px;
  color: #fff;
  line-height: 20px;
  border-radius: 5px;
  box-shadow: 0px 4px 25px -6px #f70000, 0px 3px 10px -8px #000;
  text-shadow: 1px 2px 7px #0000005c;
}
#tab-specifications .md-prefix {
  margin-left: 5px;
}

.features-checkbox .el-checkbox__label {
  padding-left: 0px;
  padding-right: 10px;
  position: relative;
  top: 4px;
}
.features-checkbox .el-checkbox__input.is-checked+.el-checkbox__label {
  color: #ff8d72 !important;
}

.md-chip {
  max-width: 100%;
}
.md-chip .md-ripple {
  width: 100%;
  max-width: 500px;
  text-overflow: ellipsis;
}

.md-ripple .md-button-content {
  transition: color 300ms;
}
.md-ripple.md-disabled .md-button-content {
  color: #7b7b7b;
}

.md-tab {
  text-align: right;
  direction: rtl;
}
.md-button:not([disabled]).md-focused:before, .md-button:not([disabled]):active:before, .md-button:not([disabled]):hover:before {
  opacity: 0;
}
.md-button {
  outline: none !important;
}
.md-tabs-indicator {
  background: rgb(110, 3, 124) !important;
}
.md-tabs-navigation {
  justify-content: center !important;
}
.md-content.md-tabs-content {
  margin-top: 30px;
  text-align: right;
  background: rgb(255, 255, 255);
  box-shadow: 0px 5px 15px -14px #19375a, 0px 4px 10px -11px #0076ff !important;
  border-radius: 5px;
  transition: height 300ms ease 0s;
}
.md-tabs .md-ripple {
  color: #fff;
}

.md-tabs .md-prefix {
  padding-left: 10px;
}

.md-tabs .md-suffix {
  padding-right: 10px;
}

.md-select-menu .md-checkbox {
  margin: 0px;
}
.md-checkbox .md-checkbox-container:after {
  width: 15px;
  height: 15px;
  border-radius: 50%;
  background: orange;
}

.upload-photo-gallery img {
  max-height: 100%;
}
.upload-photo-gallery .el-upload.el-upload--picture-card {
  width: 148px;
  margin: 0px 10px;
}

.el-checkbox__inner:hover {
  border-color: #f56c6c;
}
.el-checkbox__input.is-checked .el-checkbox__inner,
.el-checkbox__input.is-indeterminate .el-checkbox__inner {
  background-color: #ff8d72;
  border-color: #f56c6c;
}

.el-switch__label.is-active {
  color: #ff8d72;
}
.md-chips .md-chip {
  background: linear-gradient(to bottom right, #ff8d72, #f56c6c);
  box-shadow: 0px 5px 12px -4px #ff8d72, 0px 4px 8px -5px #000 !important;
  text-shadow: 1px 2px 10px #999;
}
.disadvantages .md-chip {
  background: linear-gradient(to bottom right, #ff4c4c, #fd5d93);
  box-shadow: 0px 5px 12px -4px #ff4c4c, 0px 4px 8px -5px #000 !important;
  text-shadow: 1px 2px 10px #999;
}
.advantages .md-chip {
  background: linear-gradient(to bottom right, #00f2c3, #00caa2);
  box-shadow: 0px 5px 12px -4px #00caa2, 0px 4px 8px -5px #000 !important;
  text-shadow: 1px 2px 10px #999;
}
.leaflet-control-attribution.leaflet-control {
  display: none;
}
.vue2leaflet-map {
  box-shadow: 0px 4px 15px -5px;
  border-radius: 10px;
}
</style>
