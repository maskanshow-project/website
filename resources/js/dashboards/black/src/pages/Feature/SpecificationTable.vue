<template>
  <div>
    <div :style="{ position: 'relative', zIndex: 1000 }">
      <div class="row col-12 m-0 p-0">
        <div class="col-12 text-right m-0 p-0">
          <div class="pull-right">
            <h1
              class="animated bounceInRight delay-first"
              :style="{ color: '#fff', fontWeight: 'bold', textShadow: '0px 3px 15px #333' }"
            >
              مدیریت
              <span :style="{ color: '#ff3d3d' }">جدول مشخصات</span> فنی
              <i
                class="header-nav-icon tim-icons icon-align-left-2"
                :style="{fontSize: '25px'}"
              ></i>
            </h1>
            <h6
              class="header-description animated bounceInRight delay-secound"
            >با استفاده از جداول زیر ، امکان مدیریت کامل جدول مشخصات فنی مورد نظر برای شما ممکن خواهد شد</h6>
          </div>
          <div class="pull-left animated bounceInDown delay-last">
            <flip-clock
              :options="{
              label: false,
              clockFace: 'TwentyFourHourClock'
            }"
            />
          </div>
        </div>
      </div>

      <card
        v-if="has_loaded"
        class="mt-4 mb-4 operation-cell text-right animated bounceInRight delay-first"
        dir="rtl"
      >
        <h3 class="card-title">
          {{ table_title }}
          <el-popover
            v-if="table_estate_type"
            placement="top-end"
            width="300"
            trigger="hover"
            :disabled="typeof table_estate_type.title === 'string' ? table_estate_type.title.length <= 50 : false"
            :content="table_estate_type.title"
          >
            <span slot="reference" class="badge badge-default ml-1 hvr-grow-shadow hvr-icon-grow">
              <i class="tim-icons icon-bullet-list-67 hvr-icon"></i>
              {{ table_estate_type.title | truncate(50) }}
            </span>
          </el-popover>
        </h3>
        <h6 class="card-subtitle mb-2 text-muted">{{ table_description }}</h6>

        <base-button @click="createMethod(null, 'header')" type="success" size="sm" class="ml-2">
          <i class="tim-icons icon-simple-add"></i>
          ثبت عنوان جدید
        </base-button>

        <base-button
          @click="$router.push('/panel/specification')"
          size="sm"
          type="warning"
          class="pull-left"
        >
          بازگشت
          <i class="tim-icons icon-double-left"></i>
        </base-button>
      </card>

      <md-dialog :md-active.sync="spec_default_dialog" class="text-right" dir="rtl">
        <md-dialog-title>
          <h2 class="modal-title">مدیریت مقادیر سطر</h2>
          <p>از طریق فرم زیر میتوانید کلیه مقادیر جدول را مدیریت کنید</p>
        </md-dialog-title>

        <div class="md-dialog-content">
          <div class="p-2">
            <base-table
              :style="{ width: '100%' }"
              :tableData="row_defaults"
              :has_animation="false"
              type="spec"
              group="feature"
              label="مقدار"
              :fields="[
                {
                  field: 'value',
                  label: 'مقدار',
                  icon: 'icon-caps-small'
                },
                {
                  field: 'similar_titles',
                  label: 'عناوین مشابه',
                  icon: 'icon-caps-small'
                },
              ]"
              :has_loaded="true"
              :canSelect="false"
              :methods="{ deleteSingle: deleteDefault, edit: (index, item) => editMethod(index, item, 'default') }"
              :has_operation="true"
              :has_times="false"
            >
              <template #value-body="props">
                {{ selected_row.prefix }}
                {{ props.row.value }}
                {{ selected_row.postfix }}
              </template>

              <template #similar_titles-body="props">
                <div>
                  <p v-for="(item, index) in props.row.similar_titles" :key="index">
                    -
                    {{ selected_row.prefix }}
                    {{ item }}
                    {{ selected_row.postfix }}
                  </p>
                </div>
              </template>
            </base-table>
          </div>
        </div>

        <md-dialog-actions>
          <base-button class="ml-2" size="sm" type="danger" @click="spec_default_dialog = false">
            <i class="tim-icons icon-simple-remove"></i>
            بستن
          </base-button>

          <base-button size="sm" type="success" @click="createMethod(selected_row.id, 'default')">
            <i class="tim-icons icon-simple-add"></i>
            افزودن مقدار جدید
          </base-button>
        </md-dialog-actions>
      </md-dialog>

      <md-dialog
        :md-active.sync="$store.state.spec.is_open.spec_header"
        class="text-right"
        dir="rtl"
      >
        <md-dialog-title>
          <h2
            class="modal-title"
          >{{ $store.state.spec.is_creating.spec_header ? 'ثبت عنوان جدول' : 'ویرایش عنوان جدول' }}</h2>
          <p>از طریق فرم زیر میتوانید عنوان جدول {{ $store.state.spec.is_creating.spec_header ? 'جدید ثبت کنید' : 'مورد نظر خود را ویرایش کنید' }}</p>
        </md-dialog-title>

        <div class="md-dialog-content">
          <div class="p-2">
            <form @submit.prevent>
              <md-field>
                <label>عنوان</label>
                <md-input v-model="header_form.title.value" :maxlength="50" />
                <i class="md-icon tim-icons icon-caps-small"></i>
                <span class="md-helper-text">برای مثال : مشخصات پردازنده</span>
                <!-- <span class="md-error" v-show="!$v.title.required">لطفا عنوان را وارد کنید</span> -->
              </md-field>
              <br />
              <md-field>
                <label>توضیحات</label>
                <md-textarea v-model="header_form.description.value" :maxlength="250"></md-textarea>
                <i class="md-icon tim-icons icon-paper"></i>
                <span class="md-helper-text">توضیحی مختصر درباره این عنوان</span>
              </md-field>
            </form>
          </div>
        </div>

        <md-dialog-actions>
          <base-button
            size="sm"
            class="ml-2"
            type="danger"
            @click="$store.state.spec.is_open.spec_header = false"
          >
            <i class="tim-icons icon-simple-remove"></i>
            لغو
          </base-button>

          <base-button
            size="sm"
            :loading="$store.state.spec.is_mutation_loading.spec_header"
            :type="$store.state.spec.is_creating.spec_header ? 'success' : 'warning'"
            @click="$store.state.spec.is_creating.spec_header ? store() : update()"
          >
            <transition name="fade" mode="out-in">
              <semipolar-spinner
                :animation-duration="2000"
                :size="17"
                color="#fff"
                v-if="$store.state.spec.is_mutation_loading.spec_header"
              />
              <span v-else class="pull-right ml-2">
                <i
                  v-if="$store.state.spec.is_creating.spec_header"
                  class="tim-icons icon-simple-add"
                ></i>
                <i v-else class="tim-icons icon-pencil"></i>
              </span>
            </transition>
            {{ $store.state.spec.is_creating.spec_header ? 'ذخیره' : 'بروز رسانی' }} عنوان جدول مشخصات
          </base-button>
        </md-dialog-actions>
      </md-dialog>

      <md-dialog :md-active.sync="$store.state.spec.is_open.spec_row" class="text-right" dir="rtl">
        <md-dialog-title>
          <h2
            class="modal-title"
          >{{ $store.state.spec.is_creating.spec_row ? 'ثبت ردیف جدول' : 'ویرایش ردیف جدول' }}</h2>
          <p>از طریق فرم زیر میتوانید ردیف جدول {{ $store.state.spec.is_creating.spec_row ? 'جدید ثبت کنید' : 'مورد نظر خود را ویرایش کنید' }}</p>
        </md-dialog-title>

        <div class="md-dialog-content">
          <div class="p-2">
            <form @submit.prevent>
              <md-field>
                <label>عنوان</label>
                <md-input v-model="row_form.title.value" :maxlength="50" />
                <i class="md-icon tim-icons icon-caps-small"></i>
                <!-- <span class="md-error" v-show="!$v.title.required">لطفا عنوان ردیف را وارد کنید</span> -->
              </md-field>
              <br />

              <md-chips v-model="row_form.similar_titles.value" md-placeholder="افزودن ...">
                <label>عناوین مشابه</label>
                <span class="md-helper-text">عناوین مشابه با این فیلد جهت بررسی ربات</span>
              </md-chips>
              <br />

              <div class="row">
                <div class="col-6">
                  <md-field>
                    <label>پیشوند</label>
                    <md-input v-model="row_form.prefix.value" :maxlength="20" />
                    <i class="md-icon tim-icons icon-double-right"></i>
                    <span class="md-helper-text">پیشوند مقادیر جدول</span>
                  </md-field>
                </div>
                <div class="col-6">
                  <md-field>
                    <label>پسوند</label>
                    <md-input v-model="row_form.postfix.value" :maxlength="20" />
                    <i class="md-icon tim-icons icon-double-left"></i>
                    <span class="md-helper-text">پسوند مقادیر جدول</span>
                  </md-field>
                </div>
              </div>
              <br />

              <md-field>
                <label>توضیحات</label>
                <md-textarea v-model="row_form.description.value" md-autogrow :maxlength="250"></md-textarea>
                <i class="md-icon tim-icons icon-paper"></i>
                <span class="md-helper-text">توضیحی مختصر درباره این ردیف</span>
              </md-field>
              <br />

              <md-field>
                <label>راهنما</label>
                <md-textarea v-model="row_form.help.value" md-autogrow :maxlength="250"></md-textarea>
                <i class="md-icon tim-icons icon-alert-circle-exc"></i>
                <span class="md-helper-text">راهنمایی کوتاه جهت نمایش به کاربران درباره این ردیف</span>
              </md-field>
              <br />

              <md-field>
                <label>آیکون</label>
                <md-select v-model="row_form.icon.value">
                  <md-optgroup
                    v-for="group in $store.state.icons"
                    :key="group.label"
                    :label="group.label"
                  >
                    <md-option v-for="icon in group.icons" :key="icon" :value="icon">
                      {{ icon }}
                      <i :class="`fas fa-${icon}`"></i>
                    </md-option>
                  </md-optgroup>
                </md-select>
                <i class="md-icon fa-icon" :class="`fas fa-${row_form.icon.value}`"></i>
                <span class="md-helper-text">آیکون سطر جدول را مشخص کنید</span>
              </md-field>

              <br />
              <div class="row" dir="ltr">
                <el-switch
                  class="col-6 d-flex justify-content-center"
                  v-model="row_form.is_filterable.value"
                  active-text="قابلیت فیتلر"
                  active-color="#ff8d72"
                  inactive-text="غیرقابل فیلتر"
                ></el-switch>
                <el-switch
                  class="col-6 d-flex justify-content-center"
                  v-model="row_form.is_detailable.value"
                  active-text="قابل نمایش"
                  active-color="#ff8d72"
                  inactive-text="عدم نمایش"
                ></el-switch>
              </div>
              <br />
              <div class="row" dir="ltr">
                <el-switch
                  class="col-6 d-flex justify-content-center"
                  v-model="row_form.is_required.value"
                  active-text="اجباری"
                  active-color="#ff8d72"
                  inactive-text="اختیاری"
                ></el-switch>
                <el-switch
                  class="col-6 d-flex justify-content-center"
                  v-model="row_form.is_multiple.value"
                  active-text="چند مقداری"
                  active-color="#ff8d72"
                  inactive-text="تک مقداری"
                ></el-switch>
              </div>
              <br />
            </form>
          </div>
        </div>

        <md-dialog-actions>
          <base-button
            size="sm"
            class="ml-2"
            type="danger"
            @click="$store.state.spec.is_open.spec_row = false"
          >
            <i class="tim-icons icon-simple-remove"></i>
            لغو
          </base-button>

          <base-button
            size="sm"
            :loading="$store.state.spec.is_mutation_loading.spec_row"
            :type="$store.state.spec.is_creating.spec_row ? 'success' : 'warning'"
            @click="$store.state.spec.is_creating.spec_row ? store() : update()"
          >
            <transition name="fade" mode="out-in">
              <semipolar-spinner
                :animation-duration="2000"
                :size="17"
                color="#fff"
                v-if="$store.state.spec.is_mutation_loading.spec_row"
              />
              <span v-else class="pull-right ml-2">
                <i v-if="$store.state.spec.is_creating.spec_row" class="tim-icons icon-simple-add"></i>
                <i v-else class="tim-icons icon-pencil"></i>
              </span>
            </transition>
            {{ $store.state.spec.is_creating.spec_row ? 'ذخیره' : 'بروز رسانی' }} ردیف جدول مشخصات
          </base-button>
        </md-dialog-actions>
      </md-dialog>

      <md-dialog
        :md-active.sync="$store.state.spec.is_open.spec_default"
        class="text-right"
        dir="rtl"
      >
        <md-dialog-title>
          <h2
            class="modal-title"
          >{{ $store.state.spec.is_creating.spec_default ? 'ثبت ردیف جدول' : 'ویرایش ردیف جدول' }}</h2>
          <p>از طریق فرم زیر میتوانید ردیف جدول {{ $store.state.spec.is_creating.spec_default ? 'جدید ثبت کنید' : 'مورد نظر خود را ویرایش کنید' }}</p>
        </md-dialog-title>

        <div class="md-dialog-content">
          <div class="p-2">
            <form @submit.prevent>
              <md-field>
                <label>مقدار</label>
                <md-input v-model="default_form.value.value" :maxlength="50" />
                <i class="md-icon tim-icons icon-caps-small"></i>
                <!-- <span class="md-error" v-show="!$v.title.required">لطفا عنوان ردیف را وارد کنید</span> -->
              </md-field>
              <br />

              <md-chips v-model="default_form.similar_titles.value" md-placeholder="افزودن ...">
                <label>عناوین مشابه</label>
                <span class="md-helper-text">عناوین مشابه با این فیلد جهت بررسی ربات</span>
              </md-chips>
              <br />
              <br />
            </form>
          </div>
        </div>

        <md-dialog-actions>
          <base-button
            size="sm"
            class="ml-2"
            type="danger"
            @click="$store.state.spec.is_open.spec_default = false"
          >
            <i class="tim-icons icon-simple-remove"></i>
            لغو
          </base-button>

          <base-button
            size="sm"
            :loading="$store.state.spec.is_mutation_loading.spec_default"
            :type="$store.state.spec.is_creating.spec_default ? 'success' : 'warning'"
            @click="$store.state.spec.is_creating.spec_default ? store() : update()"
          >
            <transition name="fade" mode="out-in">
              <semipolar-spinner
                :animation-duration="2000"
                :size="17"
                color="#fff"
                v-if="$store.state.spec.is_mutation_loading.spec_default"
              />
              <span v-else class="pull-right ml-2">
                <i
                  v-if="$store.state.spec.is_creating.spec_default"
                  class="tim-icons icon-simple-add"
                ></i>
                <i v-else class="tim-icons icon-pencil"></i>
              </span>
            </transition>
            {{ $store.state.spec.is_creating.spec_default ? 'ذخیره' : 'بروز رسانی' }} ردیف جدول مشخصات
          </base-button>
        </md-dialog-actions>
      </md-dialog>

      <div v-for="(header, index) of headers" :key="header.id" class="row mb-3 w-100 m-0">
        <div class="w-100">
          <div class="text-right pull-right">
            <h2 class="animated bounceInRight delay-first mb-0">
              {{ header.title }}
              <i
                class="tim-icons icon-bullet-list-67"
                :style="{fontSize: '20px'}"
              ></i>
            </h2>
            <h6 class="text-muted animated bounceInRight delay-secound">{{ header.description }}</h6>
          </div>

          <div class="animated bounceInLeft delay-secound operation-cell" dir="rtl">
            <el-tooltip content="ثبت ردیف برای این عنوان">
              <base-button @click="createMethod(header.id, 'row')" type="success" size="sm" icon>
                <i class="tim-icons icon-simple-add"></i>
              </base-button>
            </el-tooltip>

            <el-tooltip content="ویرایش عنوان">
              <base-button
                @click="editMethod(index, header, 'header')"
                type="warning"
                size="sm"
                icon
              >
                <i class="tim-icons icon-pencil"></i>
              </base-button>
            </el-tooltip>

            <el-tooltip content="حذف عنوان">
              <base-button
                @click="deleteSingle(index, header, 'header')"
                type="danger"
                size="sm"
                icon
              >
                <i class="tim-icons icon-trash-simple"></i>
              </base-button>
            </el-tooltip>
          </div>
        </div>

        <base-table
          class="spec-rows-table"
          :style="{ width: '100%' }"
          :tableData="header.rows"
          :has_animation="false"
          type="spec"
          group="feature"
          label="سطر جدول"
          :canSelect="false"
          :fields="[
            {
              field: 'title',
              label: 'عنوان جدول',
              icon: 'icon-caps-small'
            }, {
              field: 'defaults',
              label: 'مقادیر پیشفرض',
              icon: 'icon-single-copy-04'
            }, {
              field: 'texts',
              label: 'پیشوند/پسوند',
              icon: 'icon-single-copy-04'
            }, {
              field: 'features',
              label: 'ویژگی ها',
              icon: 'icon-single-copy-04'
            }
          ]"
          :methods="{ edit: editMethod, deleteSingle }"
          :has_loaded="has_loaded"
          :has_times="true"
          :has_operation="true"
        >
          <template #title-body="slotProps">
            <el-popover
              placement="top-end"
              width="300"
              trigger="hover"
              :title="slotProps.row.title"
              :disabled="typeof slotProps.row.title === 'string' ? slotProps.row.title.length <= 50 : false"
              :content="slotProps.row.help"
            >
              <p slot="reference" class="text-center" :style="{ overflow: 'visible' }">
                <i v-if="slotProps.row.icon" :class="`ml-2 fas fa-${slotProps.row.icon}`"></i>
                {{ slotProps.row.title | truncate(50) }}
              </p>
            </el-popover>
          </template>

          <template #defaults-body="slotProps">
            <transition-group name="list">
              <el-popover
                v-for="item in slotProps.row.defaults.filter( (i, index) => index < 3)"
                :key="item.id"
                placement="top-end"
                width="300"
                trigger="hover"
                :disabled="typeof item.value === 'string' ? item.value.length <= 20 : false"
                :content="item.value"
              >
                <span
                  slot="reference"
                  class="badge badge-default ml-1 hvr-grow-shadow hvr-icon-grow"
                >
                  <i class="tim-icons icon-bullet-list-67 hvr-icon"></i>
                  {{ slotProps.row.prefix }}
                  {{ item.value | truncate(20) }}
                  {{ slotProps.row.postfix }}
                </span>
              </el-popover>

              <el-dropdown
                v-if="slotProps.row.defaults.length > 3"
                :key="slotProps.row.defaults.map((c) => c.id).join(',')"
              >
                <span class="el-dropdown-link badge badge-default">
                  باقی مقادیر ها
                  <i class="el-icon-arrow-down el-icon--right"></i>
                </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item
                    v-for="item in slotProps.row.defaults.filter( (i, index) => index < 3)"
                    :key="item.id"
                  >{{ item.value }}</el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </transition-group>
          </template>

          <template #features-body="slotProps">
            <div>
              <table class="spec-feature-table">
                <tbody>
                  <tr>
                    <td>
                      <i class="tim-icons icon-satisfied"></i>
                    </td>
                    <td
                      class="text-right"
                      :class="slotProps.row.is_detailable ? 'text-success' : 'text-danger'"
                    >{{ slotProps.row.is_detailable ? 'نمایش' : 'عدم نمایش' }}</td>
                  </tr>

                  <tr>
                    <td>
                      <i class="tim-icons icon-bullet-list-67"></i>
                    </td>
                    <td
                      class="text-right"
                      :class="slotProps.row.is_multiple ? 'text-success' : 'text-danger'"
                    >{{ slotProps.row.is_multiple ? 'چند مقداری' : 'تک مقداری' }}</td>
                  </tr>

                  <tr>
                    <td>
                      <i class="tim-icons icon-pin"></i>
                    </td>
                    <td
                      class="text-right"
                      :class="slotProps.row.is_filterable ? 'text-success' : 'text-danger'"
                    >{{ slotProps.row.is_filterable ? 'قابلیت فیلتر' : 'غیرقابل فیلتر' }}</td>
                  </tr>

                  <tr>
                    <td>
                      <i class="tim-icons icon-lock-circle"></i>
                    </td>
                    <td
                      class="text-right"
                      :class="slotProps.row.is_required ? 'text-success' : 'text-danger'"
                    >{{ slotProps.row.is_required ? 'اجباری' : 'اختیاری' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </template>

          <template #texts-body="slotProps">
            <table class="spec-feature-table">
              <tbody>
                <tr v-if="slotProps.row.postfix">
                  <td>
                    <i class="tim-icons icon-double-left"></i>
                  </td>
                  <td class="text-right">{{ slotProps.row.postfix }}</td>
                </tr>

                <tr v-if="slotProps.row.prefix">
                  <td>
                    <i class="tim-icons icon-double-right"></i>
                  </td>
                  <td class="text-right">{{ slotProps.row.prefix }}</td>
                </tr>
              </tbody>
            </table>
          </template>

          <template #custom-operations="slotProps">
            <el-tooltip content="مدیریت مقادیر">
              <base-button
                class="m-0"
                @click="manageValues(slotProps.index, slotProps.row)"
                type="success"
                size="sm"
                icon
              >
                <i class="tim-icons icon-bullet-list-67"></i>
              </base-button>
            </el-tooltip>
          </template>
        </base-table>
      </div>

      <transition name="fade">
        <div class="main-panel-loading" v-if="!has_loaded">
          <fingerprint-spinner :animation-duration="1000" :size="100" color="#fff" />
        </div>
      </transition>

      <transition name="loading">
        <div class="query-loader" v-if="$store.state.spec.is_mutation_loading.spec_default">
          <half-circle-spinner :animation-duration="800" :size="40" color="#fff" />
        </div>
      </transition>

      <transition name="fade">
        <md-empty-state
          v-show="has_loaded && headers.length === 0"
          md-icon="search"
          md-label="متاسفانه هیچ داده ای یافت نشد :("
          md-description="اگر در حالت جستجو نیستید و هیچ فیلتری نیز اعمال نکرده اید ، میتوانید با کلیک بر روی دکمه زیر یک داده جدید ثبت کنید"
        >
          <base-button @click="createMethod(null, 'header')" type="success" size="sm" class="ml-2">
            <i class="tim-icons icon-simple-add"></i>
            ثبت عنوان جدید
          </base-button>
        </md-empty-state>
      </transition>
    </div>
  </div>
</template>

<script>
import BaseTable from "../../components/BaseTable.vue";
import { FlipClock } from "@mvpleung/flipclock";
import {
  SemipolarSpinner,
  HalfCircleSpinner,
  FingerprintSpinner
} from "epic-spinners";

import deleteMixin from "../../mixins/deleteMixin";
import createMixin from "../../mixins/createMixin";

export default {
  mixins: [createMixin, deleteMixin],
  components: {
    BaseTable,
    FlipClock,
    SemipolarSpinner,
    HalfCircleSpinner,
    FingerprintSpinner
  },
  metaInfo() {
    return {
      title: `جدول ${this.table_title}`
    };
  },
  data() {
    return {
      headers: [],
      row_defaults: [],
      spec_default_dialog: false,
      spec_default_is_creating: false,
      spec_default_loading: false,
      table_title: "",
      table_description: "",
      table_estate_type: {
        title: ""
      },

      type: null,
      group: "spec",
      has_loaded: false,
      is_modal_open: false,
      is_open_value_prompt: false,
      selected_type: false,
      selected_row: null
    };
  },
  methods: {
    createMethod(id = null, type = "row") {
      this.label =
        type === "row"
          ? "سطر جدول"
          : type === "default"
          ? "مقدار پیشفرض"
          : "عنوان جدول";
      this.type =
        type === "row"
          ? "spec_row"
          : type === "default"
          ? "spec_default"
          : "spec_header";
      this.selected_type = type;
      this.selected_header_id = id;

      this.create();
    },
    afterCreate() {
      this.$store.commit("setFormData", {
        group: this.group,
        type: this.type,
        field:
          this.selected_type === "row"
            ? "spec_header_id"
            : this.selected_type === "default"
            ? "spec_row_id"
            : "spec_id",
        value:
          this.selected_type === "row" || this.selected_type === "default"
            ? this.selected_header_id
            : this.$route.params.id
      });
    },
    editMethod(index, row, type = "row") {
      this.label =
        type === "row"
          ? "سطر جدول"
          : type === "default"
          ? "مقدار پیشفرض"
          : "عنوان جدول";
      this.type =
        type === "row"
          ? "spec_row"
          : type === "default"
          ? "spec_default"
          : "spec_header";
      this.selected_type = type;

      this.edit(index, row);
    },
    afterEdit(row) {
      this.$store.commit("setFormData", {
        group: this.group,
        type: this.type,
        field:
          this.selected_type === "row"
            ? "spec_header_id"
            : this.selected_type == "default"
            ? "spec_row_id"
            : "spec_id",
        value:
          this.selected_type === "row"
            ? this.headers.filter(
                header => header.rows.filter(i => i.id === row.id).length !== 0
              )[0].id
            : this.selected_type == "default"
            ? this.selected_row.id
            : this.$route.params.id
      });
    },
    store() {
      this.storeInServer({
        callback: ({ data }) => {
          if (this.selected_type === "row") {
            this.headers
              .filter(i => i.id === this.selected_header_id)[0]
              .rows.unshift(data);
          } else if (this.selected_type == "default") {
            this.row_defaults.unshift(data);
          } else {
            data.rows = [];
            this.headers.unshift(data);
          }

          this.setAttr("is_open", false);
          this.setAttr("is_creating", false);
        }
      });
    },
    update() {
      this.storeInServer({
        callback: ({ data }) => {
          let index = this.attr("selected").index;

          if (this.selected_type === "row") {
            let item = this.headers.filter(
              header => header.rows.filter(i => i.id === data.id).length !== 0
            );
            item[0].rows[index] = data;
          } else if (this.selected_type == "default") {
            this.row_defaults[this.attr("selected").index].value = data.value;
          } else {
            data.rows = this.headers[index].rows;
            this.headers[index] = data;
          }

          this.setAttr("is_open", false);
        }
      });
    },
    deleteSingle(index, row, type = "row") {
      this.label = type === "row" ? "سطر جدول" : "عنوان جدول";
      this.type = type === "row" ? "spec_row" : "spec_header";
      this.selected_type = type;

      this.handleDelete(index, row);
    },
    deleteDefault(index, row) {
      this.label = "مقدار سطر";
      this.type = "spec_default";
      this.selected_type = "default";

      this.handleDelete(index, row);
    },
    afterDelete(index, row) {
      if (this.selected_type === "row") {
        let item = this.headers.filter(
          header => header.rows.filter(i => i.id === row.id).length !== 0
        );
        item[0].rows.splice(index, 1);
      } else if (this.selected_type === "default") {
        this.row_defaults.splice(index, 1);
      } else this.headers.splice(index, 1);
    },
    manageValues(index, row) {
      this.row_defaults = row.defaults;
      this.selected_row = row;
      this.type = "spec_default";

      this.$store.commit("setFormData", {
        group: this.group,
        type: this.type,
        field: "spec_row_id",
        value: row.id
      });

      this.spec_default_dialog = true;
    },
    validate() {
      return true;
    }
  },
  computed: {
    row_form() {
      return this.$store.state.spec.form.spec_row;
    },
    default_form() {
      return this.$store.state.spec.form.spec_default;
    },
    header_form() {
      return this.$store.state.spec.form.spec_header;
    },
    allQuery() {
      if (this.selected_type === "row") {
        return `
          title
          similar_titles
          description
          help
          postfix
          prefix
          icon
          is_detailable
          is_multiple
          is_filterable
          is_required
          created_at
          updated_at

          defaults {
            id
            value
          }
        `;
      } else if (this.selected_type === "header") {
        return `
          title
          description
          rows {
            id
          }
        `;
      }

      return `value similar_titles`;
    }
  },
  mounted() {
    axios
      .post("/graphql/auth", {
        query: `{
        spec(id: ${this.$route.params.id}) {
          id
          title
          description
          estate_type {
            id
            title
          }
          headers {
            id
            title
            description

            rows {
              id
              title
              similar_titles
              description
              help
              postfix
              prefix
              icon
              is_detailable
              is_multiple
              is_filterable
              is_required
              created_at
              updated_at

              defaults {
                id
                value
                similar_titles
              }
            }
          }
        }
      }`
      })
      .then(({ data }) => {
        // return console.log( data.data.spec )
        this.table_title = data.data.spec.title;
        this.table_description = data.data.spec.description;
        // this.table_estate_type = data.data.spec.estate_type
        this.headers = data.data.spec.headers;
      })
      .then(() => (this.has_loaded = true));
  }
};
</script>

<style>
.spec-feature-table td {
  padding: 2px;
}
.spec-rows-table .data-table-row ul {
  min-height: 120px;
}
.md-list-item-text {
  display: block;
  text-align: right;
}
.md-list-item-text i.fas {
  float: left;
  width: auto;
}
</style>
