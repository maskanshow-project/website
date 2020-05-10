import "./bootstrap";
import "./import";
import Vue from "vue";
import store from "./store";
import App from "./components/App.vue";

import anime from "animejs";
window.anime = anime;

import Typed from "typed.js";
window.Typed = Typed;

// Vue Router
import VueRouter from "vue-router";
Vue.use(VueRouter);
import router from "./router.js";

// Vue Meta
import VueMeta from "vue-meta";
Vue.use(VueMeta);

// Configuration VueAnalytics
import VueAnalytics from "vue-analytics";
Vue.use(VueAnalytics, {
    id: "UA-157961258-2",
    router
});

// Vue Geolocation
import VueGeolocation from "vue-browser-geolocation";
Vue.use(VueGeolocation);

Vue.component("App", App);

axios.defaults.headers.common["Authorization"] =
    "Bearer " + window.localStorage.getItem("JWT");
axios.defaults.headers.common["Access-Control-Allow-Origin"] = "*";
axios.defaults.headers.common["Access-Control-Allow-Methods"] =
    "Access-Control-Allow-Methods";
axios.defaults.headers.common["Access-Control-Allow-Headers"] =
    "Origin, Content-Type, X-Auth-Token";

const app = new Vue({
    el: "#app",
    router,
    store
});
