<template>
  <div>
    <!-- Banner -->
    <section class="banner_area">
      <div class="banner_inner d-flex align-items-center">
        <div
          class="overlay bg-parallax"
          data-stellar-ratio="0.9"
          data-stellar-vertical-offset="0"
          data-background
        ></div>
        <!-- Breadcrumbs -->
        <div class="container mb-5 pb-5 rtl" v-if="!(Res && username_clt && is_exist(office))">
          <v-breadcrumbs :items="Breadcrumb">
            <template v-slot:divider>
              <v-icon>chevron_left</v-icon>
            </template>
          </v-breadcrumbs>
        </div>
      </div>
    </section>

    <!-- Office Estate Information ( Responsive Mode ) -->
    <div class="profile-estate responsive" v-if=" Res && username_clt && is_exist(office) ">
      <div class="container">
        <div class="row rtl">
          <div class="col-md-2 text-center">
            <v-avatar color="teal" :size="130">
              <img
                :src=" is_exist(office.owner) && office.owner.avatar ? url + office.owner.avatar.medium : '/img/user.png' "
                :alt="office.name"
              />
            </v-avatar>
          </div>

          <div class="col-md-10 text-center d-flex flex-column justify-content-around mt-xs-4">
            <div class="username">{{ 'املاک ' + office.name }}</div>

            <p class="mt-3 mb-0 text-center">
              <i class="fa fa-user ml-2 bold web-color"></i>
              {{ office.owner && office.owner.full_name ? office.owner.full_name : '' }}
            </p>

            <p class="mt-1 mb-0 text-center">
              <i class="fa fa-map-marker-alt ml-2 bold web-color"></i>
              {{ ( office.area && office.area.name ? office.area.name +' ، ' : '' ) + office.address }}
            </p>

            <p class="mt-1 mb-0 text-center">
              <i class="fa fa-phone ml-2 bold web-color"></i>
              <a class="text-reset" :href="`tel:${office.phone_number}`">{{ office.phone_number }}</a>
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-5 px-5" :class="{ 'pt-xs-85 py-4' : Res && is_exist($route.params) }">
      <template v-if=" Res && username_clt && is_exist(office) && office.description ">
        <div class="text-center">
          <qrcode
            class="rounded flex-unset"
            :value="`http://maskanshow.ir/${office.username}`"
            :options="{ width: 100 }"
          ></qrcode>
        </div>

        <p class="text-right">
          <i class="fa fa-book ml-2 fs-20 bold web-color"></i>
          {{ office.description }}
        </p>
      </template>
    </div>

    <!-- Filter Bar ( PC Mode ) -->
    <div class="container filter-bar as-shadow" v-show="!Res">
      <div class="row rtl p-5" id="filter-bar" ref="filterBar">
        <div class="as-main-btn hvr-ripple-out" @click="Apply_filters(false)">
          <i class="material-icons">search</i>
        </div>

        <div class="as-main-btn remove-filters hvr-ripple-out" @click="Delete_filters">
          <i class="material-icons">delete</i>
        </div>

        <!-- City Areas -->
        <div class="col-md-2">
          <el-cascader
            v-model="areas_select"
            :options="city_areas"
            :props="{
                            expandTrigger: 'hover' ,
                            label : 'name' ,
                            value : 'id' ,
                            children : 'streets' ,
                            multiple : true ,
                            checkStrictly : true
                        }"
            :placeholder="set_disabled"
            collapse-tags
          ></el-cascader>
        </div>

        <!-- Assignments -->
        <div class="col-md-2">
          <el-select v-model="assignments_select" placeholder="نوع واگذاری" clearable>
            <el-option
              v-for="item in assignments"
              :key="item.id"
              :label="item.title"
              :value="item.id"
            ></el-option>
          </el-select>
        </div>

        <!-- Estate Types -->
        <div class="col-md-2">
          <el-select
            v-model="estate_types_select"
            @change="set_dynamic_filters"
            placeholder="نوع ملک"
            clearable
            no-data-text="نوع واگذاری انتخاب نشده"
          >
            <el-option
              v-for="item in custom_estate_types"
              :key="item.id"
              :label="item.title"
              :value="item.id"
            ></el-option>
          </el-select>
        </div>

        <!-- Area -->
        <div class="col-md-2">
          <el-popover v-model="area.popover" placement="bottom" width="295" trigger="click">
            <el-button slot="reference" class="btn-range w-100 text-right" plain>
              {{ area.label }}
              <i class="el-icon-arrow-down"></i>
            </el-button>

            <div class="input-price rtl">
              <el-input
                class="text-center fs-12"
                placeholder="حداقل"
                v-model="area.min.value"
                @focus="focus_min('area')"
              ></el-input>
              <span>-</span>
              <el-input
                class="text-center fs-12"
                placeholder="حداکثر"
                v-model="area.max.value"
                @focus="focus_max('area')"
              ></el-input>
            </div>

            <div class="default-prices input_min" v-if="area.min.active_defaults">
              <v-list class="animated fadeInRight">
                <v-list-tile
                  v-for="(item,index) in area.min.defaults"
                  :key="index"
                  @click="set_ranges('area' , 'min' , index , item.value)"
                  :disabled="item.disabled"
                >
                  <v-list-tile-content>
                    <v-list-tile-title v-text="(item.value).toLocaleString('fa-IR')"></v-list-tile-title>
                  </v-list-tile-content>
                </v-list-tile>
              </v-list>
            </div>

            <div class="default-prices" v-if="area.max.active_defaults">
              <v-list class="animated fadeInLeft faster">
                <v-list-tile
                  v-for="(item,index) in area.max.defaults"
                  :key="index"
                  @click="set_ranges('area' , 'max' , index , item.value)"
                  :disabled="item.disabled"
                >
                  <v-list-tile-content>
                    <v-list-tile-title v-text="(item.value).toLocaleString('fa-IR')"></v-list-tile-title>
                  </v-list-tile-content>
                </v-list-tile>
              </v-list>
            </div>

            <div class="input-price d-flex justify-content-between pt-2">
              <v-btn class="mb-0" flat :color="web_color" @click="filter_area">اعمال</v-btn>
              <v-btn class="mb-0 text-danger" flat color="#999" @click="Cansel('area')">حذف فیلتر</v-btn>
            </div>
          </el-popover>
        </div>

        <!-- Dynamic Filters -->
        <div
          class="col-md-2"
          v-for="filter in ( dynamic_filters.spec ? dynamic_filters.spec.filters : [] )"
          :key="filter.id"
        >
          <template v-if="is_exist(filter.defaults)">
            <el-select
              v-model="bind_filters[filter.id]"
              :placeholder="filter.title"
              multiple
              collapse-tags
            >
              <el-option
                class="rtl"
                v-for="item in filter.defaults"
                :key="item.id"
                :label="filter.prefix +' '+ item.value +' '+ filter.postfix"
                :value="item.id"
              ></el-option>
            </el-select>
          </template>

          <template v-else>
            <vs-input :label-placeholder="filter.title" v-model="bind_filters[filter.id]" />
          </template>
        </div>

        <!-- Estate Options -->
        <div class="col-md-2">
          <el-select
            v-model="options_select"
            multiple
            collapse-tags
            :no-data-text=" estate_types_select ? 'موردی وجود ندارد' : 'نوع ملک انتخاب نشده' "
            placeholder="امکانات"
          >
            <el-option
              v-for="item in dynamic_filters.features"
              :key="item.id"
              :label="item.title"
              :value="item.id"
            ></el-option>
          </el-select>
        </div>

        <!-- Code State -->
        <div class="col-md-2">
          <vs-input icon="search" label-placeholder="کد ملک" v-model="code_estate" />
        </div>

        <!-- Search -->
        <div class="col-md-2 mt-3">
          <vs-input icon="search" label-placeholder="آدرس واژه" v-model="search" />
        </div>

        <!-- Owner -->
        <div class="col-md-2 mt-3">
          <vs-input icon="person" label-placeholder="نام مالک" v-model="owner" />
        </div>

        <!-- Phone Owner -->
        <div class="col-md-2 mt-3">
          <vs-input
            icon="local_phone"
            label-placeholder="تلفن مالک"
            v-model="phone_owner"
            type="number"
          />
        </div>

        <!-- Date -->
        <div class="col-md-2 mt-3">
          <vs-input
            class="date-picker"
            icon="date_range"
            readonly
            label-placeholder="تاریخ ثبت"
            v-model="date"
          />
        </div>

        <transition name="fade" mode="out-in">
          <!-- Sales Price Input -->
          <div class="col-md-2 mt-3" v-if="active_ranges.has_sales_price">
            <el-popover
              v-model="sales_price.popover"
              placement="bottom"
              width="295"
              trigger="click"
            >
              <el-button slot="reference" class="btn-range pr-2 w-100 text-right" plain>
                <i class="el-icon-arrow-down"></i>
                {{ sales_price.label }}
              </el-button>

              <div class="input-price rtl">
                <el-input
                  class="text-center fs-12"
                  placeholder="حداقل"
                  v-model="sales_price.min.value"
                  @focus="focus_min('sales_price')"
                ></el-input>
                <span>-</span>
                <el-input
                  class="text-center fs-12"
                  placeholder="حداکثر"
                  v-model="sales_price.max.value"
                  @focus="focus_max('sales_price')"
                ></el-input>
              </div>

              <div class="default-prices input_min" v-if="sales_price.min.active_defaults">
                <v-list class="animated fadeInRight">
                  <v-list-tile
                    v-for="(item,index) in sales_price.min.defaults"
                    :key="index"
                    @click="set_ranges('sales_price' , 'min' , index , item.value)"
                    :disabled="item.disabled"
                  >
                    <v-list-tile-content>
                      <v-list-tile-title v-text="(item.value).toLocaleString('fa-IR')"></v-list-tile-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-list>
              </div>

              <div class="default-prices" v-if="sales_price.max.active_defaults">
                <v-list class="animated fadeInLeft faster">
                  <v-list-tile
                    v-for="(item,index) in sales_price.max.defaults"
                    :key="index"
                    @click="set_ranges('sales_price' , 'max' , index , item.value)"
                    :disabled="item.disabled"
                  >
                    <v-list-tile-content>
                      <v-list-tile-title v-text="(item.value).toLocaleString('fa-IR')"></v-list-tile-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-list>
              </div>

              <div class="input-price d-flex justify-content-between pt-2">
                <v-btn
                  class="mb-0"
                  flat
                  :color="web_color"
                  @click="filter_range('sales_price',sales_price.default_label)"
                >اعمال</v-btn>
                <v-btn
                  class="mb-0 text-danger"
                  flat
                  color="#999"
                  @click="Cansel('sales_price')"
                >حذف فیلتر</v-btn>
              </div>
            </el-popover>
          </div>
        </transition>

        <transition name="fade" mode="out-in">
          <!-- Mortgage Price Input -->
          <div class="col-md-2 mt-3" v-if="active_ranges.has_mortgage_price">
            <el-popover
              v-model="mortgage_price.popover"
              placement="bottom"
              width="295"
              trigger="click"
            >
              <el-button slot="reference" class="btn-range pr-2 w-100 text-right" plain>
                <i class="el-icon-arrow-down"></i>
                {{ mortgage_price.label }}
              </el-button>

              <div class="input-price rtl">
                <el-input
                  class="text-center fs-12"
                  placeholder="حداقل"
                  v-model="mortgage_price.min.value"
                  @focus="focus_min('mortgage_price')"
                ></el-input>
                <span>-</span>
                <el-input
                  class="text-center fs-12"
                  placeholder="حداکثر"
                  v-model="mortgage_price.max.value"
                  @focus="focus_max('mortgage_price')"
                ></el-input>
              </div>

              <div class="default-prices input_min" v-if="mortgage_price.min.active_defaults">
                <v-list class="animated fadeInRight">
                  <v-list-tile
                    v-for="(item,index) in mortgage_price.min.defaults"
                    :key="index"
                    @click="set_ranges('mortgage_price' , 'min' , index , item.value)"
                    :disabled="item.disabled"
                  >
                    <v-list-tile-content>
                      <v-list-tile-title v-text="(item.value).toLocaleString('fa-IR')"></v-list-tile-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-list>
              </div>

              <div class="default-prices" v-if="mortgage_price.max.active_defaults">
                <v-list class="animated fadeInLeft faster">
                  <v-list-tile
                    v-for="(item,index) in mortgage_price.max.defaults"
                    :key="index"
                    @click="set_ranges('mortgage_price' , 'max' , index , item.value)"
                    :disabled="item.disabled"
                  >
                    <v-list-tile-content>
                      <v-list-tile-title v-text="(item.value).toLocaleString('fa-IR')"></v-list-tile-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-list>
              </div>

              <div class="input-price d-flex justify-content-between pt-2">
                <v-btn
                  class="mb-0"
                  flat
                  :color="web_color"
                  @click="filter_range('mortgage_price',mortgage_price.default_label)"
                >اعمال</v-btn>
                <v-btn
                  class="mb-0 text-danger"
                  flat
                  color="#999"
                  @click="Cansel('mortgage_price')"
                >حذف فیلتر</v-btn>
              </div>
            </el-popover>
          </div>
        </transition>

        <transition name="fade" mode="out-in">
          <!-- Rental Price Input -->
          <div class="col-md-2 mt-3" v-if="active_ranges.has_rental_price">
            <el-popover
              v-model="rental_price.popover"
              placement="bottom"
              width="295"
              trigger="click"
            >
              <el-button slot="reference" class="btn-range pr-2 w-100 text-right" plain>
                <i class="el-icon-arrow-down"></i>
                {{ rental_price.label }}
              </el-button>

              <div class="input-price rtl">
                <el-input
                  class="text-center fs-12"
                  placeholder="حداقل"
                  v-model="rental_price.min.value"
                  @focus="focus_min('rental_price')"
                ></el-input>
                <span>-</span>
                <el-input
                  class="text-center fs-12"
                  placeholder="حداکثر"
                  v-model="rental_price.max.value"
                  @focus="focus_max('rental_price')"
                ></el-input>
              </div>

              <div class="default-prices input_min" v-if="rental_price.min.active_defaults">
                <v-list class="animated fadeInRight">
                  <v-list-tile
                    v-for="(item,index) in rental_price.min.defaults"
                    :key="index"
                    @click="set_ranges('rental_price' , 'min' , index , item.value)"
                    :disabled="item.disabled"
                  >
                    <v-list-tile-content>
                      <v-list-tile-title v-text="(item.value).toLocaleString('fa-IR')"></v-list-tile-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-list>
              </div>

              <div class="default-prices" v-if="rental_price.max.active_defaults">
                <v-list class="animated fadeInLeft faster">
                  <v-list-tile
                    v-for="(item,index) in rental_price.max.defaults"
                    :key="index"
                    @click="set_ranges('rental_price' , 'max' , index , item.value)"
                    :disabled="item.disabled"
                  >
                    <v-list-tile-content>
                      <v-list-tile-title v-text="(item.value).toLocaleString('fa-IR')"></v-list-tile-title>
                    </v-list-tile-content>
                  </v-list-tile>
                </v-list>
              </div>

              <div class="input-price d-flex justify-content-between pt-2">
                <v-btn
                  class="mb-0"
                  flat
                  :color="web_color"
                  @click="filter_range('rental_price',rental_price.default_label)"
                >اعمال</v-btn>
                <v-btn
                  class="mb-0 text-danger"
                  flat
                  color="#999"
                  @click="Cansel('rental_price')"
                >حذف فیلتر</v-btn>
              </div>
            </el-popover>
          </div>
        </transition>
      </div>
    </div>

    <!-- Office Estate Information ( PC Mode ) -->
    <div
      class="container mb-5 mt-n5 profile-estate as-shadow"
      v-if=" !Res && username_clt && is_exist(office) "
    >
      <div class="row rtl">
        <div class="col-md-2 text-center">
          <v-avatar color="teal" :size="130">
            <img
              :src=" is_exist(office.owner) && office.owner.avatar ? url + office.owner.avatar.medium : '/img/user.png' "
              :alt="office.name"
            />
          </v-avatar>
        </div>

        <div class="col-md-4 text-right d-flex justify-content-around align-items-center">
          <div>
            <div class="username mb-3">{{ 'املاک ' + office.name }}</div>

            <p class="mb-2 text-right">
              <i class="fa fa-user ml-2 bold web-color"></i>
              {{ office.owner && office.owner.full_name ? office.owner.full_name : '' }}
            </p>

            <p class="mb-2 text-right">
              <i class="fa fa-map-marker-alt ml-2 bold web-color"></i>
              {{ ( office.area && office.area.name ? office.area.name +' ، ' : '' ) + office.address }}
            </p>

            <p class="mb-0 text-right" v-if="office.phone_number">
              <i class="fa fa-phone ml-2 bold web-color"></i>
              <a class="text-reset" :href="`tel:${office.phone_number}`">{{ office.phone_number }}</a>
            </p>
          </div>
        </div>

        <div class="col-md-4 text-right d-flex justify-content-around">
          <div>
            <p class="mb-0 text-right" v-if="office.description">
              <i class="fa fa-book ml-2 fs-20 bold web-color"></i>
              {{ office.description }}
            </p>
          </div>
        </div>

        <div class="col-md-2 d-flex justify-content-center align-items-center">
          <qrcode
            class="rounded flex-unset"
            download
            :value="`http://maskanshow.ir/${office.username}`"
            :options="{ width: 100 }"
          ></qrcode>
        </div>
      </div>
    </div>

    <date-picker v-model="date" element="date-picker"></date-picker>

    <!-- Estates Component With Slots -->
    <Estates
      :class="{ 'mb-5' : !(total > 1) , 'mt-n4' : !(username_clt && is_exist(office)) }"
      :title="title"
    >
      <template #sort>
        <div class="d-flex align-items-center sort-btn ml-3" v-if="!Res">
          <div class="rtl">
            <el-select v-model="ordering" placeholder="مرتب سازی">
              <el-option
                v-for="item in sort_items"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              ></el-option>
            </el-select>
          </div>
        </div>
        <div class="d-flex align-items-center sort-btn ml-4" v-if="!Res">
          <div class="rtl checkbox assign">
            <v-checkbox
              class="mt-2"
              v-model="is_assignemnt"
              :color="web_color"
              label="ملک های واگذار نشده"
            ></v-checkbox>
          </div>
        </div>
      </template>
    </Estates>

    <!-- Pagination -->
    <div class="properties_area mt-4 pb-0" v-show="total > 1">
      <vs-pagination :color="web_color" :total="total" v-model="page"></vs-pagination>
    </div>

    <!-- Filter Bar ( Responsive Mode ) -->
    <v-app>
      <v-dialog
        v-model="dialog_filters"
        fullscreen
        scrollable
        hide-overlay
        transition="dialog-bottom-transition"
      >
        <v-card class="dialog_filters">
          <v-toolbar dark fixed :color="web_color_dark">
            <v-btn icon dark @click="Set_state({ prop : 'dialog_filters' , val : false })">
              <v-icon>close</v-icon>
            </v-btn>

            <v-spacer></v-spacer>

            <v-toolbar-title>فیلتر ها</v-toolbar-title>
          </v-toolbar>

          <v-card-text class="mt-5">
            <div class="rtl p-4">
              <div class="rtl checkbox">
                <v-checkbox
                  class="mt-2"
                  v-model="is_assignemnt"
                  :color="web_color"
                  label="نمایش ملک های واگذار نشده"
                ></v-checkbox>
              </div>

              <!-- City Areas -->
              <div class="mb-4 mt-2">
                <el-cascader
                  class="w-100"
                  v-model="areas_select"
                  :options="city_areas"
                  :props="{
                                        expandTrigger: 'hover' ,
                                        label : 'name' ,
                                        value : 'id' ,
                                        children : 'streets' ,
                                        multiple : true ,
                                        checkStrictly : true
                                    }"
                  :placeholder="set_disabled"
                  collapse-tags
                ></el-cascader>
              </div>

              <!-- Assignments -->
              <div class="mb-4">
                <el-select v-model="assignments_select" placeholder="نوع واگذاری">
                  <el-option
                    v-for="item in assignments"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id"
                  ></el-option>
                </el-select>
              </div>

              <!-- Estate Types -->
              <div class="mb-4">
                <el-select
                  v-model="estate_types_select"
                  @change="set_dynamic_filters"
                  placeholder="نوع ملک"
                  no-data-text="نوع واگذاری انتخاب نشده"
                >
                  <el-option
                    v-for="item in custom_estate_types"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id"
                  ></el-option>
                </el-select>
              </div>

              <!-- Search -->
              <div class="mb-4">
                <el-input placeholder="آدرس واژه" v-model="search"></el-input>
              </div>

              <!-- Owner -->
              <div class="mb-4">
                <el-input placeholder="مالک" v-model="owner"></el-input>
              </div>

              <!-- Phone Owner -->
              <div class="mb-4">
                <el-input placeholder="شماره تلفن مالک" type="number" v-model="phone_owner"></el-input>
              </div>

              <!-- Area -->
              <div class="mt-4 ranges">
                <p class="text-center">متراژ</p>
                <div class="d-flex align-items-center mt-2">
                  <el-input placeholder="حداقل متراژ" type="number" v-model="area.min.value"></el-input>
                  <span class="mx-2">-</span>
                  <el-input placeholder="حداکثر متراژ" type="number" v-model="area.max.value"></el-input>
                </div>
              </div>

              <!-- Dynamic Filters -->
              <div
                class="mt-4"
                v-for="filter in ( dynamic_filters.spec ? dynamic_filters.spec.filters : [] )"
                :key="filter.id"
              >
                <template v-if="is_exist(filter.defaults)">
                  <el-select
                    v-model="bind_filters[filter.id]"
                    :placeholder="filter.title"
                    multiple
                    collapse-tags
                  >
                    <el-option
                      class="rtl"
                      v-for="item in filter.defaults"
                      :key="item.id"
                      :label="filter.prefix +' '+ item.value +' '+ filter.postfix"
                      :value="item.id"
                    ></el-option>
                  </el-select>
                </template>

                <template v-else>
                  <el-input :placeholder="filter.title" v-model="bind_filters[filter.id]"></el-input>
                </template>
              </div>

              <!-- Estate Options -->
              <div class="mt-4">
                <el-select
                  v-model="options_select"
                  multiple
                  collapse-tags
                  :no-data-text=" estate_types_select ? 'موردی وجود ندارد' : 'نوع ملک انتخاب نشده' "
                  placeholder="امکانات"
                >
                  <el-option
                    v-for="item in dynamic_filters.features"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id"
                  ></el-option>
                </el-select>
              </div>

              <!-- Sales Price Input -->
              <div class="mt-4 ranges" v-if="active_ranges.has_sales_price">
                <p class="text-center">قیمت ملک (تومان)</p>
                <div class="d-flex align-items-center mt-2">
                  <el-input placeholder="حداقل قیمت" type="number" v-model="sales_price.min.value"></el-input>
                  <span class="mx-2">-</span>
                  <el-input placeholder="حداکثر قیمت" type="number" v-model="sales_price.max.value"></el-input>
                </div>
              </div>

              <!-- Mortgage Price Input -->
              <div class="mt-4 ranges" v-if="active_ranges.has_mortgage_price">
                <p class="text-center">قیمت رهن (تومان)</p>
                <div class="d-flex align-items-center">
                  <el-input placeholder="حداقل رهن" v-model="mortgage_price.min.value"></el-input>
                  <span class="mx-2">-</span>
                  <el-input placeholder="حداکثر رهن" v-model="mortgage_price.max.value"></el-input>
                </div>
              </div>

              <!-- Rental Price Input -->
              <div class="mt-4 ranges" v-if="active_ranges.has_rental_price">
                <p class="text-center">قیمت اجاره (تومان)</p>
                <div class="d-flex align-items-center">
                  <el-input placeholder="حداقل اجاره" v-model="rental_price.min.value"></el-input>
                  <span class="mx-2">-</span>
                  <el-input placeholder="حداکثر اجاره" v-model="rental_price.max.value"></el-input>
                </div>
              </div>
            </div>
          </v-card-text>

          <div class="row m-0 mb-1 d-flex">
            <v-btn
              :color="web_color_dark"
              dark
              large
              width="50%"
              @click="Apply_filters(false)"
            >جستجو</v-btn>
            <v-btn class="mv-25" color="#E91E63" dark large @click="Delete_filters">
              <v-icon dark>delete</v-icon>
            </v-btn>
          </div>
        </v-card>
      </v-dialog>
    </v-app>

    <vs-popup class="dialog_sort" title="مرتب سازی" :active.sync="$store.state.dialog_sort">
      <v-radio-group v-model="ordering">
        <v-radio
          class="mb-3"
          :color="web_color"
          v-for="item in sort_items"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        ></v-radio>
      </v-radio-group>
    </vs-popup>

    <div class="share-btn animated zoomIn" v-if="is_exist(this.$route.query)">
      <v-speed-dial v-model="fab" direction="top" transition="slide-y-reverse-transition">
        <template v-slot:activator>
          <v-tooltip right>
            <template v-slot:activator="{ on }">
              <v-btn v-model="fab" v-on="on" color="#484848" small dark fab>
                <v-icon v-if="fab">close</v-icon>
                <v-icon v-else>share</v-icon>
              </v-btn>
            </template>
            <span>اشتراک گذاری این لیست املاک</span>
          </v-tooltip>
        </template>

        <social-sharing
          :url="`http://maskanshow.ir${$route.fullPath}`"
          title="مسکن شو"
          :description="`${ (title || 'ملک های مشهد') + ' | مسکن شو' }`"
          inline-template
        >
          <network network="telegram">
            <v-btn color="#1D93D4" fab dark small>
              <i class="fab fa-telegram-plane fs-18"></i>
            </v-btn>
          </network>
        </social-sharing>

        <social-sharing
          :url="`http://maskanshow.ir${$route.fullPath}`"
          title="مسکن شو"
          :description="`${ (title || 'ملک های مشهد') + ' | مسکن شو' }`"
          inline-template
        >
          <network network="whatsapp">
            <v-btn color="#0EE676" fab dark small>
              <i class="fab fa-whatsapp fs-18"></i>
            </v-btn>
          </network>
        </social-sharing>
      </v-speed-dial>
    </div>
  </div>
</template>

<script>
import {
  VBreadcrumbs,
  VBtn,
  VIcon,
  VAvatar,
  VList,
  VListTile,
  VListTileTitle,
  VListTileContent,
  VListTileAction,
  VCheckbox,
  VDialog,
  VCard,
  VCardText,
  VRadio,
  VSpeedDial,
  VTooltip,
  VRadioGroup,
  VToolbar,
  VToolbarTitle,
  VSpacer
} from "vuetify/lib";
import { Select, Option, Popover, Button, Cascader, Input } from "element-ui";
import { mapState, mapMutations } from "vuex";
import mixin from "../../mixin";
import moment from "../../moment";
import Estates from "./Properties/Estates.vue";
import DatetimePicker from "vue-persian-datetime-picker";
import qrcode from "@chenfengyuan/vue-qrcode";

export default {
  mixins: [mixin, moment],

  beforeRouteEnter(to, from, next) {
    if (from.path == "/") {
      scrollTo(document.body, 0, 1000);
    }
    next(vm => {
      vm.prevRoute = from;
    });
  },

  metaInfo() {
    return {
      title: this.title || "ملک های مشهد"
    };
  },

  components: {
    Estates,
    datePicker: DatetimePicker,
    qrcode,
    elSelect: Select,
    elOption: Option,
    elPopover: Popover,
    elButton: Button,
    elCascader: Cascader,
    elInput: Input,
    VBreadcrumbs,
    VBtn,
    VIcon,
    VAvatar,
    VList,
    VListTile,
    VListTileTitle,
    VListTileContent,
    VListTileAction,
    VCheckbox,
    VDialog,
    VCard,
    VCardText,
    VRadio,
    VSpeedDial,
    VTooltip,
    VRadioGroup,
    VToolbar,
    VToolbarTitle,
    VSpacer
  },

  created() {
    if (this.username_clt) {
      axios({
        method: "POST",
        url: this.req_url,
        data: {
          query: `
                            {
                                office( username : "${this.username_clt}" ) {
                                    id
                                    name
                                    phone_number
                                    username
                                    area {
                                        name
                                        streets {
                                            id
                                            name
                                        }
                                    }
                                    address
                                    description
                                    owner {
                                        first_name
                                        last_name
                                        full_name
                                        avatar {
                                            id
                                            file_name
                                            medium
                                        }
                                    }
                                }
                            }
                        `
        }
      })
        .then(({ data }) => {
          if (this.is_exist(data.data.office)) {
            this.Set_state({ prop: "office", val: data.data.office });
          } else {
            this.$router.push("/404-nf");
          }
        })
        .then(() => {
          this.Req_estates();
        })
        .catch(Err => {
          console.error(Err);
        });
    } else {
      this.Set_state({ prop: "office", val: {} });
      this.Req_estates();
    }

    this.set_default_filters();
  },

  mounted() {
    $(document).ready(function() {
      $(".date-picker")
        .find("input")
        .attr("id", "date-picker");
      if ($(window).width() > 992) $(".bg-parallax").parallax();
    });
  },

  data() {
    return {
      fab: false,

      bind_filters: {},

      consultant: "",

      areas_select: [],
      street_select: "",

      assignments_select: "",
      estate_types_select: "",
      options_select: [],

      search: "",
      code_estate: "",
      owner: "",
      phone_owner: "",
      date: "",

      ordering: "latest",
      apply_ordering: true,
      is_assignemnt: false,

      area: {
        is_active: false,
        popover: false,
        min: {
          value: "",
          active_defaults: true,
          defaults: [
            { value: 0, disabled: false },
            { value: 50, disabled: false },
            { value: 75, disabled: false },
            { value: 100, disabled: false },
            { value: 125, disabled: false },
            { value: 150, disabled: false },
            { value: 175, disabled: false },
            { value: 200, disabled: false },
            { value: 250, disabled: false },
            { value: 300, disabled: false },
            { value: 400, disabled: false },
            { value: 500, disabled: false },
            { value: 600, disabled: false },
            { value: 700, disabled: false },
            { value: 800, disabled: false },
            { value: 900, disabled: false },
            { value: 1000, disabled: false }
          ]
        },
        max: {
          value: "",
          active_defaults: false,
          defaults: [
            { value: 0, disabled: false },
            { value: 50, disabled: false },
            { value: 75, disabled: false },
            { value: 100, disabled: false },
            { value: 125, disabled: false },
            { value: 150, disabled: false },
            { value: 175, disabled: false },
            { value: 200, disabled: false },
            { value: 250, disabled: false },
            { value: 300, disabled: false },
            { value: 400, disabled: false },
            { value: 500, disabled: false },
            { value: 600, disabled: false },
            { value: 700, disabled: false },
            { value: 800, disabled: false },
            { value: 900, disabled: false },
            { value: 1000, disabled: false }
          ]
        },
        label: "متراژ",
        default_label: "متراژ"
      },

      sales_price: {
        popover: false,
        min: {
          value: "",
          active_defaults: true,
          defaults: [
            { value: 0, disabled: false },
            { value: 50000000, disabled: false },
            { value: 100000000, disabled: false },
            { value: 150000000, disabled: false },
            { value: 200000000, disabled: false },
            { value: 250000000, disabled: false },
            { value: 300000000, disabled: false },
            { value: 350000000, disabled: false },
            { value: 400000000, disabled: false },
            { value: 450000000, disabled: false },
            { value: 500000000, disabled: false },
            { value: 600000000, disabled: false },
            { value: 700000000, disabled: false },
            { value: 800000000, disabled: false },
            { value: 900000000, disabled: false },
            { value: 1000000000, disabled: false },
            { value: 1500000000, disabled: false },
            { value: 2000000000, disabled: false },
            { value: 3000000000, disabled: false },
            { value: 4000000000, disabled: false },
            { value: 5000000000, disabled: false }
          ]
        },
        max: {
          value: "",
          active_defaults: false,
          defaults: [
            { value: 0, disabled: false },
            { value: 50000000, disabled: false },
            { value: 100000000, disabled: false },
            { value: 150000000, disabled: false },
            { value: 200000000, disabled: false },
            { value: 250000000, disabled: false },
            { value: 300000000, disabled: false },
            { value: 350000000, disabled: false },
            { value: 400000000, disabled: false },
            { value: 450000000, disabled: false },
            { value: 500000000, disabled: false },
            { value: 600000000, disabled: false },
            { value: 700000000, disabled: false },
            { value: 800000000, disabled: false },
            { value: 900000000, disabled: false },
            { value: 1000000000, disabled: false },
            { value: 1500000000, disabled: false },
            { value: 2000000000, disabled: false },
            { value: 3000000000, disabled: false },
            { value: 4000000000, disabled: false },
            { value: 5000000000, disabled: false }
          ]
        },
        label: "قیمت ملک",
        default_label: "قیمت ملک"
      },

      mortgage_price: {
        popover: false,
        min: {
          value: "",
          active_defaults: true,
          defaults: [
            { value: 0, disabled: false },
            { value: 2000000, disabled: false },
            { value: 5000000, disabled: false },
            { value: 10000000, disabled: false },
            { value: 15000000, disabled: false },
            { value: 20000000, disabled: false },
            { value: 30000000, disabled: false },
            { value: 40000000, disabled: false },
            { value: 50000000, disabled: false },
            { value: 60000000, disabled: false },
            { value: 70000000, disabled: false },
            { value: 80000000, disabled: false },
            { value: 90000000, disabled: false },
            { value: 100000000, disabled: false },
            { value: 150000000, disabled: false },
            { value: 250000000, disabled: false },
            { value: 500000000, disabled: false }
          ]
        },
        max: {
          value: "",
          active_defaults: false,
          defaults: [
            { value: 0, disabled: false },
            { value: 2000000, disabled: false },
            { value: 5000000, disabled: false },
            { value: 10000000, disabled: false },
            { value: 15000000, disabled: false },
            { value: 20000000, disabled: false },
            { value: 30000000, disabled: false },
            { value: 40000000, disabled: false },
            { value: 50000000, disabled: false },
            { value: 60000000, disabled: false },
            { value: 70000000, disabled: false },
            { value: 80000000, disabled: false },
            { value: 90000000, disabled: false },
            { value: 100000000, disabled: false },
            { value: 150000000, disabled: false },
            { value: 250000000, disabled: false },
            { value: 500000000, disabled: false }
          ]
        },
        label: "رهن",
        default_label: "رهن"
      },

      rental_price: {
        popover: false,
        min: {
          value: "",
          active_defaults: true,
          defaults: [
            { value: 0, disabled: false },
            { value: 100000, disabled: false },
            { value: 200000, disabled: false },
            { value: 300000, disabled: false },
            { value: 400000, disabled: false },
            { value: 500000, disabled: false },
            { value: 600000, disabled: false },
            { value: 700000, disabled: false },
            { value: 800000, disabled: false },
            { value: 900000, disabled: false },
            { value: 1000000, disabled: false },
            { value: 1100000, disabled: false },
            { value: 1200000, disabled: false },
            { value: 1300000, disabled: false },
            { value: 1400000, disabled: false },
            { value: 1500000, disabled: false },
            { value: 2000000, disabled: false },
            { value: 3000000, disabled: false },
            { value: 4000000, disabled: false },
            { value: 5000000, disabled: false }
          ]
        },
        max: {
          value: "",
          active_defaults: false,
          defaults: [
            { value: 0, disabled: false },
            { value: 100000, disabled: false },
            { value: 200000, disabled: false },
            { value: 300000, disabled: false },
            { value: 400000, disabled: false },
            { value: 500000, disabled: false },
            { value: 600000, disabled: false },
            { value: 700000, disabled: false },
            { value: 800000, disabled: false },
            { value: 900000, disabled: false },
            { value: 1000000, disabled: false },
            { value: 1100000, disabled: false },
            { value: 1200000, disabled: false },
            { value: 1300000, disabled: false },
            { value: 1400000, disabled: false },
            { value: 1500000, disabled: false },
            { value: 2000000, disabled: false },
            { value: 3000000, disabled: false },
            { value: 4000000, disabled: false },
            { value: 5000000, disabled: false }
          ]
        },
        label: "اجاره",
        default_label: "اجاره"
      },

      query_fields: {
        search: "query",
        code_estate: "code",
        owner: "landloard_fullname",
        phone_owner: "landloard_phone_number",
        date: "created_at",
        areas_select: "areas",
        assignments_select: "assignments",
        estate_types_select: "estate_types",
        options_select: "features"
      },

      ranges_fields: ["area", "sales_price", "mortgage_price", "rental_price"],

      page:
        this.$route.query.page && eval(this.$route.query.page)
          ? eval(this.$route.query.page)
          : 1,
      per_page: 20
    };
  },

  computed: {
    ...mapState([
      "dynamic_filters",
      "city_areas",
      "area_streets",
      "assignments",
      "estate_types",
      "dialog_filters",
      "pagination",
      "req_url",
      "office",
      "url"
    ]),

    custom_estate_types() {
      if (this.assignments_select) {
        let obj = this.assignments.find(el => el.id == this.assignments_select);
        return obj ? obj.estate_types : [];
      } else {
        return this.estate_types;
      }
    },

    total() {
      return Math.ceil(this.pagination.total / this.per_page);
    },

    set_disabled() {
      this.$store.state.city_areas.map(el => {
        el.streets.map(el => (el.disabled = true));
      });

      return "منطقه";
    },

    username_clt() {
      return this.$route.params.username || null;
    },

    title() {
      if (this.username_clt && this.is_exist(this.office)) {
        return `ملک های ${this.office.name}`;
      } else if (this.is_exist(this.Breadcrumb[2])) {
        return this.Breadcrumb[2].text;
      }
    },

    Breadcrumb() {
      let areas = [];
      if (
        this.is_exist(this.$route.query.areas) &&
        typeof this.$route.query.areas == "object"
      ) {
        this.$route.query.areas.map(el => {
          let obj = _.find(this.city_areas, ["id", parseInt(el)]);
          if (obj) areas.push(obj.name);
        });
      }
      let assign = _.find(this.assignments, [
        "id",
        parseInt(this.$route.query.assignments)
      ]);
      let esatetType = _.find(this.estate_types, [
        "id",
        parseInt(this.$route.query.estate_types)
      ]);
      let arr = [
        {
          text: "خانه",
          disabled: false,
          to: "/"
        },
        {
          text: "املاک",
          disabled: true,
          to: "/"
        }
      ];

      if (this.is_exist(assign) && this.is_exist(esatetType)) {
        arr.push({
          text: assign.title + " " + esatetType.title,
          disabled: true,
          to: "/"
        });
      } else if (this.is_exist(assign)) {
        arr.push({
          text: assign.title,
          disabled: true,
          to: "/"
        });
      } else if (this.is_exist(esatetType)) {
        arr.push({
          text: esatetType.title,
          disabled: true,
          to: "/"
        });
      } else if (this.is_exist(areas)) {
        arr.push({
          text: `ملک های ${areas.join(" و ")}`,
          disabled: true,
          to: "/"
        });
      }

      return arr;
    },

    sort_items() {
      let arr = [
        {
          label: "جدیدترین",
          value: "latest"
        },
        {
          label: "قدیمی‌ترین",
          value: "oldest"
        }
      ];

      if (this.assignments_select) {
        this.assignments.map(el => {
          if (el.id == this.assignments_select) {
            if (el.has_sales_price) {
              let temp = [
                {
                  label: "بالاترین قیمت خرید",
                  value: "max_sales_price"
                },
                {
                  label: "پایین‌ترین قیمت خرید",
                  value: "min_sales_price"
                }
              ];
              arr = [...arr, ...temp];
            } else if (el.has_mortgage_price && el.has_rental_price) {
              let temp = [
                {
                  label: "بالاترین قیمت رهن",
                  value: "max_mortgage_price"
                },
                {
                  label: "پایین‌ترین قیمت رهن",
                  value: "min_mortgage_price"
                },
                {
                  label: "بالاترین قیمت اجاره",
                  value: "max_rental_price"
                },
                {
                  label: "پایین‌ترین قیمت اجاره",
                  value: "min_rental_price"
                }
              ];
              arr = [...arr, ...temp];
            } else if (el.has_mortgage_price || el.has_rental_price) {
              let temp = [
                {
                  label: el.has_mortgage_price
                    ? "بالاترین قیمت رهن"
                    : "بالاترین قیمت اجاره",
                  value: el.has_mortgage_price
                    ? "max_mortgage_price"
                    : "max_rental_price"
                },
                {
                  label: el.has_mortgage_price
                    ? "پایین‌ترین قیمت رهن"
                    : "پایین‌ترین قیمت اجاره",
                  value: el.has_mortgage_price
                    ? "min_mortgage_price"
                    : "min_rental_price"
                }
              ];
              arr = [...arr, ...temp];
            }
          }
        });
      }

      return arr;
    },

    active_ranges() {
      let obj = this.assignments.filter(
        el => el.id == this.assignments_select
      )[0];
      if (this.is_exist(obj)) {
        return obj;
      } else {
        return {};
      }
    }
  },

  watch: {
    page() {
      this.Apply_filters(true);
    },

    areas_select(val) {
      if (typeof val != "object" || !val || !val.flat()) this.areas_select = [];
    },

    "$route.path"() {
      if (this.username_clt) {
        axios({
          method: "POST",
          url: this.req_url,
          data: {
            query: `
                                {
                                    office( username : "${this.username_clt}" ) {
                                        id
                                        name
                                        phone_number
                                        area {
                                        name
                                        streets {
                                            id
                                            name
                                        }
                                        }
                                        address
                                        owner {
                                        avatar {
                                            id
                                            file_name
                                            medium
                                        }
                                        }
                                    }
                                }
                            `
          }
        })
          .then(({ data }) => {
            if (this.is_exist(data.data.office)) {
              this.Set_state({ prop: "office", val: data.data.office });
            }
          })
          .then(() => {
            this.Apply_filters(false);
          })
          .catch(Err => {
            console.error(Err);
          });
      } else {
        this.Set_state({ prop: "office", val: {} });
        this.Apply_filters(false);
      }

      this.set_default_filters();
    },

    options_select(val) {
      if (typeof val == "object") {
        for (let idx in this.options_select) {
          if (eval(this.options_select[idx])) {
            this.options_select[idx] = eval(this.options_select[idx]);
          }
        }
      }
    },

    ordering(val) {
      if (!val) this.ordering = "latest";
      if (this.apply_ordering) {
        this.Apply_filters(false);
      } else {
        this.apply_ordering = true;
      }
      this.Set_state({ prop: "dialog_sort", val: false });
    },

    is_assignemnt() {
      this.Apply_filters(false);
    },

    active_ranges() {
      if (this.ordering != "latest" || this.ordering != "oldest")
        this.ordering = "latest";
    }
  },

  methods: {
    ...mapMutations(["Req_data", "Set_state"]),

    Req_estates() {
      let query = `
                    {
                        estates(
                            page : ${
                              this.$route.query.page &&
                              eval(this.$route.query.page)
                                ? eval(this.$route.query.page)
                                : 1
                            } ,
                            per_page : ${this.per_page} ,
                            ${
                              this.is_exist(this.office) && !!this.office.id
                                ? `consultant : ${this.office.id} ,`
                                : ""
                            }
                            query : "${this.$route.query.query || ""}" ,
                            code : "${this.$route.query.code || ""}" ,
                            landloard_fullname : "${this.$route.query
                              .landloard_fullname || ""}" ,
                            landloard_phone_number : "${this.$route.query
                              .landloard_phone_number || ""}" ,
                            created_at : "${this.to_en(
                              this.$route.query.created_at
                            )}" ,
                            areas : [${this.$route.query.areas || ""}] ,
                            ${
                              this.$route.query.assignments
                                ? `assignment : ${this.$route.query.assignments} ,`
                                : ""
                            }
                            ${
                              this.$route.query.estate_types
                                ? `estate_type : ${this.$route.query.estate_types} ,`
                                : ""
                            }
                            features : [${this.$route.query.features || ""}] ,
                            dynamic_filters : [${this.get_all_dynamic_filters()}] ,
                            area : {
                                min : "${
                                  this.$route.query.area
                                    ? this.$route.query.area[0]
                                    : ""
                                }" ,
                                max : "${
                                  this.$route.query.area
                                    ? this.$route.query.area[1]
                                    : ""
                                }"
                            } ,
                            sales_price : {
                                min : "${
                                  this.$route.query["sales_price[min]"]
                                    ? this.$route.query["sales_price[min]"]
                                    : ""
                                }" ,
                                max : "${
                                  this.$route.query["sales_price[max]"]
                                    ? this.$route.query["sales_price[max]"]
                                    : ""
                                }"
                            } ,
                            mortgage_price : {
                                min : "${
                                  this.$route.query["mortgage_price[min]"]
                                    ? this.$route.query["mortgage_price[min]"]
                                    : ""
                                }" ,
                                max : "${
                                  this.$route.query["mortgage_price[max]"]
                                    ? this.$route.query["mortgage_price[max]"]
                                    : ""
                                }"
                            } ,
                            rental_price : {
                                min : "${
                                  this.$route.query["rental_price[min]"]
                                    ? this.$route.query["rental_price[min]"]
                                    : ""
                                }" ,
                                max : "${
                                  this.$route.query["rental_price[max]"]
                                    ? this.$route.query["rental_price[max]"]
                                    : ""
                                }"
                            }
                        ) {
                            data {
                                id
                                code
                                title
                                is_active
                                is_mine
                                is_favorite
                                photos {
                                    id
                                    file_name
                                    medium
                                }
                                address
                                area
                                rental_price
                                mortgage_price
                                sales_price
                                created_at
                                assignmented_at
                                label {
                                    id
                                    title
                                    color
                                }
                                want_cooperation
                                registrar_type {
                                    id
                                    display_name
                                    description
                                }
                                creator {
                                    id
                                    username
                                    is_public_info
                                    first_name
                                    last_name
                                    full_name
                                    avatar {
                                        id
                                        file_name
                                        small
                                    }
                                }
                                specifications {
                                    id
                                    data
                                    values {
                                        id
                                        value
                                    }
                                    row {
                                        prefix
                                        postfix
                                        icon
                                    }
                                }
                                detailable_features {
                                    id
                                    icon
                                    title
                                }
                                assignment {
                                    id
                                    title
                                    color
                                }
                                estate_type {
                                    id
                                    title
                                }
                                street {
                                    id
                                    name
                                    area {
                                        id
                                        name
                                    }
                                }
                            }
                            total
                        }
                    }
                `;

      this.Req_data({
        query: query,
        props: ["estates"],
        states: ["Estates"]
      });
    },

    Apply_filters(watch_page = false) {
      if (!watch_page) {
        this.page = 1;
      } else {
        this.$vuetify.goTo(400, {
          duration: 1000
        });
      }

      if (this.Res) this.Set_state({ prop: "dialog_filters", val: false });

      let query = `
                    {
                        estates(
                            page : ${this.page} ,
                            per_page : ${this.per_page} ,
                            ordering : "${this.ordering}" ,
                            ${
                              this.is_exist(this.office) && !!this.office.id
                                ? `consultant : ${this.office.id} ,`
                                : ""
                            }
                            query : "${this.search}" ,
                            code : "${this.code_estate}" ,
                            landloard_fullname : "${this.owner}" ,
                            landloard_phone_number : "${this.phone_owner}" ,
                            created_at : "${this.to_en(this.date)}" ,
                            areas : [${
                              this.is_exist(this.areas_select)
                                ? this.areas_select.flat()
                                : []
                            }] ,
                            ${
                              this.assignments_select
                                ? `assignment : ${this.assignments_select} ,`
                                : ""
                            }
                            ${
                              this.estate_types_select
                                ? `estate_type : ${this.estate_types_select} ,`
                                : ""
                            }
                            features : [${this.options_select}] ,
                            dynamic_filters : [${this.get_all_dynamic_filters()}] ,
                            area : {
                                min : "${this.area.min.value}" ,
                                max : "${this.area.max.value}"
                            } ,
                            sales_price : {
                                min : "${this.sales_price.min.value}" ,
                                max : "${this.sales_price.max.value}"
                            } ,
                            mortgage_price : {
                                min : "${this.mortgage_price.min.value}" ,
                                max : "${this.mortgage_price.max.value}"
                            } ,
                            rental_price : {
                                min : "${this.rental_price.min.value}" ,
                                max : "${this.rental_price.max.value}"
                            } ,
                            ${
                              this.is_assignemnt
                                ? "is_assignmented : false"
                                : ""
                            }
                        ) {
                            data {
                                id
                                code
                                title
                                is_active
                                is_mine
                                is_favorite
                                photos {
                                    id
                                    file_name
                                    medium
                                }
                                address
                                area
                                rental_price
                                mortgage_price
                                sales_price
                                created_at
                                assignmented_at
                                label {
                                    id
                                    title
                                    color
                                }
                                want_cooperation
                                registrar_type {
                                    id
                                    display_name
                                    description
                                }
                                creator {
                                    id
                                    username
                                    is_public_info
                                    first_name
                                    last_name
                                    full_name
                                    avatar {
                                        id
                                        file_name
                                        small
                                    }
                                }
                                specifications {
                                    id
                                    data
                                    values {
                                        id
                                        value
                                    }
                                    row {
                                        prefix
                                        postfix
                                        icon
                                    }
                                }
                                detailable_features {
                                    id
                                    icon
                                    title
                                }
                                assignment {
                                    id
                                    title
                                    color
                                }
                                estate_type {
                                    id
                                    title
                                }
                                street {
                                    id
                                    name
                                    area {
                                        id
                                        name
                                    }
                                }
                            }
                            total
                        }
                    }
                `;

      this.Req_data({
        query: query,
        props: ["estates"],
        states: ["Estates"]
      });

      let query_str = {};

      query_str.page = this.page;

      Object.keys(this.query_fields).map(el => {
        if (this.is_exist(this[el])) {
          query_str[this.query_fields[el]] = this[el];
        }
      });

      this.ranges_fields.map(el => {
        if (this[el].min.value || this[el].max.value) {
          if (this[el].min.value) query_str[el + "[min]"] = this[el].min.value;
          if (this[el].max.value) query_str[el + "[max]"] = this[el].max.value;
        }
      });

      if (this.is_exist(this.bind_filters)) {
        query_str.dynamic_filters = JSON.stringify(this.bind_filters);
      }

      this.$router.replace({
        path:
          this.is_exist(this.$route.params) && !!this.$route.params.username
            ? "/" + this.$route.params.username
            : "/properties",
        query: query_str
      });
    },

    Delete_filters() {
      Object.keys(this.query_fields).map(el => {
        this[el] = "";
      });

      this.areas_select = [];

      this.ranges_fields.map(el => {
        this.Cansel(el);
      });

      this.Set_state({
        prop: "dynamic_filters",
        val: {
          spec: {
            filters: []
          }
        }
      });

      this.bind_filters = {};

      this.is_assignemnt = false;
      this.apply_ordering = false;
      this.ordering = "latest";

      this.Apply_filters();
    },

    set_default_filters() {
      let query_str = this.$route.query;

      Object.keys(this.query_fields).map(el => {
        if (el != "date" && eval(query_str[this.query_fields[el]])) {
          if (el == "options_select" || el == "areas_select") {
            typeof query_str[this.query_fields[el]] === "object"
              ? (this[el] = query_str[this.query_fields[el]])
              : this[el].push(eval(query_str[this.query_fields[el]]));
          } else {
            this[el] = eval(query_str[this.query_fields[el]]) || "";
          }
        } else {
          this[el] = query_str[this.query_fields[el]] || "";
        }
      });

      this.ranges_fields.map(el => {
        if (query_str[el + "[min]"]) {
          this[el].min.value = query_str[el + "[min]"];
          this.filter_range(el, this[el].default_label);
        }
        if (query_str[el + "[max]"]) {
          this[el].max.value = query_str[el + "[max]"];
          this.filter_range(el, this[el].default_label);
        }
      });

      if (!!this.estate_types_select) this.set_dynamic_filters();
      if (
        this.is_exist(query_str.dynamic_filters) &&
        JSON.parse(query_str.dynamic_filters)
      ) {
        this.bind_filters = {
          ...this.bind_filters,
          ...JSON.parse(query_str.dynamic_filters)
        };
      }
    },

    set_dynamic_filters() {
      let target_loading = document.querySelectorAll("#filter-bar");
      if (!this.Res && target_loading.length !== 0) {
        this.$vs.loading({
          container: "#filter-bar",
          scale: 1,
          type: "sound",
          color: this.web_color
        });
      }

      this.bind_filters = {};
      this.Set_state({
        prop: "dynamic_filters",
        val: {
          spec: {
            filters: []
          }
        }
      });
      this.Req_data({
        query: `
                        {
                            estate_type( id : ${this.estate_types_select} ) {
                                id
                                title
                                features {
                                    id
                                    title
                                    icon
                                }
                                spec {
                                    id
                                    title
                                    filters {
                                        prefix
                                        postfix
                                        id
                                        title
                                        defaults {
                                            id
                                            value
                                        }
                                    }
                                }
                            }
                        }`,
        props: ["estate_type"],
        states: ["dynamic_filters"],
        is_object: true,
        loading: false,
        changeDataResolver: data => {
          data.data.estate_type.features = _.orderBy(
            data.data.estate_type.features,
            "title",
            "asc"
          );
          return data;
        },
        afterResolver: () => {
          $(document).ready(function() {
            setTimeout(() => {
              $("#filter-bar .con-vs-loading").fadeOut();
              setTimeout(() => {
                $("#filter-bar .con-vs-loading").remove();
              }, 100);
            }, 1000);
          });
        }
      });
    },

    set_ranges(state, range, index, val) {
      this[state][range].value = val;
      if (range == "min") {
        this[state].max.defaults.map(el => (el.disabled = false));
        if (index)
          for (let i = index; i >= 0; i--) {
            this[state].max.defaults[i].disabled = true;
          }
      } else if (range == "max") {
        this[state].min.defaults.map(el => (el.disabled = false));
        if (index)
          for (let j = index; j < this[state].min.defaults.length; j++) {
            this[state].min.defaults[j].disabled = true;
          }
      }
    },

    focus_min(state) {
      this[state].max.active_defaults = false;
      this[state].min.active_defaults = true;
    },

    focus_max(state) {
      this[state].min.active_defaults = false;
      this[state].max.active_defaults = true;
    },

    filter_range(state, label) {
      let prices = [
        this[state].min.value / 1000000,
        this[state].max.value / 1000000
      ];

      if (!!prices[0] && !!prices[1]) {
        this[state].label = `${label} از ${prices[0].toLocaleString(
          "fa-IR"
        )} تا ${prices[1].toLocaleString("fa-IR")} میلیون تومان`;
      } else if (!!prices[0]) {
        this[state].label = `${label} از ${prices[0].toLocaleString(
          "fa-IR"
        )} میلیون تومان`;
      } else if (!!prices[1]) {
        this[state].label = `${label} تا ${prices[1].toLocaleString(
          "fa-IR"
        )} میلیون تومان`;
      }

      this[state].popover = false;
    },

    filter_area() {
      let areas = [this.area.min.value, this.area.max.value];

      if (!!areas[0] && !!areas[1]) {
        this.area.label = `${
          this.area.default_label
        } از ${areas[0].toLocaleString("fa-IR")} تا ${areas[1].toLocaleString(
          "fa-IR"
        )} متر`;
      } else if (!!areas[0]) {
        this.area.label = `${
          this.area.default_label
        } از ${areas[0].toLocaleString("fa-IR")} متر`;
      } else if (!!areas[1]) {
        this.area.label = `${
          this.area.default_label
        } تا ${areas[1].toLocaleString("fa-IR")} متر`;
      }

      this.area.popover = false;
    },

    Cansel(state) {
      this[state].label = this[state].default_label;
      this[state].min.value = "";
      this[state].min.defaults.map(el => (el.disabled = false));
      this[state].max.value = "";
      this[state].max.defaults.map(el => (el.disabled = false));
      this[state].popover = false;
    },

    get_all_dynamic_filters() {
      let query = "";
      Object.keys(this.bind_filters).map((el, idx) => {
        if (this.is_exist(this.bind_filters[el])) {
          query += `{
                            row_id : ${parseInt(el)} ,
                            ${
                              typeof this.bind_filters[el] == "string"
                                ? `data : "${this.bind_filters[el]}"`
                                : `values : [${this.bind_filters[el].map(el =>
                                    parseInt(el)
                                  )}]`
                            }

                        }`;
        }
      });
      return query;
    }
  }
};
</script>

<style>
.share-btn {
  z-index: 200;
  position: fixed;
  bottom: 20px;
  left: 20px;
}

.fs-18 {
  font-size: 18px !important;
}

.con-vs-loading {
  background: hsla(0, 0%, 100%, 0.95) !important;
  border-radius: 20px;
}

.checkbox.assign label {
  font-size: 14px;
}

.v-image__image {
  transition: filter 0.3s;
}

.grid-list-btn
  .el-radio-button__orig-radio:checked
  + .el-radio-button__inner
  .v-image__image {
  filter: invert(1);
}

.mv-25 {
  max-width: 25% !important;
}

.is_assignemnt .vs-checkbox {
  margin-left: 10px;
}

.vs-input--placeholder {
  color: rgba(0, 0, 0, 0.6);
}

.dialog_sort .vs-popup {
  width: 300px !important;
}

.dialog_sort .vs-popup .v-input {
  justify-content: flex-end;
}

.dialog_sort .v-input__slot {
  border: unset !important;
  margin: 0px !important;
  direction: rtl;
}

.dialog_sort label {
  margin-bottom: 0px !important;
  margin-right: 10px !important;
}

.sort-btn .el-select {
  max-width: 180px;
}

.el-cascader {
  overflow: hidden;
}

.el-cascader__tags {
  flex-wrap: unset !important;
  right: 10px !important;
  max-width: 110px;
}

.el-select__tags {
  flex-wrap: unset !important;
}

.el-select {
  overflow: hidden;
}

.el-cascader__dropdown svg path {
  fill: transparent !important;
}

.el-cascader-node > .el-checkbox {
  margin-bottom: 0px !important;
}

.el-cascader-node__label {
  font-size: 12px !important;
}

.el-select-group__title {
  padding-left: 0px !important;
  padding-right: 20px !important;
  text-align: right;
}

.date-picker input {
  cursor: pointer;
}

.el-select .el-tag__close.el-icon-close {
  right: unset !important;
  top: 1px !important;
  left: -5px !important;
}

.el-cascader__tags .el-tag .el-icon-close {
  right: unset !important;
  top: 1px !important;
  left: -5px !important;
}

.el-select .el-tag {
  margin-right: 7px;
}

.el-select-dropdown.is-multiple .el-select-dropdown__item.selected::after {
  right: unset !important;
  left: 10px !important;
}

/* Div Ranges In Modal Responsive */

.ranges input {
  text-align: center;
}

/*  Dialog Filters Styles  */

.dialog_filters .el-input__inner {
  height: 50px !important;
  background: #f7f8fa;
  border: unset;
  border-radius: 7px;
}

.dialog_filters .btn-range {
  height: 50px;
  background: #f7f8fa;
  border: unset;
  border-radius: 7px;
}

.dialog_filters .btn-range {
  max-width: unset !important;
}

.dialog_filters .el-select {
  width: 100%;
}

/*  Div Default Prices  */

.default-prices {
  overflow-y: auto;
  overflow-x: hidden !important;
  max-height: 200px;
  direction: rtl;
}

.default-prices .el-select .el-input__suffix {
  left: 15px;
  right: unset !important;
}

.default-prices .el-select .el-input .el-select__caret {
  color: #3b4144 !important;
}

.default-prices .v-list__tile {
  height: 30px !important;
  font-size: 13px !important;
}

.default-prices.input_min .v-list__tile__title {
  text-align: right !important;
}

.default-prices .animated {
  -webkit-animation-duration: 0.3s !important;
  animation-duration: 0.3s !important;
}

/* Button Set Range */

.btn-range {
  position: relative;
  padding-right: 15px !important;
  max-width: 215px;
  overflow: hidden;
  text-overflow: ellipsis;
}

.btn-range i {
  position: absolute;
  left: 10px;
}

.btn-range span {
  color: #898989;
  /* font-size: 12px; */
}

.el-scrollbar.el-cascader-menu {
  height: 280px !important;
}

.el-cascader-menu__wrap.el-scrollbar__wrap {
  height: 110% !important;
}

/*  */

.el-input--suffix .el-input__inner {
  padding-left: 30px !important;
  padding-right: 15px !important;
}

.el-select .el-input__suffix,
.el-cascader .el-input__suffix {
  left: 5px;
  right: unset !important;
}

.input-price {
  padding-bottom: 10px;
}

.el-select-dropdown__item {
  font-size: 13px;
}

.vs-con-input-label.is-label-placeholder {
  margin-top: 2px !important;
}

.input-price .el-input {
  width: 125px !important;
}

.input-price input {
  text-align: center !important;
}

.filter-bar .col-md-3 {
  text-align: center !important;
}

.el-select-dropdown__item {
  text-align: right;
}

/* .vs-input-primary .vs-input--input:focus {
        border: 1px solid #29B6F6 !important;
    } */

.vs-input-primary .vs-input--input:focus ~ .icon-inputx,
.vs-input-primary .vs-input--input:focus ~ .vs-placeholder-label {
  color: #222222;
}

.vs-input--input.hasIcon {
  padding-left: 32px;
  padding-right: 10px;
  text-align: right;
  font-size: 12px !important;
}

.vs-input--placeholder {
  text-align: right;
  padding-right: 10px;
}

.vs-con-input-label {
  width: auto !important;
}

.vs-input--input:focus + .vs-placeholder-label {
  /* transition: padding 200ms, 300ms transform 400; */
  transform: translate(-10px, -90%) !important;
}

.vs-input--input:focus + .vs-input--placeholder {
  top: 10px;
  width: fit-content;
  right: 0px;
  padding: 0px 7px !important;
}

.vs-input--input + .vs-input--placeholder::before {
  position: absolute;
  right: 0;
  top: 10px;
  content: "";
  height: 3px;
  width: 0px;
  background: transparent;
  opacity: 0;
  z-index: -1;
  transition: all 0.4s ease-in-out 0.04s;
}

.vs-input--input:focus + .vs-input--placeholder::before {
  width: 60px;
  background: #fff;
  opacity: 1;
}

.vs-input--input.hasValue + .vs-placeholder-label {
  padding-bottom: 1px !important;
}

.vs-input--input:focus {
  box-shadow: unset !important;
}

.vs-con-input .material-icons {
  font-size: 19px !important;
  /* background: #29B6F6; */
  padding: 5px 5px 5px 6px;
  border-radius: 10px;
  left: -10px;
  top: 4px;
  color: #fff !important;
  font-size: 17px;
  /* box-shadow: 0px 2px 10px -7px #000, 0px 4px 10px -5px #29B6F6; */
}

.vs-switch--text .material-icons {
  font-size: 0.8rem !important;
}

/* el-slider */

.el-slider__runway {
  background-color: rgba(0, 0, 0, 0.1);
}

.el-slider__bar {
  background-color: #29b6f6 !important;
}

.el-slider__button {
  border-color: #29b6f6 !important;
}

.black {
  color: #222222;
}

.el-select .el-input.is-focus .el-input__inner {
  border-color: #29b6f6;
}

.el-checkbox-button.is-checked .el-checkbox-button__inner {
  color: #fff;
  background-color: #409eff;
  border-color: #409eff;
  z-index: 4;
}
</style>

<style scoped>
.filter-bar div.col-md-2:nth-of-type(n + 9) {
  margin-top: 16px !important;
}

.profile-estate {
  padding: 30px 25px;
  border-radius: 20px;
}

.profile-estate.responsive {
  position: absolute;
  background: #fff;
  width: 70%;
  padding: 30px;
  border-radius: 20px;
  top: 130px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 99;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1) !important;
}

.profile-estate .username {
  font-weight: bold !important;
  font-size: 22px;
  color: #484848;
}

.as-px-4 {
  padding-right: 4rem !important;
  padding-left: 2.4rem !important;
}

.as-main-btn {
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0;
  position: absolute;
  top: 60px;
  z-index: 400000;
  left: -30px;
  box-shadow: 0px -2px 20px -13px #000 !important;
  background: #29b6f6;
  border-color: #29b6f6;
  width: 65px;
  height: 60px;
  border-radius: 18px;
  cursor: pointer;
  transition: all 300ms linear 0s;
  -webkit-transition: all 300ms linear 0s;
  -o-transition: all 300ms linear 0s;
}

.as-main-btn.remove-filters {
  width: 45px;
  height: 45px;
  left: unset !important;
  right: -20px !important;
  background: #e91e63;
  border-color: #e91e63;
}

.as-main-btn.remove-filters i {
  font-size: 20px !important;
}

.remove-filters.hvr-ripple-out:before {
  border-color: #e91e63 !important;
}

.as-main-btn i {
  transition: all 500ms;
}

.as-main-btn:hover i {
  transform: rotateY(180deg);
}

.remove-filters.as-main-btn:hover i {
  transform: rotate(-20deg);
}

.hvr-ripple-out:before {
  border-color: #29b6f6 !important;
  border-radius: 22px;
}

.filter-bar {
  background: #ffffff;
  border-radius: 20px;
  position: relative;
  z-index: 1;
  box-shadow: 0px 10px 50px -35px;
  transform: translateY(-90px);
}
</style>
