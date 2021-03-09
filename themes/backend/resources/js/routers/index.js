import VueRouter from 'vue-router';
import Vue from 'vue';
import _ from 'lodash'
import { Message } from 'element-ui';
import AuthRouters from "./authrouters";
import DashboardRouters from "./dashboard";
import MediaRouters from "./MediaRoutes";


Vue.use(VueRouter);
const currentLocale = window.MonCMS.currentLocale;
const permissions = window.MonCMS.permissions;
const multipleLanguage = window.MonCMS.multipleLanguage;
const router = new VueRouter({
    mode: 'history',
    base: makeBaseUrl(),
    routes: [
        ...AuthRouters,
        ...MediaRouters,
        ...DashboardRouters,


    ],
});
function makeBaseUrl() {
    return '';
    // if (multipleLanguage === 'true' || multipleLanguage === '1') {
    //     return `${currentLocale}`;
    // }
    // return '';
}

router.beforeEach((to, from, next) => {
    const routeName = to.name;
    if (_.find(permissions, function(permission) { return permission.name === routeName })) {
        next();
    } else {
        Message({
            type: 'error',
            message: window.MonCMS.permissionDenied,
        });
        next(false)
    }

})

export default router