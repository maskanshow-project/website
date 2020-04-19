import Vue from "vue";

import {
  Menu,
  MenuItem,
  Submenu,
  Tooltip,
  Switch,
  Pagination,
  Tree,
  Popover,
  Select,
  Option,
  OptionGroup,
  Progress,
  Dropdown,
  Input,
  DropdownItem,
  DropdownMenu,
  Dialog,
  ColorPicker,
  Upload,
  Button,
  Form,
  FormItem,
  Transfer,
  Checkbox,
  Table,
  TableColumn,
  CheckboxGroup,
  CheckboxButton
} from "element-ui";
import lang from "element-ui/lib/locale/lang/fa";
import locale from "element-ui/lib/locale";
import "element-ui/lib/theme-chalk/index.css";

locale.use(lang);

Vue.component(Menu.name, Menu);
Vue.component(MenuItem.name, MenuItem);
Vue.component(Submenu.name, Submenu);
Vue.component(Tooltip.name, Tooltip);
Vue.component(Tree.name, Tree);
Vue.component(Switch.name, Switch);
Vue.component(Pagination.name, Pagination);
Vue.component(Popover.name, Popover);
Vue.component(Progress.name, Progress);
Vue.component(Table.name, Table);
Vue.component(TableColumn.name, TableColumn);
Vue.component(Select.name, Select);
Vue.component(Option.name, Option);
Vue.component(Input.name, Input);
Vue.component(Button.name, Button);
Vue.component(Dialog.name, Dialog);
Vue.component(OptionGroup.name, OptionGroup);
Vue.component(Dropdown.name, Dropdown);
Vue.component(DropdownItem.name, DropdownItem);
Vue.component(DropdownMenu.name, DropdownMenu);
Vue.component(ColorPicker.name, ColorPicker);
Vue.component(Form.name, Form);
Vue.component(FormItem.name, FormItem);
Vue.component(Upload.name, Upload);
Vue.component(Checkbox.name, Checkbox);
Vue.component(CheckboxGroup.name, CheckboxGroup);
Vue.component(CheckboxButton.name, CheckboxButton);
Vue.component(Transfer.name, Transfer);

import "vue-material-design-icons/styles.css";
import MenuIcon from "vue-material-design-icons/Menu.vue";
Vue.component("menu-icon", MenuIcon);

import Vue2Filters from "vue2-filters";
Vue.use(Vue2Filters);

import VueMaterial from "vue-material";
import "vue-material/dist/vue-material.min.css";
Vue.use(VueMaterial);

import CKEditor from "@ckeditor/ckeditor5-vue";
Vue.use(CKEditor);

import VueGeolocation from "vue-browser-geolocation";
Vue.use(VueGeolocation);
