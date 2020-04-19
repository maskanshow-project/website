<template>
  <div :style="{ position: 'relative', zIndex: 10 }">
    <div class="row mb-4">
      <div class="col-12 text-right">
        <div class="pull-right">
          <h1
            class="animated bounceInRight delay-first"
            :style="{ color: '#fff', fontWeight: 'bold', textShadow: '0px 3px 15px #333' }"
          >
            تنظیمات
            <span :style="{ color: '#ff3d3d' }">منابع</span> ربات
            <i class="header-nav-icon tim-icons icon-align-left-2" :style="{fontSize: '25px'}"></i>
          </h1>
          <h6
            class="header-description animated bounceInRight delay-secound"
          >در این قسمت تنظیمات منابع ربات شامل اطلاعات ورود به آن ثبت میشوند</h6>
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

    <div class="row">
      <div class="col-6">
        <card class="text-right animated bounceInLeft delay-last" dir="rtl">
          <h3 class="font-weight-bold mb-2">ملک ایرانی</h3>
          <div class="cotent">
            <md-field class="mb-4">
              <label>نام کاربری</label>
              <md-input v-model="MelkeIrani.username" :maxlength="50" />
              <span class="md-helper-text">نام کاربری جهت ورود به وبسایت را وارد بکنید</span>
            </md-field>

            <md-field class="mb-4">
              <label>کلمه عبور</label>
              <md-input type="password" v-model="MelkeIrani.password" :maxlength="100" />
              <span class="md-helper-text">نام کاربری جهت ورود به وبسایت را وارد بکنید</span>
            </md-field>

            <div class="pt-4 text-center">
              <base-button
                @click="updateInfo('MelkeIrani')"
                :loading="loading == 'MelkeIrani'"
                class="hvr-icon-spin"
                type="warning"
                size="sm"
              >
                <transition name="fade" mode="out-in">
                  <semipolar-spinner
                    :animation-duration="2000"
                    :size="17"
                    color="#fff"
                    v-if="loading == 'MelkeIrani'"
                  />
                  <span v-else class="pull-right ml-2">
                    <i class="tim-icons icon-pencil"></i>
                  </span>
                </transition>بروزرسانی اطلاعات
              </base-button>
            </div>
          </div>
        </card>
      </div>

      <div class="col-6">
        <card class="text-right animated bounceInRight delay-last" dir="rtl">
          <h3 class="font-weight-bold mb-2">مسکن فایل</h3>

          <div class="cotent">
            <md-field class="mb-4">
              <label>نام کاربری</label>
              <md-input v-model="MaskanFile.username" :maxlength="50" />
              <span class="md-helper-text">نام کاربری جهت ورود به وبسایت را وارد بکنید</span>
            </md-field>

            <md-field class="mb-4">
              <label>کلمه عبور</label>
              <md-input type="password" v-model="MaskanFile.password" :maxlength="100" />
              <span class="md-helper-text">نام کاربری جهت ورود به وبسایت را وارد بکنید</span>
            </md-field>

            <div class="pt-4 text-center">
              <base-button
                @click="updateInfo('MaskanFile')"
                :loading="loading == 'MaskanFile'"
                class="hvr-icon-spin"
                type="warning"
                size="sm"
              >
                <transition name="fade" mode="out-in">
                  <semipolar-spinner
                    :animation-duration="2000"
                    :size="17"
                    color="#fff"
                    v-if="loading == 'MaskanFile'"
                  />
                  <span v-else class="pull-right ml-2">
                    <i class="tim-icons icon-pencil"></i>
                  </span>
                </transition>بروزرسانی اطلاعات
              </base-button>
            </div>
          </div>
        </card>
      </div>
    </div>
  </div>
</template>

<script>
import { FlipClock } from "@mvpleung/flipclock";

export default {
  components: {
    FlipClock
  },

  data() {
    return {
      loading: null,

      MelkeIrani: {
        username: "",
        password: ""
      },
      MaskanFile: {
        username: "",
        password: ""
      }
    };
  },

  beforeCreate() {
    axios
      .post("/graphql/auth", {
        query: `{
          botProviders {
            id provider username password
          }
        }`
      })
      .then(({ data }) => {
        this.MaskanFile = _.find(data.data.botProviders, {
          provider: "MaskanFile"
        });
        this.MelkeIrani = _.find(data.data.botProviders, {
          provider: "MelkeIrani"
        });
      });
  },

  methods: {
    updateInfo(provider) {
      if (!this[provider].username || !this[provider].password) return;

      this.loading = provider;

      axios
        .post("/graphql/auth", {
          query: `mutation {
            updateBotProviderInfo(
              provider: "${provider}"
              username: "${this[provider].username}"
              password: "${this[provider].password}"
            ) {
              status
              message
            }
          }`
        })
        .then(({ data }) => {
          this.loading = null;
          this.$notify({
            message: data.data.updateBotProviderInfo.message,
            timeout: 1500,
            type:
              data.data.updateBotProviderInfo.status == 200
                ? "success"
                : "danger",
            verticalAlign: "top",
            horizontalAlign: "left"
          });
        });
    }
  }
};
</script>
