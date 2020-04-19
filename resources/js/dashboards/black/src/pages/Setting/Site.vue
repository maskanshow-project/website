<template>
  <div class="row" :style="{ position: 'relative', zIndex: 10 }">
    <div class="row col-12 mb-3">
      <div class="col-12 text-right">
        <div class="pull-right">
          <h1
            class="animated bounceInRight delay-first"
            :style="{ color: '#fff', fontWeight: 'bold', textShadow: '0px 3px 15px #333' }"
          >
            مدیریت
            <span :style="{ color: '#ff3d3d' }">تنظیمات</span> سایت
            <i class="header-nav-icon tim-icons icon-align-left-2" :style="{fontSize: '25px'}"></i>
          </h1>
          <h6
            class="header-description animated bounceInRight delay-secound"
          >با استفاده از بخش زیر میتوانید تنظیمات کلی وبسایت خود را مدیریت کنید</h6>
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

    <card class="text-right animated bounceInBottom delay-last" v-if="is_loaded" dir="rtl">
      <span slot="header">اطلاعات کلی</span>

      <div class="row">
        <div class="col-md-6">
          <md-field :class="getValidationClass('title')">
            <label>عنوان سایت</label>
            <md-input v-model="info.title" :maxlength="$v.title.$params.maxLength.max" />
            <i class="md-icon tim-icons icon-caps-small"></i>
            <span class="md-helper-text">در تب مرورگر نمایش داده میشود</span>
          </md-field>

          <md-field :class="getValidationClass('description')">
            <label>توضیحات سایت</label>
            <md-textarea
              v-model="info.description"
              :maxlength="$v.description.$params.maxLength.max"
            ></md-textarea>
            <i class="md-icon tim-icons icon-paper"></i>
            <span class="md-helper-text">در قسمت پاصفحه وبسایت نمایش داده میشود</span>
          </md-field>
        </div>

        <div class="col-md-6">
          <md-field :class="getValidationClass('phone')">
            <label>شماره تلفن سایت</label>
            <md-input v-model="info.phone" />
            <i class="md-icon tim-icons icon-mobile"></i>
            <span class="md-helper-text">جهت نمایش در سایت</span>
          </md-field>

          <md-field :class="getValidationClass('address')">
            <label>آدرس سایت</label>
            <md-textarea v-model="info.address" :maxlength="$v.address.$params.maxLength.max"></md-textarea>
            <i class="md-icon tim-icons icon-map-big"></i>
            <span class="md-helper-text">در قسمت پاصفحه وبسایت نمایش داده میشود</span>
          </md-field>
        </div>
      </div>
      <br />

      <div class="row">
        <div class="col-md-6">
          <base-input label="عکس سر تیتر">
            <el-upload
              class="avatar-uploader"
              action="/"
              :auto-upload="false"
              :show-file-list="false"
              :on-change="file => addImage('banner', file)"
            >
              <img
                v-if="info.banner.url"
                :src="info.banner.url"
                :style="{ borderRadius: '0px', width: '100% !important' }"
              />
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
            <small
              slot="helperText"
              id="emailHelp"
              class="form-text text-muted"
            >عکس سرتیتر قالب در تمامی صفحات قرار میگیرد</small>
          </base-input>
        </div>

        <div class="col-md-6">
          <base-input label="بنر ابتدایی">
            <el-upload
              class="avatar-uploader"
              action="/"
              :auto-upload="false"
              :show-file-list="false"
              :on-change="file => addImage('header_banner', file)"
            >
              <img
                v-if="info.header_banner.url"
                :src="info.header_banner.url"
                :style="{ borderRadius: '0px', width: '100% !important' }"
              />
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
            <small
              slot="helperText"
              id="emailHelp"
              class="form-text text-muted"
            >این تصویر در اولین بخش قالب قرار خواهد گرفت</small>
          </base-input>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3" dir="ltr">
          <base-input label="وضعیت سایت">
            <el-switch
              class="d-block mb-1"
              v-model="info.is_enabled"
              active-text="فعال"
              inactive-text="غیرفعال"
            ></el-switch>
            <small
              slot="helperText"
              id="emailHelp"
              class="form-text text-muted"
            >در صورت غیر فعال کردن سایت برای کاربران قابل مشاهده نیست</small>
          </base-input>
        </div>

        <div class="col-md-3">
          <md-field :class="getValidationClass('theme_color')">
            <el-color-picker v-model="info.theme_color"></el-color-picker>
            <span class="md-helper-text pt-2">رنگ قالب سایت انتخاب کنید</span>
          </md-field>
        </div>

        <div class="col-md-6">
          <md-field :class="getValidationClass('banner_link')">
            <label>لینک بنر بالا</label>
            <md-input v-model="info.banner_link" />
            <i class="md-icon tim-icons icon-link-72"></i>
            <span class="md-helper-text">میتوانید هریک از لینک های سایت را قرار دهید</span>
          </md-field>
        </div>
      </div>

      <base-button @click="updateInfo" size="sm" type="warning" :loading="is_update_site_info">
        <transition name="fade" mode="out-in">
          <semipolar-spinner
            :animation-duration="2000"
            :size="17"
            color="#fff"
            v-if="is_update_site_info"
          />
          <span v-else class="pull-right ml-2">
            <i v-if="attr('is_creating')" class="tim-icons icon-simple-add"></i>
            <i v-else class="tim-icons icon-pencil"></i>
          </span>
        </transition>بروز رسانی تنظیمات کلی
      </base-button>
    </card>

    <div class="row">
      <div class="col-md-4">
        <card class="text-right animated bounceInBottom delay-last" v-if="is_loaded" dir="rtl">
          <span slot="header">مدیریت تبلیغات</span>

          <div class="row" v-for="(ad, index) in ads" :key="index">
            <div class="col-md-12">
              <base-input label="بنر تبلیغاتی">
                <el-upload
                  class="avatar-uploader"
                  action="/"
                  :auto-upload="false"
                  :show-file-list="false"
                  :on-change="file => addImage('ads', file, index)"
                >
                  <img
                    v-if="ad.image.url"
                    :src="ad.image.url"
                    :style="{ borderRadius: '0px', width: '100% !important' }"
                  />
                  <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>
                <div
                  class="text-left ml-2"
                  :style="{
                  marginTop: '-45px',
                  zIndex: 10000,
                  position: 'relative',
                  marginBottom: '15px'
                }"
                >
                  <el-button @click="removeImage(ad)" type="danger" size="mini">حذف تصویر</el-button>
                </div>
                <small
                  slot="helperText"
                  id="emailHelp"
                  class="form-text text-muted"
                >بنر تبلیغاتی جهت قرار گرفتن در وبسایت</small>
              </base-input>
            </div>
            <div class="col-md-12">
              <md-field>
                <label>لینک بنر</label>
                <md-textarea v-model="ad.link" md-autogrow></md-textarea>
                <i class="md-icon tim-icons icon-link-72"></i>
                <span
                  class="md-helper-text"
                >در صورت کلیک بر روی بنر ، کاربر به لینک مورد نظر خود هدایت خواهد شد</span>
              </md-field>
            </div>
            <hr class="w-100 pull-right m-3" v-if="index !== ads.length - 1" />
          </div>

          <div
            v-if="!ads.length"
            class="alert alert-warning"
          >متاسفانه هیچ تبلیغی برای وبسایت ثبت نشده است :(</div>

          <div class="operation-cell d-flex justify-content-center mt-3">
            <base-button
              @click="updateSlider('ads')"
              :loading="is_update_site_ads"
              class="hvr-icon-spin"
              type="warning"
              size="sm"
            >
              <transition name="fade" mode="out-in">
                <semipolar-spinner
                  :animation-duration="2000"
                  :size="17"
                  color="#fff"
                  v-if="is_update_site_ads"
                />
                <span v-else class="pull-right ml-2">
                  <i v-if="attr('is_creating')" class="tim-icons icon-simple-add"></i>
                  <i v-else class="tim-icons icon-pencil"></i>
                </span>
              </transition>بروزرسانی تبلیغات
            </base-button>
          </div>
        </card>
      </div>

      <div class="col-md-4">
        <card class="text-right animated bounceInBottom delay-last" v-if="is_loaded" dir="rtl">
          <span slot="header">مدیریت پوسترها</span>

          <div class="row" v-for="(poster, index) in posters" :key="index">
            <div class="col-md-12">
              <base-input label="تصویر پوستر">
                <el-upload
                  class="avatar-uploader"
                  action="/"
                  :auto-upload="false"
                  :show-file-list="false"
                  :on-change="file => addImage('poster', file, index)"
                >
                  <img
                    v-if="poster.image.url"
                    :src="poster.image.url"
                    :style="{ borderRadius: '0px', width: '100% !important' }"
                  />
                  <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>
                <small
                  slot="helperText"
                  id="emailHelp"
                  class="form-text text-muted"
                >تصویر پس زمینه هر پوستر</small>
              </base-input>
            </div>
            <div class="col-md-12">
              <md-field>
                <label>عنوان پوستر</label>
                <md-input v-model="poster.title" />
                <i class="md-icon tim-icons icon-caps-small"></i>
                <span class="md-helper-text">عنوان پوستر مورد نظر</span>
              </md-field>

              <md-field>
                <label>عنوان دکمه</label>
                <md-input v-model="poster.button" />
                <i class="md-icon tim-icons icon-bold"></i>
                <span class="md-helper-text">عنوان دکمه پوستر مورد نظر</span>
              </md-field>

              <md-field>
                <label>لینک دکمه</label>
                <md-textarea v-model="poster.link" md-autogrow></md-textarea>
                <i class="md-icon tim-icons icon-link-72"></i>
                <span class="md-helper-text">میتواند از لینک های خود وبسایت استفاده شود</span>
              </md-field>

              <md-field>
                <label>پاراگرف</label>
                <md-textarea v-model="poster.description" md-autogrow></md-textarea>
                <i class="md-icon tim-icons icon-paper"></i>
                <span class="md-helper-text">پاراگرف زیر عنوان برای پوستر مورد نظر</span>
              </md-field>
            </div>
            <hr class="w-100 pull-right m-3" v-if="index !== posters.length - 1" />
          </div>

          <div
            v-if="!posters.length"
            class="alert alert-warning"
          >متاسفانه هیچ پوستری برای وبسایت ثبت نشده است :(</div>

          <div class="operation-cell d-flex justify-content-center mt-3">
            <base-button
              @click="updateSlider('posters')"
              :loading="is_update_site_posters"
              class="hvr-icon-spin"
              type="warning"
              size="sm"
            >
              <transition name="fade" mode="out-in">
                <semipolar-spinner
                  :animation-duration="2000"
                  :size="17"
                  color="#fff"
                  v-if="is_update_site_posters"
                />
                <span v-else class="pull-right ml-2">
                  <i v-if="attr('is_creating')" class="tim-icons icon-simple-add"></i>
                  <i v-else class="tim-icons icon-pencil"></i>
                </span>
              </transition>بروزرسانی پوسترها
            </base-button>
          </div>
        </card>
      </div>

      <div class="col-md-4">
        <card class="text-right animated bounceInBottom delay-last" v-if="is_loaded" dir="rtl">
          <span slot="header">مدیریت نظرات</span>

          <div class="row" v-for="(opinion, index) in opinions" :key="index">
            <div class="col-md-12">
              <base-input label="تصویر شخص">
                <el-upload
                  class="avatar-uploader"
                  action="/"
                  :auto-upload="false"
                  :show-file-list="false"
                  :on-change="file => addImage('opinion', file, index)"
                >
                  <img
                    v-if="opinion.avatar.url"
                    :src="opinion.avatar.url"
                    :style="{ borderRadius: '0px', width: '100% !important' }"
                  />
                  <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>
                <small
                  slot="helperText"
                  id="emailHelp"
                  class="form-text text-muted"
                >تصویر شخص صاحب نظر</small>
              </base-input>
            </div>
            <div class="col-md-12">
              <md-field>
                <label>سمت کاری</label>
                <md-input v-model="opinion.post" />
                <i class="md-icon tim-icons icon-caps-small"></i>
                <span class="md-helper-text">برای مثال : مدیر دفتر املاک آسمان</span>
              </md-field>

              <md-field>
                <label>نام شخص</label>
                <md-textarea v-model="opinion.fullname" md-autogrow></md-textarea>
                <i class="md-icon tim-icons icon-link-72"></i>
                <span class="md-helper-text">برای مثال : امیر جعفرزاده</span>
              </md-field>

              <md-field>
                <label>نظر شخص</label>
                <md-textarea v-model="opinion.opinion" md-autogrow></md-textarea>
                <i class="md-icon tim-icons icon-paper"></i>
                <span class="md-helper-text">متن کامل نظر شخص را در این قسمت وارد کنید</span>
              </md-field>
            </div>
            <hr class="w-100 pull-right m-3" v-if="index !== opinions.length - 1" />
          </div>

          <div
            v-if="!opinions.length"
            class="alert alert-warning"
          >متاسفانه بخش نظرات وبسایت در حال حاضر خالیست :(</div>

          <div class="operation-cell d-flex justify-content-center mt-3">
            <el-tooltip content="افزودن نظر جدید">
              <base-button
                @click="opinions.push({ avatar: {} })"
                class="hvr-icon-spin ml-2"
                type="success"
                size="sm"
                icon
              >
                <i
                  class="tim-icons icon-simple-add hvr-icon"
                  :style="{ fontSize: '18px !important' }"
                ></i>
              </base-button>
            </el-tooltip>

            <el-tooltip content="حذف آخرین نظر">
              <base-button
                @click="opinions.pop()"
                class="hvr-icon-spin ml-2"
                type="danger"
                size="sm"
                icon
              >
                <i
                  class="tim-icons icon-trash-simple hvr-icon"
                  :style="{ fontSize: '18px !important' }"
                ></i>
              </base-button>
            </el-tooltip>

            <base-button
              @click="updateSlider('opinions')"
              :loading="is_update_site_opinions"
              class="hvr-icon-spin"
              type="warning"
              size="sm"
            >
              <transition name="fade" mode="out-in">
                <semipolar-spinner
                  :animation-duration="2000"
                  :size="17"
                  color="#fff"
                  v-if="is_update_site_opinions"
                />
                <span v-else class="pull-right ml-2">
                  <i v-if="attr('is_creating')" class="tim-icons icon-simple-add"></i>
                  <i v-else class="tim-icons icon-pencil"></i>
                </span>
              </transition>بروزرسانی نظرات
            </base-button>
          </div>
        </card>
      </div>
    </div>

    <transition name="fade">
      <div class="main-panel-loading" v-if="!is_loaded">
        <fingerprint-spinner :animation-duration="1000" :size="100" color="#fff" />
      </div>
    </transition>

    <transition name="loading">
      <div class="query-loader" v-if="attr('is_query_loading')">
        <half-circle-spinner :animation-duration="800" :size="40" color="#fff" />
      </div>
    </transition>
  </div>
</template>
<script>
import { FlipClock } from "@mvpleung/flipclock";
import { validationMixin } from "vuelidate";
import { required, maxLength } from "vuelidate/lib/validators";
import binding from "../../mixins/binding";
import createMixin from "../../mixins/createMixin";
import {
  SemipolarSpinner,
  HalfCircleSpinner,
  FingerprintSpinner
} from "epic-spinners";

export default {
  components: {
    FlipClock,
    SemipolarSpinner,
    HalfCircleSpinner,
    FingerprintSpinner
  },
  mixins: [validationMixin, binding, createMixin],
  metaInfo: {
    title: "تنظیمات سایت"
  },
  validations: {
    title: {
      required,
      maxLength: maxLength(50)
    },
    description: {
      maxLength: maxLength(250)
    },
    address: {
      maxLength: maxLength(250)
    },
    phone: {
      //
    },
    theme_color: {
      //
    }
  },
  data() {
    return {
      type: null,
      group: "setting",
      label: "تنظیمات",

      info: {
        title: "",
        description: "",
        address: "",
        phone: "",
        theme_color: "",
        is_enabled: true,
        banner_link: "",
        banner: {
          file: null,
          url: ""
        },
        header_banner: {
          file: null,
          url: ""
        }
      },
      opinions: [
        //
      ],
      posters: [
        //
      ],
      ads: [
        //
      ],

      is_loaded: false,
      is_update_site_info: false,
      is_update_site_opinions: false,
      is_update_site_posters: false,
      is_update_site_ads: false
    };
  },
  mounted() {
    axios
      .post("/graphql/auth", {
        query: `{
        siteSetting {
          title description phone address
          theme_color is_enabled
          banner { small }
          header_banner { small }
          opinions {
            avatar { small }
            post fullname opinion
          }
          posters {
            image { small }
            title description button link
          }
          ads { link image { small } }
        }
      }`
      })
      .then(({ data }) => {
        this.info = data.data.siteSetting;
        this.info.banner = {
          url: this.info.banner ? this.info.banner.small : null,
          file: null
        };
        this.info.header_banner = {
          url: this.info.header_banner ? this.info.header_banner.small : null,
          file: null
        };

        this.opinions = data.data.siteSetting.opinions.map(i => {
          return {
            ...i,
            ...{ avatar: { url: i.avatar ? i.avatar.small : "", file: null } }
          };
        });
        this.posters = data.data.siteSetting.posters.map(i => {
          return {
            ...i,
            ...{ image: { url: i.image ? i.image.small : "", file: null } }
          };
        });
        this.ads = data.data.siteSetting.ads.map(i => {
          return {
            ...i,
            ...{ image: { url: i.image ? i.image.small : "", file: null } }
          };
        });

        this.is_loaded = true;
      });
  },
  methods: {
    addImage(type, file, index = null) {
      const fileObj = { file: file.raw, url: URL.createObjectURL(file.raw) };

      if (type === "poster") this.posters[index].image = fileObj;
      else if (type === "opinion") this.opinions[index].avatar = fileObj;
      else if (type === "ads") this.ads[index].image = fileObj;
      else this.info[type] = fileObj;
    },
    removeImage(ad) {
      if (!ad.image.file) ad.delete_image = true;

      ad.image.file = null;
      ad.image.url = null;
    },
    validate() {
      return true;
    },
    updateInfo() {
      this.is_update_site_info = true;
      this.type = "site_info";

      this.storeInServer({
        callback: ({ data }) => {
          this.is_update_site_info = false;
          return console.log(data);
        }
      });
    },
    updateSlider(type) {
      this.type =
        type === "opinions"
          ? "site_opinions"
          : type === "ads"
          ? "site_ads"
          : "site_posters";

      type === "opinions"
        ? (this.is_update_site_opinions = true)
        : type === "ads"
        ? (this.is_update_site_ads = true)
        : (this.is_update_site_posters = true);

      this.storeInServer({
        callback: ({ data }) => {
          type === "opinions"
            ? (this.is_update_site_opinions = false)
            : type === "ads"
            ? (this.is_update_site_ads = false)
            : (this.is_update_site_posters = false);
        },
        errorResolver: error => {
          type === "opinions"
            ? (this.is_update_site_opinions = false)
            : type === "ads"
            ? (this.is_update_site_ads = false)
            : (this.is_update_site_posters = false);
        }
      });
    },
    getVariables() {
      if (this.type === "site_opinions") {
        return {
          opinions: this.opinions.map(i => {
            return {
              post: i.post,
              fullname: i.fullname,
              opinion: i.opinion,
              avatar: null
            };
          })
        };
      } else if (this.type === "site_posters") {
        return {
          posters: this.posters.map(i => {
            return {
              title: i.title,
              description: i.description,
              button: i.button,
              link: i.link,
              image: null
            };
          })
        };
      } else if (this.type === "site_ads") {
        return {
          ads: this.ads.map(i => {
            return {
              delete_image: i.delete_image || false,
              link: i.link,
              image: null
            };
          })
        };
      }

      return {
        title: this.info.title,
        description: this.info.description,
        phone: this.info.phone,
        address: this.info.address,
        theme_color: this.info.theme_color,
        is_enabled: this.info.is_enabled,
        banner_link: this.info.banner_link,
        banner: null,
        header_banner: null
      };
    },
    changeFormData(fd) {
      if (this.type === "site_posters") {
        if (this.posters.filter(i => i.image.file).length === 0) return fd;

        let map = {};

        this.posters.forEach((poster, index) => {
          if (!poster.image.file) return;
          map[`images_${index}`] = [`variables.posters.${index}.image`];
          fd.append(`images_${index}`, poster.image.file);
        });

        fd.delete("map");
        fd.append("map", JSON.stringify(map));

        return fd;
      } else if (this.type === "site_opinions") {
        if (this.opinions.filter(i => i.avatar.file).length === 0) return fd;

        let map = {};

        this.opinions.forEach((opinion, index) => {
          if (!opinion.avatar.file) return;

          map[`images_${index}`] = [`variables.opinions.${index}.avatar`];
          fd.append(`images_${index}`, opinion.avatar.file);
        });

        fd.delete("map");
        fd.append("map", JSON.stringify(map));

        return fd;
      } else if (this.type === "site_ads") {
        if (this.ads.filter(i => i.image.file).length === 0) return fd;

        let map = {};

        this.ads.forEach((ad, index) => {
          if (!ad.image.file) return;

          map[`images_${index}`] = [`variables.ads.${index}.image`];
          fd.append(`images_${index}`, ad.image.file);
        });

        fd.delete("map");
        fd.append("map", JSON.stringify(map));

        return fd;
      }

      fd.delete("map");
      let map = {};

      if (this.info.banner.file) {
        map.banner = [`variables.banner`];
        fd.append("banner", this.info.banner.file);
      }

      if (this.info.header_banner.file) {
        map.header_banner = [`variables.header_banner`];
        fd.append("header_banner", this.info.header_banner.file);
      }

      fd.append("map", JSON.stringify(map));

      return fd;
    }
  },
  computed: {
    allQuery() {
      return `status message`;
    }
  }
};
</script>
<style>
</style>
