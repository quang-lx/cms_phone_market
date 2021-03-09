/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue'
import VueLazyLoad from 'vue-lazyload'
import VueI18n from 'vue-i18n'
import VueRouter from 'vue-router'
import VueEvents from 'vue-events'
import FeRoutes from './routes/index'

require('./bootstrap')

Vue.use(VueI18n)
Vue.use(VueRouter)
Vue.use(VueEvents)

require("./mixins")

const currentLocale = window.MonCMS.locale
const messages = {
  [currentLocale]: window.MonCMS.translations
}

const i18n = new VueI18n({
  locale: currentLocale,
  messages
})
const router = new VueRouter({
  mode: 'history',
  base: window.Vma.locale_prefix,
  routes: [
    ...FeRoutes
  ]
})

Vue.use(VueLazyLoad, {
  preLoad: 1.3,
  lazyComponent: true
})

const app = new Vue({
  el: '#app',
  router,
  i18n
})
