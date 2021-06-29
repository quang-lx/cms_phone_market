require('./bootstrap');

window._ = require('lodash');

import Vue from 'vue';
import VueI18n from 'vue-i18n';
import ElementUI from 'element-ui';
import DataTables from 'vue-data-tables';
import VueEvents from 'vue-events';
import locale from 'element-ui/lib/locale/lang/vi';
import VueSimplemde from 'vue-simplemde';
 import 'element-ui/lib/theme-chalk/index.css'
import Locale from './vue-i18n-locales.generated';
import router from './routers';

import ReloadDeleteComponent from './components/utils/ReloadDeleteComponent';
import DeleteComponent from './components/utils/DeleteComponent';
import EditComponent from './components/utils/EditComponent';
import SingleMedia from './components/media/js/components/SingleMedia';
import MediaManager from './components/media/js/components/MediaManager';
import ChatMessages from './components/message/chat_messages';
import NotificationComponent from './components/notification/notification_component';
import Clipboard from 'v-clipboard'

import VueMobileDetection from 'vue-mobile-detection'
Vue.use(VueMobileDetection)
Vue.use(ElementUI, { locale });
Vue.use(DataTables, { locale });
Vue.use(VueI18n);
Vue.use(Clipboard)

Vue.use(require('vue-shortkey'), { prevent: ['input', 'textarea'] });

Vue.use(VueEvents);
Vue.use(VueSimplemde);
require('./mixins');

Vue.component('ReloadDeleteButton', ReloadDeleteComponent);
Vue.component('DeleteButton', DeleteComponent);
Vue.component('EditButton',  EditComponent);
Vue.component('SingleMedia', SingleMedia);
Vue.component('MediaManager', MediaManager);
Vue.component('ChatMessages', ChatMessages);
Vue.component('NotificationComponent', NotificationComponent);

Vue.directive("currency", {
    bind(el, binding, vnode) {
      el.innerHTML =binding.value.toLocaleString('ja-JP')+' đ'
    },
    update(el, binding, vnode) {
      el.innerHTML =binding.value.toLocaleString('ja-JP')+' đ'
    }
});

Vue.directive("number", {
    bind(el, binding, vnode) {
      el.innerHTML = binding.value.toLocaleString()
    },
    update(el, binding, vnode) {
      el.innerHTML = binding.value.toLocaleString()
    }
});

const currentLocale = window.MonCMS.currentLocale;


const i18n = new VueI18n({
    locale: currentLocale,
    messages: Locale
});

const app = new Vue({
    el: '#app',

    router,
    i18n,


});
