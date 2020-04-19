<template>
  <component :is="tag"
             @click.native="hideSidebar"
             class="nav-item"
             v-bind="$attrs"
             tag="li">
    <a class="nav-link">
      <slot>
        <i v-if="icon" :class="icon"></i>
        <p>
          {{name}}
          <transition name="fade">
            <span v-if="alert !== 0" class="link-alert badge badge-danger mr-2">{{ alert }}</span>
          </transition>
        </p>
      </slot>
    </a>
  </component>
</template>
<script>
export default {
  name: "sidebar-link",
  inheritAttrs: false,
  inject: {
    autoClose: {
      default: true
    },
    addLink: {
      default: ()=>{}
    },
    removeLink: {
      default: ()=>{}
    }
  },
  props: {
    name: String,
    icon: String,
    alert: {
      type: Number,
      default: () => 0 
    },
    tag: {
      type: String,
      default: "router-link"
    },
  },
  methods: {
    hideSidebar() {
      if (this.autoClose) {
        this.$sidebar.displaySidebar(false);
      }
    },
    isActive() {
      return this.$el.classList.contains("active");
    }
  },
  mounted() {
    if (this.addLink) {
      this.addLink(this);
    }
  },
  beforeDestroy() {
    if (this.$el && this.$el.parentNode) {
      this.$el.parentNode.removeChild(this.$el)
    }
    if (this.removeLink) {
      this.removeLink(this);
    }
  }
};
</script>

<style>

.link-alert.badge {
  border-radius: 13px;
  color: #fff !important;
  padding: 4px 5px 3px;
  background: #ff3d3d;
  box-shadow: 0px 3px 10px -4px #ff3d3d, 0px 3px 6px -5px #000;
}

</style>