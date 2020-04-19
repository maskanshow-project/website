<template>
  <div class="parent_header_area">
    <header class="header_area">
      <div class="main_menu as-shadow">
        <nav class="navbar navbar-expand-lg navbar-light">
          <div class="container box_1620">
            <!-- Brand and toggle get grouped for better mobile display -->
            <router-link class="navbar-brand logo_h" to="/properties" v-show="Res">
              <img class="logo-site res" src="/img/logo.svg" alt="logo" />
            </router-link>

            <div v-show="!Res">
              <div v-if="is_exist(me)">
                <vs-dropdown vs-custom-content vs-trigger-click>
                  <vs-button
                    class="user-full-name"
                    :color="web_color"
                    icon="expand_more"
                    line-origin="right"
                    type="line"
                  >
                    {{ me.full_name.trim() || me.username || me.email }}
                    <v-avatar class="ml-2" :size="40" :color="web_color">
                      <img
                        :src=" me.avatar ? url + me.avatar.small : '/img/user.png' "
                        alt="avatar"
                      />
                    </v-avatar>
                  </vs-button>

                  <vs-dropdown-menu class="loginx">
                    <v-list dense shaped>
                      <v-list-tile href="/panel">
                        <v-list-tile-content>
                          <v-list-tile-title class="text-right">پنل کاربری</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                          <i class="fs-20 mr-2 lnr lnr-user"></i>
                        </v-list-tile-action>
                      </v-list-tile>

                      <v-list-tile href="/panel/estate/create">
                        <v-list-tile-content>
                          <v-list-tile-title class="text-right">ثبت ملک جدید</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                          <i class="fs-20 mr-2 lnr lnr-file-add"></i>
                        </v-list-tile-action>
                      </v-list-tile>

                      <v-list-tile href="/panel/estate/mine">
                        <v-list-tile-content>
                          <v-list-tile-title class="text-right">ملک های من</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                          <i class="fs-20 mr-2 lnr lnr-flag"></i>
                        </v-list-tile-action>
                      </v-list-tile>

                      <v-list-tile href="/panel/estate/favorite">
                        <v-list-tile-content>
                          <v-list-tile-title class="text-right">ملک های نشان شده</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                          <i class="fs-20 mr-2 lnr lnr-bookmark"></i>
                        </v-list-tile-action>
                      </v-list-tile>

                      <v-list-tile href="/panel/setting/user">
                        <v-list-tile-content>
                          <v-list-tile-title class="text-right">تنظیمات</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                          <i class="fs-20 mr-2 lnr lnr-cog"></i>
                        </v-list-tile-action>
                      </v-list-tile>

                      <v-list-tile @click="logOut" class="text-danger">
                        <v-list-tile-content>
                          <v-list-tile-title class="text-right">خروج از حساب</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                          <i class="fs-20 mr-2 lnr lnr-exit"></i>
                        </v-list-tile-action>
                      </v-list-tile>
                    </v-list>
                  </vs-dropdown-menu>
                </vs-dropdown>
              </div>
              <v-btn
                v-else
                class="px-4"
                :color="web_color"
                dark
                round
                @click="Set_state({ prop : 'login_modal' , val : true })"
              >ورود / ثبت نام</v-btn>
            </div>

            <div id="nav-icon3" :class="{ 'open' : drawer }" v-show="Res" @click="drawer = !drawer">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
              <ul class="nav navbar-nav menu_nav ml-auto rtl pr-0">
                <li class="nav-item" v-for="item in header_links" :key="item.title">
                  <router-link class="nav-link" :to="item.link">{{ item.title }}</router-link>
                </li>

                <li class="nav-item">
                  <a
                    class="nav-link"
                    href="/app/maskanshow v1.3.0.apk"
                    download
                  >دانلود اپلیکیشن آندروید</a>
                </li>
              </ul>
            </div>

            <!-- Brand and toggle get grouped for better mobile display -->
            <router-link class="navbar-brand logo_h" to="/properties" v-show="!Res">
              <img class="logo-site" src="/img/logo.svg" alt="logo" />
            </router-link>
          </div>
        </nav>
      </div>
    </header>

    <!-- Navigation Drawer -->
    <v-app>
      <v-navigation-drawer class="drawer-res" :dark="false" v-model="drawer" fixed temporary>
        <div class="p-2 text-center">
          <template v-if="is_exist(me)">
            <v-avatar class="my-2" :size="60" :color="web_color">
              <img :src=" me.avatar ? url + me.avatar.small : '/img/user.png' " alt="avatar" />
            </v-avatar>

            <p class="text-center mb-0">{{ me.full_name.trim() || me.username || me.email }}</p>
          </template>

          <img v-else class="logo-site" src="/img/logo.svg" alt="logo" />
        </div>

        <v-list dense>
          <v-divider></v-divider>

          <v-list-tile
            v-for="item in header_links"
            :key="item.title"
            @click="$router.push(item.link)"
          >
            <v-list-tile-content>
              <v-list-tile-title class="text-right">{{ item.title }}</v-list-tile-title>
            </v-list-tile-content>

            <v-list-tile-action>
              <i :class="[ 'fs-20 mr-2 lnr' , item.icon ]"></i>
            </v-list-tile-action>
          </v-list-tile>

          <v-list-tile>
            <a
              href="/app/maskanshow v1.1.6.apk"
              download
              style="color: #2a2a2a; display: flex; width: 100%;"
            >
              <v-list-tile-content>
                <v-list-tile-title class="text-right">دانلود اپلیکیشن آندروید</v-list-tile-title>
              </v-list-tile-content>

              <v-list-tile-action>
                <i :class="[ 'fs-20 mr-2 lnr' , 'lnr lnr-smartphone' ]"></i>
              </v-list-tile-action>
            </a>
          </v-list-tile>

          <template v-if="is_exist(me)">
            <v-list-tile href="/panel">
              <v-list-tile-content>
                <v-list-tile-title class="text-right">پنل کاربری</v-list-tile-title>
              </v-list-tile-content>
              <v-list-tile-action>
                <i class="fs-20 mr-2 lnr lnr-user"></i>
              </v-list-tile-action>
            </v-list-tile>

            <v-list-tile href="/panel/estate/create">
              <v-list-tile-content>
                <v-list-tile-title class="text-right">ثبت ملک جدید</v-list-tile-title>
              </v-list-tile-content>
              <v-list-tile-action>
                <i class="fs-20 mr-2 lnr lnr-file-add"></i>
              </v-list-tile-action>
            </v-list-tile>

            <v-list-tile href="/panel/estate/mine">
              <v-list-tile-content>
                <v-list-tile-title class="text-right">ملک های من</v-list-tile-title>
              </v-list-tile-content>
              <v-list-tile-action>
                <i class="fs-20 mr-2 lnr lnr-flag"></i>
              </v-list-tile-action>
            </v-list-tile>

            <v-list-tile href="/panel/estate/favorite">
              <v-list-tile-content>
                <v-list-tile-title class="text-right">ملک های نشان شده</v-list-tile-title>
              </v-list-tile-content>
              <v-list-tile-action>
                <i class="fs-20 mr-2 lnr lnr-bookmark"></i>
              </v-list-tile-action>
            </v-list-tile>

            <v-list-tile href="/panel/setting/user">
              <v-list-tile-content>
                <v-list-tile-title class="text-right">تنظیمات</v-list-tile-title>
              </v-list-tile-content>
              <v-list-tile-action>
                <i class="fs-20 mr-2 lnr lnr-cog"></i>
              </v-list-tile-action>
            </v-list-tile>
          </template>
        </v-list>

        <div class="login-drawer" v-if="is_exist(me)">
          <div>
            <v-btn color="#E91E63" dark round @click="logOut">
              خروج از حساب
              <v-icon right dark>exit_to_app</v-icon>
            </v-btn>
          </div>
        </div>

        <div v-else class="login-drawer">
          <v-btn :color="web_color" dark round @click="open_modal">ورود / ثبت نام</v-btn>
        </div>
      </v-navigation-drawer>
    </v-app>
  </div>
</template>

<script>
import { mapState, mapMutations } from "vuex";
import {
  // VApp ,
  VBtn,
  VIcon,
  VAvatar,
  VList,
  VListTile,
  VListTileTitle,
  VListTileContent,
  VListTileAction,
  VNavigationDrawer,
  VDivider
} from "vuetify/lib";
import mixin from "../../mixin";

export default {
  mixins: [mixin],

  components: {
    // VApp ,
    VBtn,
    VIcon,
    VAvatar,
    VList,
    VListTile,
    VListTileTitle,
    VListTileContent,
    VListTileAction,
    VNavigationDrawer,
    VDivider
  },

  mounted() {
    $(document).ready(function() {
      var nav_offset_top = $(".header_area").height() + 50;
      function navbarFixed() {
        if ($(".header_area").length) {
          $(window).scroll(function() {
            var scroll = $(window).scrollTop();
            if (scroll >= nav_offset_top) {
              $(".header_area").addClass("navbar_fixed");
            } else {
              $(".header_area").removeClass("navbar_fixed");
            }
          });
        }
      }
      navbarFixed();
    });
  },

  data() {
    return {
      drawer: false
    };
  },

  computed: {
    ...mapState([
      "me",
      // 'assignments' ,
      "url",
      "req_url"
    ]),

    header_links() {
      let links = [
        { title: "صفحه اصلی", link: { path: "/" }, icon: "lnr-home" }
      ];

      // if(this.is_exist(this.assignments)) {
      // this.assignments.slice(0,4).map( el => {
      //     links.push({
      //         title : el.title ,
      //         link : { path : '/properties' , query : { assignments : el.id } } ,
      //         icon : 'lnr-apartment'
      //     })
      // })
      // } else {
      // }

      links.push({
        title: "املاک",
        link: { path: "/properties" },
        icon: "lnr-apartment"
      });

      links.push({
        title: "وبلاگ",
        link: "/articles",
        icon: "lnr-book"
      });

      return links;
    }
  },

  methods: {
    ...mapMutations(["Set_state"]),

    open_modal() {
      this.drawer = false;

      setTimeout(() => {
        this.Set_state({ prop: "login_modal", val: true });
      }, 400);
    },

    logOut() {
      axios({
        method: "POST",
        url: this.req_url,
        data: {
          query: `
                            mutation {
                                logout {
                                    status
                                    message
                                }
                            }
                        `
        }
      })
        .then(({ data }) => {
          if (data.data.logout.status == 200) {
            window.localStorage.removeItem("JWT");
            location.reload();
          } else {
            this.notif(
              "متاسفانه عملیات با موفقیت انجام نشد",
              "warning",
              "error"
            );
          }
        })
        .catch(Err => console.log(Err));
    }
  }
};
</script>

<style>
.logo-site {
  height: 80px;
  width: auto;
  transform: scale(2) translateY(2px);
}

.logo-site.res {
  height: 60px !important;
}

.drawer-res .logo-site {
  height: 60px !important;
  transform: scale(2.5) translateY(5px);
}

.user-full-name {
  border-radius: 4px;
}

.user-full-name span.vs-button-linex {
  height: 100%;
  background: transparent !important;
  border-bottom: 2px solid #29b6f6;
  border-radius: 4px;
}
</style>

<style scoped>
.login-drawer {
  position: absolute;
  bottom: 5px;
  width: 100%;
  text-align: center;
}

#nav-icon3 {
  width: 30px;
  height: 35px;
  position: relative;
  /* margin: 50px auto; */
  -webkit-transform: rotate(0deg);
  -moz-transform: rotate(0deg);
  -o-transform: rotate(0deg);
  transform: rotate(0deg);
  -webkit-transition: 0.5s ease-in-out;
  -moz-transition: 0.5s ease-in-out;
  -o-transition: 0.5s ease-in-out;
  transition: 0.5s ease-in-out;
  cursor: pointer;
}

#nav-icon3 span {
  display: block;
  position: absolute;
  height: 4px;
  width: 100%;
  background: navy;
  border-radius: 9px;
  opacity: 1;
  left: 0;
  -webkit-transform: rotate(0deg);
  -moz-transform: rotate(0deg);
  -o-transform: rotate(0deg);
  transform: rotate(0deg);
  -webkit-transition: 0.25s ease-in-out;
  -moz-transition: 0.25s ease-in-out;
  -o-transition: 0.25s ease-in-out;
  transition: 0.25s ease-in-out;
}

#nav-icon3 span:nth-child(1) {
  top: 7px;
}

#nav-icon3 span:nth-child(2),
#nav-icon3 span:nth-child(3) {
  top: 15px;
}

#nav-icon3 span:nth-child(4) {
  top: 23.5px;
}

#nav-icon3.open span:nth-child(1) {
  top: 18px;
  width: 0%;
  left: 50%;
}

#nav-icon3.open span:nth-child(2) {
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  transform: rotate(45deg);
}

#nav-icon3.open span:nth-child(3) {
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  transform: rotate(-45deg);
}

#nav-icon3.open span:nth-child(4) {
  top: 18px;
  width: 0%;
  left: 50%;
}
</style>
