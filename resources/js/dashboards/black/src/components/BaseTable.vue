<template>
  <transition name="fade">
    <div v-show="has_loaded">

      <div class="row" v-if="false">
        <transition name="fade">
          <div class="col-12 pt-3 animated bounceInLeft delay-last filters-row" v-show="has_loaded" dir="rtl">
            <slot name="filter-labels"></slot>
          </div>
        </transition>
      </div>

      <transition name="fade">
        <div class="row">
          <div class="col-12 data-table animated bounceInUp delay-last" dir="rtl" v-show="!is_grid_view">
            <ul class="data-table-header p-2 d-flex justify-content-center">
              <li
                class="data-table-cell counter-cell p-2 d-flex align-items-center justify-content-center text-muted"
                :style="{ minWidth: '35px', maxWidth: '35px' }"
              >
                <transition name="fade" mode="out-in">
                  <span v-if="attr('selected_items').length === 0">#</span>
                  <ICountUp
                    v-if="attr('selected_items').length !== 0"
                    :endVal="attr('selected_items').length"
                    :options="{
                      useEasing: true,
                      useGrouping: true,
                    }"
                  />
                </transition>
              </li>
              <li class="data-table-cell checkbox-cell p-2 d-flex align-items-center justify-content-center" :style="{width: '50px'}" v-if="canSelect">
                <checkbox>
                  <input type="checkbox" v-model="all_items_selected" @change="$parent.handleSelectAll" />
                </checkbox>
              </li>
              <li v-for="(field, index) in fields" :key="index" class="data-table-cell p-2 d-flex align-items-center justify-content-center text-muted hvr-icon-up">
                <slot :name="field.field + '-header'">
                  <i v-if="field.icon !== undefined" :class="['tim-icons', field.icon,  'hvr-icon']"></i>
                  {{ field.label }}
                </slot>
              </li>
              <!-- <li class="data-table-cell p-2 d-flex align-items-center justify-content-center text-muted hvr-icon-up" v-if="has_times">
                <i class="tim-icons icon-time-alarm hvr-icon"></i>
                ثبت / ویرایش
              </li> -->
              <!-- <li class="data-table-cell p-2 d-flex align-items-center justify-content-center text-muted hvr-icon-spin" v-if="has_operation">
                <i class="tim-icons icon-settings hvr-icon"></i>
                عملیات ها
              </li> -->
            </ul>

            <transition name="fade">
              <transition-group
                name="flip-list"
                v-show="tableData.length !== 0"
                @enter="enter"
                @leave="leave"
                @after-enter="afterEnter"
                tag="ul"
              >
                <li
                  v-for="(item, index) in tableData"
                  :key="item.id"
                  class="data-table-row"
                  :style="{ transform: `scale(${has_animation ? 0 : 1})`, animationDelay: is_finished ? '0ms' : `${1000 + index * 50}ms`}"
                >
                  <ul class="p-2 d-flex justify-content-center">
                    <span class="cavity-effect"></span>
                    <li class="data-table-cell counter-cell p-2 d-flex align-items-center justify-content-center" :style="{ minWidth: '35px', maxWidth: '35px' }">
                      {{ index + 1 }}
                    </li>
                    <li :style="{width: '50px'}" class="data-table-cell checkbox-cell d-flex align-items-center justify-content-center" v-if="canSelect">
                      <checkbox>
                        <input type="checkbox" :value="index" v-model="$parent.selected_items" @change="$parent.handleSelectedChange(index)" />
                      </checkbox>
                    </li>
                    <li v-for="(field, childIndex) in fields" :key="childIndex" class="data-table-cell p-2 d-flex align-items-center justify-content-center" >
                      <slot :name="field.field + '-body'" :row="item" :index="index">
                        <el-popover
                          placement="top-end"
                          width="300"
                          trigger="hover"
                          :disabled="typeof item[ field.field ] === 'string' ? item[ field.field ].length <= 50 : false"
                          :content="item[ field.field ]"
                        >
                          <span slot="reference">{{ item[ field.field ] | truncate(50) }}</span>
                        </el-popover>
                      </slot>
                    </li>
                  </ul>

                  <span class="operation-cell" v-if="has_operation && !ignoreOperations.includes(item.id)">
                    <el-tooltip :content="'ویرایش ' + label" placement="right">
                      <base-button
                        v-if="canEdit"
                        @click="methods.edit(index, item)"
                        class="hvr-icon-spin"
                        :disabled="abilities ? !abilities.edit : can(`update-${type}`)"
                        :type="abilities ? !abilities.edit ? 'default' : 'warning' : can(`update-${type}`) ? 'default' : 'warning'"
                        size="sm"
                        icon
                      >
                        <i class="tim-icons icon-pencil hvr-icon" :style="{ fontSize: '18px !important' }"></i>
                      </base-button>
                    </el-tooltip>

                    <el-tooltip :content="'حذف ' + label" placement="right">
                      <base-button 
                        v-if="canDelete"
                        @click="methods.deleteSingle ?  methods.deleteSingle(index, item) : handleDelete(index, item)"
                        :disabled="abilities ? !abilities.delete : can(`delete-${type}`)"
                        :type="abilities ? !abilities.delete ? 'default' : 'danger' : can(`delete-${type}`) ? 'default' : 'danger'"
                        size="sm"
                        icon
                      >
                        <i class="tim-icons icon-trash-simple" :style="{ fontSize: '18px !important' }"></i>
                      </base-button>
                    </el-tooltip>

                    <slot
                      name="custom-operations"
                      :row="item"
                      :index="index"
                    />
                  </span>

                  <span class="time-lables" :style="{ fontSize: '10px' }" v-if="has_times">
                    <el-tooltip class="created-at-label" :content="item.created_at | created" placement="left">
                      <p><i class="tim-icons icon-check-2"></i> {{ item.created_at | ago }}</p>
                    </el-tooltip>

                    <el-tooltip
                      class="updated-at-label"
                      :content="item.updated_at | edited" placement="left"
                      v-if="item.updated_at !== item.created_at">
                      <p><i class="tim-icons icon-pencil"></i> {{ item.updated_at | ago }}</p>
                    </el-tooltip>
                  </span>

                  <span class="shadow-first"></span>
                </li>
              </transition-group>
            </transition>
              
            <transition name="fade">
              <md-empty-state
                v-show="tableData.length === 0"
                :md-icon="emptyState && emptyState.icon ? emptyState.icon : 'search'"
                :md-label="emptyState && emptyState.title ? emptyState.title : 'متاسفانه هیچ داده ای یافت نشد :('"
                :md-description="emptyState && emptyState.description ? emptyState.description : 'اگر در حالت جستجو نیستید و هیچ فیلتری نیز اعمال نکرده اید ، میتوانید با کلیک بر روی دکمه زیر یک داده جدید ثبت کنید'">
                <base-button v-if="methods.create && canCreate" @click="methods.create()" :disabled="can(`create-${type}`)" size="sm" :type="can(`create-${type}`) ? 'default' : 'success'" class="pull-left">
                  <i class="tim-icons icon-pencil"></i>
                  افزودن {{ label }} جدید
                </base-button>
              </md-empty-state>
            </transition>
          </div>

          <transition-group
            class="col-12 data-grid"
            tag="div"
            v-show="is_grid_view"
            v-if="false"
          >
            <div
              class="col-md-4 pull-right"
              v-for="(item, index) in tableData"
              :key="item.id"
            >
              <card
                class="col-md-12 data-grid-item animated bounceInUp"
                :class="!item.description ? 'card-empty' : ''"
                :showFooterLine="true"
                :title="item.name"
                :subTitle="item.description ? item.description : `متاسفانه توضیحی برای این ${label} ثبت نشده است :(`"
              >
                <img  slot="image"
                      class="card-img-top tilt"
                      :src="item.logo ? item.logo.tiny : '/images/placeholder.png'"
                      alt="Card image cap" />
                
                <div class="operation-cell">
                  <slot name="edit-button" :row="item" :index="index"></slot>
                
                  <el-tooltip :content="'حذف ' + label">
                    <base-button v-if="canDelete" @click="handleDelete(index, item)" type="danger" size="sm" icon>
                      <i class="tim-icons icon-simple-remove"></i>
                    </base-button>
                  </el-tooltip>
                </div>

                <span
                  v-if="item.categories.length === 0"
                  class="badge badge-danger hvr-grow-shadow hvr-icon-grow">
                  <i class="tim-icons icon-bullet-list-67 hvr-icon"></i>
                  بدون دسته بندی !
                </span>
                <slot name="categories-body" :row="item"></slot>
                
                <template slot="footer">
                  <hr/>
                  <slot name="time-body" :row="item"></slot>
                </template>
              </card>
            </div>
          </transition-group>
        </div>
      </transition>
    </div>
  </transition>
</template>

<script>
  import deleteMixin from '../mixins/deleteMixin'
  import createMixin from '../mixins/createMixin'
  import FiltersHelper from '../mixins/filtersHelper'

  import {Tooltip} from 'element-ui'
  import ICountUp from 'vue-countup-v2';
  import {BaseAlert} from '../../src/components'
  import Checkbox from './Checkbox.vue'
  import { FlipClock } from '@mvpleung/flipclock';

  import 'hover.css'
  import 'animate.css'
  import anime from 'animejs'

  export default {
    props: {
      tableData: {
        type: Array,
        required: true
      },
      type: {
        type: String,
        required: true
      },
      group: {
        type: String,
        required: true
      },
      fields: {
        type: Array,
        required: true
      },
      has_loaded: {
        type: Boolean,
        required: true,
      },
      has_animation: {
        type: Boolean,
        default: () => true
      },
      is_grid_view: {
        type: Boolean,
        default: () => false
      },
      has_times: {
        type: Boolean,
        default: () => false
      },
      has_operation: {
        type: Boolean,
        default: () => false
      },
      methods: {
        type: Object,
        required: true
      },
      label: {
        type: String,
        required: true
      },
      canEdit: {
        type: Boolean,
        default: () => true
      },
      canCreate: {
        type: Boolean,
        default: () => true
      },
      canSelect: {
        type: Boolean,
        default: () => true
      },
      canDelete: {
        type: Boolean,
        default: () => true
      },
      emptyState: {
        type: Object,
        default: () => {}
      },
      abilities: {
        type: Object,
        default: () => {}
      },
      ignoreOperations: {
        type: Array,
        default: () => []
      }
    },
    components: {
      Tooltip,
      ICountUp,
      Checkbox,
    },
    mixins: [
        deleteMixin,
        createMixin,
        FiltersHelper
    ],
    data() {
      return {
        layout: this.is_grid_view,
        
        entered_count: 0,
        is_finished: false,
      }
    },
    computed: {
      all_items_selected: {
        get () {
          return this.tableData.length === this.attr('selected_items').length
        },
        set (value) { }
      },
    },
    methods: {
      enter(el, done)
      {
        el.style.marginTop = `-${el.offsetHeight + 15}px`

        anime({
          targets: el,
          marginTop: {
            value: 0,
          },
          marginBottom: {
            value: 20,
          },
          scale: {
            value: 1,
            delay: 150
          },
          round: 2,
          easing: 'easeOutExpo',
          complete() {
            done()
          }
        })
      },
      leave(el, done) {
        anime({
          targets: el,
          marginTop: {
            value: -(el.offsetHeight + 15),
            delay: 150
          },
          scale: {
            value: 0
          },
          round: 2,
          duration: 500,
          easing: 'easeOutCubic',
          complete() {
            done()
          }
        })
      },
      afterEnter(el) {
        el.style.animationDelay = 'unset'

        if ( ++this.entered_count === this.tableData.length )
          this.is_finished = true
      },

      changeLayout(value) {
        if ( value ) 
          $('.data-table').removeClass(['animated', 'bounceInUp', 'delay-last']).addClass(['animated', 'bounceOutDown'])
        else
          $('.data-grid').removeClass(['animated', 'bounceInUp', 'delay-last']).addClass(['animated', 'bounceOutDown'])

        setTimeout( () => {
          this.is_grid_view = value

          if ( value ) 
            $('.data-table').removeClass(['animated', 'bounceOutDown']).addClass(['animated', 'bounceInUp', 'delay-last'])
          else
            $('.data-grid').removeClass(['animated', 'bounceOutDown']).addClass(['animated', 'bounceInUp', 'delay-last'])
        }, 800)
      },
    
      setAttr(attr, data, override = false) {
        let final_data = typeof data === 'object' ? {...this.$store.state[this.group][attr][this.type], ...data} : data;

        if ( override ) final_data = data

        this.$store.commit('setAttr', {
          attr,
          group: this.group,
          type: this.type,
          data: final_data
        })
      },
      attr(attr) {
          return this.$store.state[this.group][attr][this.type]
      },
      filter(filter) {
          return this.$store.state[this.group].filters[this.type][filter]
      },
      selected(field) {
          return this.$store.state[this.group].selected[this.type][field]
      },
      can(permission) {
        return !this.$store.state.permissions.includes(permission)
      }
    },
  }
</script>

<style>
  
  .el-popover {
    direction: rtl;
    text-align: right;
  }

  .flip-list-move {
    transition: transform 500ms !important;
  }

  .btn-danger {
    box-shadow: 0px 5px 10px -4px #ff3d3d, 0px 4px 6px -5px #000 !important;
  }
  .btn-warning {
    box-shadow: 0px 5px 10px -4px #ff8d72, 0px 4px 6px -5px #000 !important;
  }
  .btn-success {
    box-shadow: 0px 5px 10px -4px #007bff, 0px 4px 6px -5px #000 !important;
  }

  .data-table .operation-cell {
    position: absolute;
    top: 7PX;
    left: -10px;
  }
  .data-table .btn-icon.btn-warning,
  .data-table .btn-icon.btn-danger,
  .data-table .btn-icon.btn-default {
    display: block;
  }
  .data-table-cell .tim-icons.icon-trash-simple, .data-table-cell .tim-icons.icon-pencil {
    color: #fff !important;
  }

  .fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
  }
  .fade-enter, .fade-leave-to {
    opacity: 0;
  }

  .list-enter-active, .list-leave-active {
    transition: margin-top 800ms !important;
  }
  .list-enter, .list-leave-to {
    margin-top: -50px;
  }
  
  .filters-row {
    min-height: 42px !important;
  }

  .tim-icons {
    margin-left: 5px;
  }

  .badge-default {
    color: #344675 !important;
    background: transparent !important;
    border: 1px solid #344675 !important;
  }

  .remove-button {
    cursor: pointer;
  }

  .el-dropdown-menu {
    text-align: right;
  }
  .el-dropdown-menu i {
    margin-right: 0px;
    margin-left: 5px;
  }

  .data-table .cavity-effect {
    width: 30px;
    height: 80%;
    background: #eff6ff;
    position: absolute;
    right: 10px;
    top: 10%;
    border-radius: 5px 15px 15px 5px;
    box-shadow: 0px 6px 12px -14px #19375a inset, 0px 5px 11px -15px #0076ff inset;
  }

  .data-table {
    perspective: 600px;
    position: relative;
    z-index: 1000;
    transform-origin: top center; 
    transform-style: preserve-3d;
    background: transparent;
    margin-bottom: 20px;
    text-align: center;
  }
  
  .data-table-cell {
    float: right;
    width: 100%;
    padding: 10px;
    z-index: 50;
    -ms-word-break: break-word;
    word-break: break-word;
  }

  .operation-cell i {
    margin: 0px;
  }

  .data-table-header {
    text-shadow: 0px 4px 18px #999;
  }

  .data-table-row, .data-table-header {
    width: 100%;
    float: right;
    overflow: hidden;
    margin-bottom: 15px;
  }
  .time-lables {
    position: absolute;
    left: 50px;
    top: 5px;
    z-index: -1;
    transition: top 200ms;
  }
  .data-table-row:hover .time-lables {
    top: -15px;
  }
  .created-at-label {
    background: #007bff;
    box-shadow: 0px 4px 12px -15px #007bff, 0px 3px 11px -16px #000;
    border-radius: 10px;
    padding: 2px 7px 10px;
    font-size: 8px;
    color: #fff !important;
    font-weight: bold;
    float: left;
    z-index: -2;
  }
  .updated-at-label {
    background: #fd7e14;
    box-shadow: 0px 4px 12px -15px #fd7e14, 0px 3px 11px -16px #000;
    border-radius: 10px;
    padding: 2px 7px 10px;
    font-size: 8px;
    float: left;
    margin-left: 10px;
    color: #fff !important;
    font-weight: bold;
  }
  .created-at-label *, .updated-at-label * {
    color: #fff !important;
  }
  .data-table-row {
    position: relative;
    border-radius: 10px 10px 10px 60px;
    transform: scale(1);
    z-index: 1000;
    overflow: visible !important;
    transition: transform 200ms, box-shadow 250ms;
  }
  .data-table-row::after {
    content: '';
    position: absolute;
    bottom: 0px;
    left: 2%;
    width: 96%;
    height: 100%;
    background: rgba(255, 255, 255, 0.5);
    z-index: -2;
    box-shadow: 0px 0px 12px -14px #19375a, 0px 0px 11px -15px #0076ff;
    border-radius: 20px;
    transform: rotate(0.8deg);
  }
  .data-table-row::before {
    content: '';
    position: absolute;
    bottom: 0px;
    left: 4%;
    width: 92%;
    box-shadow: 0px 0px 12px -14px #19375a, 0px 0px 11px -15px #0076ff;
    height: 100%;
    background: rgba(255, 255, 255, 0.3);
    z-index: -3;
    border-radius: 20px;
    transform: rotate(1.2deg);
  }
  .data-table-row ul {
    background: #fff;
    box-shadow: 0px 7px 12px -15px #19375a, 0px 5px 11px -16px #0076ff;
    position: relative;
    border-radius: 20px;
    min-height: 74px;
    overflow: hidden !important;
  }
  .data-table-row ul::before {
    z-index: -1;
    content: '';
    display: none;
    position: absolute;
    top: -280px;
    left: -110px;
    width: 300px;
    height: 300px;
    background: linear-gradient(to left, #fd5d9330, transparent);
    border-radius: 50%;
  }

  .data-table-row ul li:first-of-type {
    text-shadow: 0px 10px 37px #440139;
  }
  
  .data-table-row:hover ul {
    transform: scale(1.003) !important;
    box-shadow: 0px 7px 14px -14px #19375a, 0px 5px 12px -15px #0076ff;
  }

  .data-table-row td {
    padding: 8px;
  }
  .data-table-row td:first-child {
    border-radius: 0px 6px 6px 0px !important;
  }

  .data-table-row td:last-child {
    border-radius: 6px 0px 0px 6px !important;
  }
  
  .data-table img {
    max-height: 50px;
    filter: drop-shadow(0px 4px 10px #ddd) !important;
  }

  button.swal2-confirm {
    background: linear-gradient(to bottom left, #00f2c3, #0098f0) !important;
  }

  button.swal2-cancel {
    background: linear-gradient(to bottom left, #fd5d93, #ec250d) !important;
  }

  .el-checkbox__inner {
    margin-top: 10px;
  }

  /* Responsive table */
  @media (max-width: 700px) {
    .data-table .data-table-header, .data-table .data-table-row ul {
      flex-direction: column;
    }
    .data-table .counter-cell, .data-table .checkbox-cell, .data-table .cavity-effect {
      display: none !important;
    }

    .data-table .data-table-header .data-table-cell {
      justify-content: flex-start !important;
    }
  }
</style>