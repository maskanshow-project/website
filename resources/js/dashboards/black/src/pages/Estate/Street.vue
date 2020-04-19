<template>
  <datatable
    :type="type"
    :group="group"
    :label="label"
    :fields="getFields"
    :methods="{
      create: create,
      edit: edit,
      store: store,
      update: update
    }"
    ref="datatable"
  >
    <template #map-body="slotProps">
      <img
        v-if="slotProps.row.coordinates && slotProps.row.coordinates.lat"
        :src="
          `https://api.neshan.org/v2/static?key=${
            $store.state.MAP_API_KEY
          }&type=dreamy&zoom=13&center=${slotProps.row.coordinates.lat},${
            slotProps.row.coordinates.lng
          }&width=1120&height=300&marker=blue`
        "
        alt="Map"
      />
    </template>

    <template #area-body="slotProps">
      <span v-if="slotProps.row.area">{{ slotProps.row.area.name }}</span>
    </template>

    <template slot="modal">
      <md-field :class="getValidationClass('name')">
        <label>نام خیابان</label>
        <md-input v-model="name" :maxlength="$v.name.$params.maxLength.max" />
        <i class="md-icon tim-icons icon-caps-small"></i>
        <span class="md-helper-text">برای مثال : احمدآباد</span>
        <span class="md-error" v-show="!$v.name.required">لطفا نام خیابان را وارد کنید</span>
      </md-field>
      <br />

      <md-field :class="getValidationClass('name')">
        <label>عبارت باقاعده</label>
        <md-input v-model="regex" :maxlength="$v.regex.$params.maxLength.max" dir="ltr" />
        <span class="md-helper-text">برای مثال : /(ابوطالب)[13579]/</span>
      </md-field>
      <br />

      <md-field>
        <label>منطقه</label>
        <md-select v-model="area_id">
          <md-option
            v-for="item in $store.state.place.area"
            :key="item.id"
            :value="item.id"
          >{{ item.name }}</md-option>
        </md-select>
        <i class="md-icon tim-icons icon-cloud-download-93"></i>
        <span class="md-helper-text">منطقه این خیابان را مشخص کنید</span>
      </md-field>
      <br />

      <base-input label="موقعیت جغرافیایی">
        <l-map
          v-show="true"
          ref="map"
          style="height: 300px; width: 100%"
          :options="{
            key: 'web.8HU2Ho1RdkaEAT79wJPPdc1ddkgRH0iPIcSqBThP'
          }"
          :zoom="zoom"
          :center="center"
          :zoomAnimation="true"
          :zoomAnimationThreshold="10"
          :fadeAnimation="true"
          :markerZoomAnimation="true"
          :noBlockingAnimations="true"
          @update:zoom="zoomUpdated"
          @update:center="centerUpdated"
          @update:bounds="boundsUpdated"
        >
          <l-tile-layer url="http://{s}.tile.osm.org/{z}/{x}/{y}.png"></l-tile-layer>
          <l-marker draggable :lat-lng.sync="location">
            <l-icon :icon-size="[40, 50]" :icon-anchor="[20, 50]" icon-url="/location_marker.svg"></l-icon>
          </l-marker>
        </l-map>
        <small slot="helperText" id="emailHelp" class="form-text text-muted">
          با گرفتن و رها کردن نشانگر آبی رنگ موقعیت جغرافیایی خیابان را مشخص
          کنید
        </small>
      </base-input>
    </template>
  </datatable>
</template>

<script>
import Datatable from "../../components/BaseDatatable.vue";
import BaseTable from "../../components/BaseTable";
import {
  LMap,
  LTileLayer,
  LMarker,
  LIcon,
  LControl,
  LCircleMarker
} from "vue2-leaflet";
import "leaflet/dist/leaflet.css";

import createMixin from "../../mixins/createMixin";
import initDatatable from "../../mixins/initDatatable";
import Binding, { bind } from "../../mixins/binding";
import { validationMixin } from "vuelidate";
import { required, maxLength } from "vuelidate/lib/validators";
import filtersHelper from "../../mixins/filtersHelper";

export default {
  components: {
    BaseTable,
    Datatable,
    LMap,
    LTileLayer,
    LMarker,
    LIcon,
    LControl,
    LCircleMarker
  },
  mixins: [initDatatable, createMixin, validationMixin, filtersHelper, Binding],
  metaInfo: {
    title: "خیابان ها"
  },
  data() {
    return {
      plural: "streets",
      type: "street",
      group: "place",
      label: "خیابان",

      location: { lat: 36.2605, lng: 59.6168 },
      area_id: null,

      zoom: 12,
      center: [36.2605, 59.6168],
      bounds: null,
      location: null
    };
  },
  validations: {
    name: {
      required,
      maxLength: maxLength(30)
    },
    regex: {
      required,
      maxLength: maxLength(150)
    }
  },
  computed: {
    name: bind("name"),
    regex: bind("regex"),

    getFields() {
      return [
        {
          field: "name",
          label: "نام خیابان",
          icon: "icon-caps-small"
        },
        {
          field: "area",
          label: "منطقه",
          icon: "icon-square-pin"
        },
        {
          field: "map",
          label: "موقعیت جغرافیایی",
          icon: "icon-compass-05"
        }
      ];
    },
    allQuery() {
      return `name regex coordinates { lat lng } area { id name }`;
    }
  },
  mounted() {
    this.$store.dispatch("getData", {
      group: "place",
      type: "area",
      query: `areas(per_page: 20) {
        data { id name coordinates { lat lng } city { id name } created_at updated_at }
        total trash chart { month count }
      }`
    });
  },
  methods: {
    zoomUpdated(zoom) {
      this.zoom = zoom;
    },
    centerUpdated(center) {
      this.center = center;
    },
    boundsUpdated(bounds) {
      this.bounds = bounds;
    },
    getVariables() {
      return {
        area_id: this.area_id,
        name: this.name,
        regex: this.regex,
        lat: this.location.lat,
        lng: this.location.lng
      };
    },
    afterCreate() {
      this.location = { lat: 36.2605, lng: 59.6168 };
      setTimeout(
        () => this.$refs.map.setCenter({ lat: 36.2605, lng: 59.6168 }),
        1000
      );
    },
    afterEdit(row) {
      if (row.coordinates && row.coordinates.lat) {
        this.location = row.coordinates;
        setTimeout(() => this.$refs.map.setCenter(row.coordinates), 500);
      } else {
        this.location = { lat: 36.2605, lng: 59.6168 };
        setTimeout(
          () => this.$refs.map.setCenter({ lat: 36.2605, lng: 59.6168 }),
          1000
        );
      }

      this.area_id = row.area ? row.area.id : null;
    }
  },
  beforeRouteLeave(to, from, next) {
    this.$refs.datatable.closePanel();

    setTimeout(() => next(), 700);
  }
};
</script>
