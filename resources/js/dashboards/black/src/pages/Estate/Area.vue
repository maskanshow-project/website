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
    ref="datatable">
    
    <template #map-body="slotProps">
      <img
        v-if="slotProps.row.coordinates && slotProps.row.coordinates.lat"
        :src="`https://api.neshan.org/v2/static?key=${$store.state.MAP_API_KEY}&type=dreamy&zoom=13&center=${slotProps.row.coordinates.lat},${slotProps.row.coordinates.lng}&width=1120&height=300&marker=blue`"
        alt="Map"
      />
    </template>
    
    <template #city-body="slotProps">
      <span v-if="slotProps.row.city">
        {{ slotProps.row.city.name }}
      </span>
    </template>

    <template slot="modal">
      <md-field :class="getValidationClass('name')">
        <label>نام منطقه</label>
        <md-input v-model="name" :maxlength="$v.name.$params.maxLength.max" />
        <i class="md-icon tim-icons icon-caps-small"></i>
        <span class="md-helper-text">برای مثال : منطقه ۱</span>
        <span class="md-error" v-show="!$v.name.required">لطفا نام منطقه را وارد کنید</span>
      </md-field>
      <br/>
      
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
            <l-icon
              :icon-size="[40, 50]"
              :icon-anchor="[20, 50]"
              icon-url="/location_marker.svg" >
            </l-icon>   
          </l-marker>
          <!-- <l-circle-marker ref="location" :lat-lng="location" v-if="location" :radius="8" color="#0043ff" /> -->
        </l-map>
        <small slot="helperText" id="emailHelp" class="form-text text-muted">با گرفتن و رها کردن نشانگر آبی رنگ موقعیت جغرافیایی منطقه را مشخص کنید</small>
      </base-input>
    </template>

  </datatable>
</template>

<script>
import Datatable from '../../components/BaseDatatable.vue'
import BaseTable from '../../components/BaseTable'
import { LMap, LTileLayer, LMarker, LIcon, LControl, LCircleMarker } from 'vue2-leaflet';
import 'leaflet/dist/leaflet.css'

import createMixin from '../../mixins/createMixin'
import initDatatable from '../../mixins/initDatatable'
import Binding, { bind } from '../../mixins/binding'
import { validationMixin } from 'vuelidate'
import { required, maxLength } from 'vuelidate/lib/validators'
import filtersHelper from '../../mixins/filtersHelper';

export default {
  components: {
    BaseTable,
    Datatable,
    LMap, LTileLayer, LMarker, LIcon, LControl, LCircleMarker
  },
  mixins: [
    initDatatable,
    createMixin,
    validationMixin,
    filtersHelper,
    Binding
  ],
  metaInfo: {
    title: 'مناطق شهری',
  },
  data() {
    return {
      plural: 'areas',
      type: 'area',
      group: 'place',
      label: 'منطقه',

      location: { lat: 36.2605, lng: 59.6168 },
      
      zoom: 12,
      center: [36.2605, 59.6168],
      bounds: null,
      location: null,
    }
  },
  validations: {
    name: {
      required,
      maxLength: maxLength(30)
    },
  },
  computed: {
    name: bind('name'),

    getFields() {
      return [
        {
          field: 'name',
          label: 'نام منطقه',
          icon: 'icon-caps-small'
        }, {
          field: 'city',
          label: 'شهر',
          icon: 'icon-square-pin'
        }, {
          field: 'map',
          label: 'موقعیت جغرافیایی',
          icon: 'icon-compass-05'
        },
      ]
    },
    allQuery() {
      return `name coordinates { lat lng } city { id name }`
    }
  },
  methods: {
    zoomUpdated (zoom) {
      this.zoom = zoom
    },
    centerUpdated (center) {
      this.center = center
    },
    boundsUpdated (bounds) {
      this.bounds = bounds
    },
    getVariables()
    {
      return {
        city_id: 130,
        name: this.name,
        lat: this.location.lat,
        lng: this.location.lng,
      }
    },
    afterCreate()
    {
      this.location = { lat: 36.2605, lng: 59.6168 }
      setTimeout(() => this.$refs.map.setCenter({ lat: 36.2605, lng: 59.6168 }), 1000);
    },
    afterEdit(row)
    {
      if ( row.coordinates && row.coordinates.lat )
      {
        this.location = row.coordinates
        setTimeout(() => this.$refs.map.setCenter(row.coordinates), 500);
      }
      else
      {
        this.location = { lat: 36.2605, lng: 59.6168 }
        setTimeout(() => this.$refs.map.setCenter({ lat: 36.2605, lng: 59.6168 }), 1000);
      }
    }
  },
  beforeRouteLeave (to, from, next) {
    this.$refs.datatable.closePanel()

    setTimeout( () => next(), 700);
  },
}
</script>