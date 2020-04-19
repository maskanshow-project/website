<template>
  <div :style="{ position: 'relative', zIndex: 10 }" v-if="is_loaded">
    <div class="row mb-4">
      <div class="col-12 text-right">
        <div class="pull-right">
          <h1
            class="animated bounceInRight delay-first"
            :style="{ color: '#fff', fontWeight: 'bold', textShadow: '0px 3px 15px #333' }"
          >
            گزارشات
            <span :style="{ color: '#ff3d3d' }">ربات</span> ثبت ملک
            <i
              class="header-nav-icon tim-icons icon-align-left-2"
              :style="{fontSize: '25px'}"
            ></i>
          </h1>
          <h6
            class="header-description animated bounceInRight delay-secound"
          >در این بخش کلیه آمارها و آخرین لینک های بررسی شده توسط ربات را میتوانید مشاهده نمایید</h6>
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

    <div class="row" dir="rtl">
      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInRight delay-secound">
          <i
            class="tim-icons dashboard-header-cards-icon icon-bank"
            :style="{ background: '#ff8f8f', color: '#ff3d3d' }"
          ></i>
          <h5 class="card-category" :style="{ color: '#ff3d3d' }">تعداد لینک بررسی شده</h5>
          <h3 class="card-title">
            <ICountUp
              :endVal="info.crawled_count"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' لینک'
              }"
            />
          </h3>
          <p
            class="card-text text-muted"
            :style="{fontSize: '10px'}"
          >تعداد ملک هایی که تا کنون بازدید کرده اید</p>
        </card>
      </div>

      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInRight delay-last">
          <i
            class="tim-icons dashboard-header-cards-icon icon-chart-bar-32"
            :style="{ background: '#85ffdb', color: '#20c997' }"
          ></i>
          <h5 class="card-category" :style="{ color: '#20c997' }">تعداد آگهی ثبت شده</h5>
          <h3 class="card-title">
            <ICountUp
              :endVal="info.registered_count"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' آگهی'
              }"
            />
          </h3>
          <p
            class="card-text text-muted"
            :style="{fontSize: '10px'}"
          >تعداد آگهی هایی که تا کنون ثبت کرده اید</p>
        </card>
      </div>

      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInLeft delay-secound">
          <i
            class="tim-icons dashboard-header-cards-icon icon-bulb-63"
            :style="{ background: '#fbc190', color: '#fd7e14' }"
          ></i>
          <h5 class="card-category" :style="{ color: '#fd7e14' }">تعداد آگهی های ثبت نشده</h5>
          <h3 class="card-title">
            <ICountUp
              :endVal="info.invalid_count"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' آگهی'
              }"
            />
          </h3>
          <p
            class="card-text text-muted"
            :style="{fontSize: '10px'}"
          >تعداد ملک هایی که میتوانید بازدید کنید</p>
        </card>
      </div>

      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInLeft delay-last">
          <i
            class="tim-icons dashboard-header-cards-icon icon-pencil"
            :style="{ background: '#7cb6f5', color: '#007bff' }"
          ></i>
          <h5 class="card-category" :style="{ color: '#007bff' }">بازدهی آگهی های معتبر</h5>
          <h3 class="card-title">
            <ICountUp
              :endVal="Math.round(info.registered_count * 100 / info.crawled_count)"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' %'
              }"
            />
          </h3>
          <p
            class="card-text text-muted"
            :style="{fontSize: '10px'}"
          >تعداد آگهی که میتوانید ثبت کنید</p>
        </card>
      </div>

      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInRight delay-secound">
          <i
            class="tim-icons dashboard-header-cards-icon icon-bank"
            :style="{ background: '#f5b4f1', color: '#cc04c1' }"
          ></i>
          <h5 class="card-category" :style="{ color: '#cc04c1' }">تعداد لینک بررسی شده این هفته</h5>
          <h3 class="card-title">
            <ICountUp
              :endVal="info.crawled_last_week"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' لینک'
              }"
            />
          </h3>
          <p
            class="card-text text-muted"
            :style="{fontSize: '10px'}"
          >تعداد ملک هایی که تا کنون بازدید کرده اید</p>
        </card>
      </div>

      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInRight delay-last">
          <i
            class="tim-icons dashboard-header-cards-icon icon-chart-bar-32"
            :style="{ background: '#bdc7ff', color: '#3f51b5' }"
          ></i>
          <h5 class="card-category" :style="{ color: '#3f51b5' }">تعداد لینک بررسی شده امروز</h5>
          <h3 class="card-title">
            <ICountUp
              :endVal="info.crawled_today"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' لینک'
              }"
            />
          </h3>
          <p
            class="card-text text-muted"
            :style="{fontSize: '10px'}"
          >تعداد آگهی هایی که تا کنون ثبت کرده اید</p>
        </card>
      </div>

      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInLeft delay-secound">
          <i
            class="tim-icons dashboard-header-cards-icon icon-bulb-63"
            :style="{ background: '#d4a999', color: '#795548' }"
          ></i>
          <h5 class="card-category" :style="{ color: '#795548' }">تعداد آگهی ثبت شده امروز</h5>
          <h3 class="card-title">
            <ICountUp
              :endVal="info.registered_today"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' آگهی'
              }"
            />
          </h3>
          <p
            class="card-text text-muted"
            :style="{fontSize: '10px'}"
          >تعداد ملک هایی که میتوانید بازدید کنید</p>
        </card>
      </div>

      <div class="col-md-3">
        <card class="text-right mb-4 animated bounceInLeft delay-last">
          <i
            class="tim-icons dashboard-header-cards-icon icon-pencil"
            :style="{ background: 'rgb(255, 204, 188)', color: '#FF5722' }"
          ></i>
          <h5 class="card-category" :style="{ color: '#FF5722' }">تعداد آگهی ثبت نشده امروز</h5>
          <h3 class="card-title">
            <ICountUp
              :endVal="info.crawled_today - info.registered_today"
              :options="{
                useEasing: true,
                useGrouping: true,
                separator: ',',
                suffix: ' آگهی'
              }"
            />
          </h3>
          <p
            class="card-text text-muted"
            :style="{fontSize: '10px'}"
          >تعداد آگهی که میتوانید ثبت کنید</p>
        </card>
      </div>
    </div>

    <div class="row mt-4" dir="rtl">
      <div class="col-12 text-right animated bounceInUp delay-last">
        <h3 class="font-weight-bold mb-2">لینک های دارای مشکل</h3>
        <el-table :data="info.invalid" class="w-100 rounded shadow mb-4">
          <el-table-column prop="provider" label="منبع" width="100">
            <template slot-scope="scope">
              <p v-if="scope.row.provider == 'MelkeIrani'">ملک ایرانی</p>
              <p v-if="scope.row.provider == 'MaskanFile'">مسکن فایل</p>
            </template>
          </el-table-column>
          <el-table-column prop="crawled_at" label="تاریخ بررسی">
            <template slot-scope="scope">
              <p dir="ltr">
                <span class="text-muted" style="font-size: 10px">({{ scope.row.crawled_at | ago }})</span>
                {{ scope.row.crawled_at | jalaali }}
              </p>
            </template>
          </el-table-column>
          <el-table-column prop="crawled_at" label="مشکلات">
            <template slot-scope="scope">
              <ul style="font-size: 12px;">
                <li v-for="(item, index) in scope.row.errors" :key="index" class="text-danger">
                  <i class="tim-icons icon-alert-circle-exc"></i>
                  {{ item }}
                </li>
              </ul>
            </template>
          </el-table-column>
          <el-table-column prop="link" label="لینک" width="180">
            <template slot-scope="scope">
              <a :href="scope.row.link" target="_blank">
                <el-button type="primary" size="mini" plain>مشاهده آگهی اصلی</el-button>
              </a>
            </template>
          </el-table-column>
        </el-table>

        <h3 class="font-weight-bold mb-2 animated bounceInUp delay-last">ملک های ثبت شده</h3>
        <el-table :data="info.registered" class="w-100 rounded shadow">
          <el-table-column prop="provider" label="منبع" width="100">
            <template slot-scope="scope">
              <p v-if="scope.row.provider == 'MelkeIrani'">ملک ایرانی</p>
              <p v-if="scope.row.provider == 'MaskanFile'">مسکن فایل</p>
            </template>
          </el-table-column>
          <el-table-column prop="crawled_at" label="تاریخ بررسی">
            <template slot-scope="scope">
              <p dir="ltr">
                <span class="text-muted" style="font-size: 10px">({{ scope.row.crawled_at | ago }})</span>
                {{ scope.row.crawled_at | jalaali }}
              </p>
            </template>
          </el-table-column>
          <el-table-column prop="estate" label="ملک">
            <template slot-scope="scope">
              <p>
                <span class="text-danger font-weight-bold">#{{ scope.row.estate.id }}</span>
                <span v-if="scope.row.estate.assignment">{{ scope.row.estate.assignment.title }}</span>
                <span v-if="scope.row.estate.estate_type">{{ scope.row.estate.estate_type.title }}</span>
                <span>{{ scope.row.estate.area }} متری</span>
              </p>
              <p class="text-muted" style="font-size: 12px">{{ scope.row.estate.address }}</p>
            </template>
          </el-table-column>
          <el-table-column prop="landlord" label="اطلاعات مالک">
            <template slot-scope="scope">
              <p>{{ scope.row.estate.landlord_fullname }}</p>
              <p>
                <a
                  :href="`tel:${scope.row.estate.landlord_phone_number}`"
                >{{ scope.row.estate.landlord_phone_number }}</a>
              </p>
            </template>
          </el-table-column>
          <el-table-column prop="link" label="لینک ها" width="180">
            <template slot-scope="scope">
              <a :href="scope.row.link" target="_blank">
                <el-button type="primary" size="mini" plain>مشاهده آگهی اصلی</el-button>
              </a>
              <div class="mb-2"></div>
              <a :href="`/estate/${scope.row.estate.id}`" target="_blank">
                <el-button type="danger" size="mini" plain>مشاهده در مسکن شو</el-button>
              </a>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>
  </div>
</template>

<script>
import ICountUp from "vue-countup-v2";
import { FlipClock } from "@mvpleung/flipclock";
import filtersHelper from "../../mixins/filtersHelper";

export default {
  components: {
    ICountUp,
    FlipClock
  },

  mixins: [filtersHelper],

  data() {
    return {
      is_loaded: false,
      info: {
        crawled_count: 0,
        registered_count: 0,
        invalid_count: 0,
        crawled_last_week: 0,
        crawled_today: 0,
        registered_today: 0,
        registered: [],
        invalid: []
      }
    };
  },

  beforeCreate() {
    axios
      .post("/graphql/auth", {
        query: `{
          crawledResult {
            crawled_count
            registered_count
            invalid_count
            crawled_last_week
            crawled_today
            registered_today
            registered {
              id
              link
              provider
              crawled_at
              estate  {
                id
                address
                area
                landlord_fullname
                landlord_phone_number
                assignment { id title }
                estate_type { id title }
              }
            }
            invalid {
              id
              link
              provider
              crawled_at
              errors
            }
          }
        }`
      })
      .then(({ data }) => {
        this.info = data.data.crawledResult;
        this.is_loaded = true;
      });
  }
};
</script>

<style>
.el-table th,
.el-table td {
  text-align: right;
}

.el-table th {
  background: #21446a;
  color: #fff;
}
</style>
