import Vue from "vue";

// Vuetify
import Vuetify, { VApp } from "vuetify/lib";
Vue.use(Vuetify, {
    components: {
        VApp
    }
});

import "vuetify/dist/vuetify.css";

// Element UI
import lang from "element-ui/lib/locale/lang/fa";
import locale from "element-ui/lib/locale";
locale.use(lang);
import "element-ui/lib/theme-chalk/index.css";
import "element-ui/lib/theme-chalk/display.css";

// Vuesax
import vuesax from "vuesax";
import "vuesax/dist/vuesax.css";
Vue.use(vuesax);

// Vue 2 Filters
import Vue2Filters from "vue2-filters";
Vue.use(Vue2Filters);

// Loading
import { RadarSpinner } from "epic-spinners";
Vue.component("RadarSpinner", RadarSpinner);

// Material Design Icons
import "material-design-icons-iconfont/dist/material-design-icons.css";

import "hover.css";

// Fontawesome
// import '@fortawesome/fontawesome-free/css/all.css';

// Animate
// import 'animate.css';

// Tilt
import "tilt.js";

// Social Sharing
import SocialSharing from "vue-social-sharing";
Vue.use(SocialSharing);
