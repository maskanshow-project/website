// GeneralViews
import NotFound from "../pages/NotFoundPage.vue";

// Admin pages
import Dashboard from "../pages/Dashboard.vue";
import SiteSetting from "../pages/Setting/Site.vue";
import UserSetting from "../pages/Setting/User.vue";
import Assignment from "../pages/Feature/Assignment.vue";
import Feature from "../pages/Feature/Feature.vue";
import EstateType from "../pages/Feature/EstateType.vue";
import Specification from "../pages/Feature/Specification.vue";
import SpecificationTable from "../pages/Feature/SpecificationTable.vue";
import Article from "../pages/Blog/Article.vue";
import Comment from "../pages/Blog/Comment.vue";
import Subject from "../pages/Blog/Subject.vue";
import Promocode from "../pages/Financial/Promocode.vue";
import Plan from "../pages/Financial/Plan.vue";
import Payment from "../pages/Financial/Payment.vue";
import Estate from "../pages/Estate/Estate.vue";
import Favorite from "../pages/Estate/Favorite.vue";
import Office from "../pages/Estate/Office.vue";
import MyEstate from "../pages/Estate/MyEstate.vue";
import InactiveEstate from "../pages/Estate/inactiveEstate.vue";
import AssignmentedEstate from "../pages/Estate/assignmentedEstate.vue";
import Label from "../pages/Estate/Label.vue";
import Area from "../pages/Estate/Area.vue";
import Street from "../pages/Estate/Street.vue";
import ManageEstate from "../pages/Estate/ManageEstate.vue";
import User from "../pages/User/User.vue";
import Role from "../pages/User/Role.vue";
import Message from "../pages/User/Message.vue";
import BlacklistPhoneNumber from "../pages/User/BlacklistPhoneNumber.vue";
import BotEstates from "../pages/Bot/BotEstates.vue";
import BotSetting from "../pages/Bot/BotSetting.vue";

const routes = [
  // {
  //   path: "/panel",
  //   redirect: "/panel/article",
  // },
  {
    path: "/panel",
    name: "dashboard",
    component: Dashboard,
    meta: { index: 0, auth: true }
  },
  {
    path: "/panel/assignment",
    name: "مدیریت واگذاری ها",
    component: Assignment,
    meta: { index: "1", auth: true }
  },
  {
    path: "/panel/estate_type",
    name: "مدیریت نوع ملک ها",
    component: EstateType,
    meta: { index: "1", auth: true }
  },
  {
    path: "/panel/feature",
    name: "مدیریت امکانات",
    component: Feature,
    meta: { index: "1", auth: true }
  },
  {
    path: "/panel/specification",
    name: "مدیریت جداول مشخصات",
    component: Specification,
    meta: { index: "1", auth: true }
  },
  {
    path: "/panel/specification/:id",
    name: "مدیریت جدول مشخصات",
    component: SpecificationTable,
    meta: { index: "1", auth: true }
  },
  {
    path: "/panel/article",
    name: "مدیریت مقالات",
    component: Article,
    meta: { index: "2", auth: true }
  },
  {
    path: "/panel/comment",
    name: "مدیریت نظرات",
    component: Comment,
    meta: { index: "2", auth: true }
  },
  {
    path: "/panel/subject",
    name: "مدیریت موضوعات",
    component: Subject,
    meta: { index: "2", auth: true }
  },
  {
    path: "/panel/estate",
    name: "مدیریت املاک",
    component: Estate,
    meta: { index: "3", auth: true }
  },
  {
    path: "/panel/estate/favorite",
    name: "املاک نشان شده",
    component: Favorite,
    meta: { index: "3", auth: true }
  },
  {
    path: "/panel/office",
    name: "دفاتر املاک",
    component: Office,
    meta: { index: "3", auth: true }
  },
  {
    path: "/panel/estate/mine",
    name: "مدیریت املاک من",
    component: MyEstate,
    meta: { index: "3", auth: true }
  },
  {
    path: "/panel/estate/inactive",
    name: "املاک تایید نشده",
    component: InactiveEstate,
    meta: { index: "3", auth: true }
  },
  {
    path: "/panel/estate/assignmented",
    name: "املاک واگذار شده",
    component: AssignmentedEstate,
    meta: { index: "3", auth: true }
  },
  {
    path: "/panel/label",
    name: "مدیریت لیبل املاک",
    component: Label,
    meta: { index: "3", auth: true }
  },
  {
    path: "/panel/area",
    name: "مدیریت مناطق",
    component: Area,
    meta: { index: "3", auth: true }
  },
  {
    path: "/panel/street",
    name: "مدیریت خیابان ها",
    component: Street,
    meta: { index: "3", auth: true }
  },
  {
    path: "/panel/estate/create",
    name: "ثبت ملک جدید",
    component: ManageEstate,
    meta: { index: "3", auth: true }
  },
  {
    path: "/panel/estate/:id/edit",
    name: "ویرایش ملک",
    component: ManageEstate,
    meta: { index: "3", auth: true }
  },
  {
    path: "/panel/promocode",
    name: "کد تخفیف ها",
    component: Promocode,
    meta: { index: "4", auth: true }
  },
  {
    path: "/panel/plan",
    name: "پلن های مالی",
    component: Plan,
    meta: { index: "4", auth: true }
  },
  {
    path: "/panel/payment",
    name: "پرداختی ها",
    component: Payment,
    meta: { index: "4", auth: true }
  },
  {
    path: "/panel/user",
    name: "مدیریت کاربران",
    component: User,
    meta: { index: "5", auth: true }
  },
  {
    path: "/panel/role",
    name: "مدیریت نقش ها",
    component: Role,
    meta: { index: "5", auth: true }
  },
  {
    path: "/panel/blacklist_phone_number",
    name: "شماره تلفن های لیست سیاه",
    component: BlacklistPhoneNumber,
    meta: { index: "5", auth: true }
  },
  {
    path: "/panel/message",
    name: "پیام ها",
    component: Message,
    meta: { index: "5", auth: true }
  },
  {
    path: "/panel/setting/user",
    name: "تنظیمات کاربر",
    component: UserSetting,
    meta: { index: "6", auth: true }
  },
  {
    path: "/panel/setting/site",
    name: "تنظیمات سایت",
    component: SiteSetting,
    meta: { index: "6", auth: true }
  },
  {
    path: "/panel/robot/estates",
    name: "ربات ثبت ملک",
    component: BotEstates,
    meta: { index: "7", auth: true }
  },
  {
    path: "/panel/robot/setting",
    name: "تنظیمات منابع",
    component: BotSetting,
    meta: { index: "7", auth: true }
  },
  { path: "*", component: NotFound }
];

/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * The specified component must be inside the Views folder
 * @param  {string} name  the filename (basename) of the view to load.
function view(name) {
   var res= require('../components/Dashboard/Views/' + name + '.vue');
   return res;
};**/

export default routes;
