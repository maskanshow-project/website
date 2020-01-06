<template>
  <section>
    <div class="container">
      <div class="sec-title mb-xs-5" :class="{ 'justify-content-end' : Res }">
        <div class="d-flex">
          <div class="d-flex align-items-center grid-list-btn" v-if="!Res">
            <el-radio-group v-model="grid_list">
              <el-radio-button :label="true">
                <v-img src="/img/gird.png" height="20px" width="20px"></v-img>
              </el-radio-button>
              <el-radio-button :label="false">
                <v-img src="/img/list.png" height="20px" width="20px"></v-img>
              </el-radio-button>
            </el-radio-group>
          </div>

          <slot name="sort"></slot>
        </div>

        <div>
          <span class="estates-title web-color">... خونه جدیدت رو پیدا کن</span>
          <h2 :class="{ 'fs-20' : Res }">{{ title || 'ملک های مشهد' }}</h2>
        </div>
      </div>

      <div class="filter-btn row justify-content-around" v-show="Res">
        <div class="col-5" @click="Set_state({ prop : 'dialog_sort' , val : true })">
          <span>
            مرتب سازی
            <i class="flaticon-stats"></i>
          </span>
        </div>
        <div class="col-5" @click="Set_state({ prop : 'dialog_filters' , val : true })">
          <span>
            فیلتر ها
            <i class="flaticon-settings"></i>
          </span>
        </div>
      </div>

      <template v-if="is_exist(Estates)">
        <transition name="fade" mode="in-out">
          <!-- Estates Grid -->
          <div class="row rtl" v-show="grid_list || Res">
            <div class="col-lg-4 col-md-6 ltr" v-for="(estate,index) in Estates" :key="index">
              <Estate-Grid :estate="estate"></Estate-Grid>
            </div>
          </div>
        </transition>

        <transition name="fade" mode="in-out">
          <!-- Estates List -->
          <div class="row list-estate" v-show="!grid_list && !Res">
            <div class="col-lg-12" v-for="(estate,index) in Estates" :key="index">
              <Estate-List :estate="estate"></Estate-List>
              <div v-if="index % 4 == 3 && index != Estates.length - 1">
                <a :href="$store.state.siteSetting.ads[(index - 3) / 4].link">
                  <v-img
                    v-if="$store.state.siteSetting.ads[(index - 3) / 4].image"
                    :src="$store.state.siteSetting.ads[(index - 3) / 4].image.large"
                    max-height="300"
                    class="w-100 mb-4 shadow rounded"
                  ></v-img>
                </a>
              </div>
            </div>
          </div>
        </transition>
      </template>

      <template v-else>
        <div class="rtl p-5 pt-0 d-flex flex-column justify-content-center align-items-center">
          <i class="lnr lnr-magnifier fs-100"></i>
          <h3 class="mt-4 text-center">متاسفانه ملکی پیدا نشد :(</h3>
          <h5 class="mt-3 text-center">
            برای دیدن ملک های بیشتر
            <a
              :href=" $route.params.username && this.is_exist($route.query)
                        ? $router.resolve({ path: '/'+$route.params.username }).href
                        : $router.resolve({path: '/properties'}).href "
            >اینجا</a>
            کلیک کنید
          </h5>
        </div>
      </template>
    </div>
  </section>
</template>

<script>
import { VImg } from "vuetify/lib";
import { RadioGroup, RadioButton } from "element-ui";
import { mapState, mapMutations } from "vuex";
import mixin from "../../../mixin";
import EstateGrid from "../../Layout/EstateGrid.vue";
import EstateList from "../../Layout/EstateList.vue";

export default {
  props: ["title"],
  mixins: [mixin],

  components: {
    EstateList,
    EstateGrid,
    elRadioGroup: RadioGroup,
    elRadioButton: RadioButton,
    VImg
  },

  data() {
    return {
      grid_list: false
    };
  },

  computed: {
    ...mapState(["Estates", "url"])
  },

  methods: {
    ...mapMutations(["Set_state"])
  }
};
</script>

<style>
.fs-100 {
  font-size: 100px;
}

.pt-xs-85 {
  padding-top: 85px !important;
}

.grid-list-btn .v-image__image {
  border-radius: 0px !important;
}

.grid-list-btn .el-radio-button__inner {
  padding: 8.5px 15px !important;
}

.sort-btn .el-radio-button__inner {
  padding: 13px 15px !important;
}

.sort-btn .el-radio-button__orig-radio:checked + .el-radio-button__inner {
  color: #222222e0 !important;
}

/* .grid-list-btn .el-radio-button__orig-radio:checked+.el-radio-button__inner {
        background-color: #29b6f6 !important;
        border-color: #29b6f6 !important;
        box-shadow: 1px 3px 8px -4px #409EFF, 0px 2px 6px -6px #000 !important;
    } */

.sort-btn .el-radio-button__orig-radio:checked + .el-radio-button__inner {
  background-color: #29b6f6 !important;
  border-color: #29b6f6 !important;
  box-shadow: 1px 3px 8px -4px #409eff, 0px 2px 6px -6px #000 !important;
}

.filter-btn {
  text-align: center;
  padding: 12px 0;
  margin: 0px 0px 40px;
}

.filter-btn div {
  background: #fff;
  cursor: pointer;
  padding: 1rem;
  box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.1) !important;
  border-radius: 7px;
}

.filter-btn span {
  color: #484848;
  font-size: 1em;
  font-weight: 500;
  display: flex;
  align-items: center;
  justify-content: center;
}

.filter-btn span i {
  margin-left: 10px;
  font-size: 20px;
}

.vs-pagination--nav,
.vs-pagination--ul {
  align-items: end;
}

.mb-7 {
  margin-bottom: 5rem !important;
}

.fs-13 {
  font-size: 13px;
}

.fs-15 {
  font-size: 15px;
}

.el-rate__icon {
  margin: 0px !important;
}

.el-rate__text {
  color: #6c757d !important;
  margin-right: 8px;
  font-size: 11px;
  vertical-align: middle;
}
</style>