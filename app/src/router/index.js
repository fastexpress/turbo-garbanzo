import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '../views/index.vue';
import store from '../store';
import RoutesCategories from '../views/categories'
import RoutesPrices from '../views/prices'
import RoutesShipmentUSA from '../views/shipmentsUSA'
import RoutesCollectUSA from '../views/collectUSA'
import RoutesUser from '../views/users'
import RouterRol from '../views/rols'
import RouterPermit from '../views/permits'
import RouterCustomer from '../views/customers'
import RouterPackege from '../views/packages'
import RouterBaler from '../views/balers'
import RouterSetting from '../views/setting'
import RouterAccount from '../views/accounts'
import RouterAccountPersonal from '../views/account-personal'
import RouterCashRegister from '../views/cashregister'
import RouterOfficeUSA from '../views/places'
import RouterOfficeGT from '../views/placesGT'
import RouterBag from '../views/bags';
import RouterTransaction from '../views/transactions'
import RouterBox from '../views/box'
import RouterCharge from '../views/charges'


Vue.use(VueRouter);

const routes = [
    //dashboard
    {
        path: '/', name: 'Home', component: Home, meta: { authRequired: true }
    },
    ...RoutesCategories,
    ...RoutesPrices,
    ...RoutesShipmentUSA,
    ...RoutesCollectUSA,
    ...RoutesUser,
    ...RouterRol,
    ...RouterPermit,
    ...RouterCustomer,
    ...RouterPackege,
    ...RouterBaler,
    ...RouterSetting,
    ...RouterAccount,
    ...RouterCashRegister,
    ...RouterOfficeUSA,
    ...RouterOfficeGT,
    ...RouterBag,
    ...RouterTransaction,
    ...RouterBox,
    ...RouterAccountPersonal,
    ...RouterCharge,
    {
        path: '/dashboard',
        name: 'dashboard',
        component: () => import(/* webpackChunkName: "index2" */ '../views/index.vue'),
        meta: { authRequired: true }
    },
    {
        path: '/auth/login',
        name: 'login',
        component: () => import(/* webpackChunkName: "auth-login" */ '../views/auth/login.vue'),
        meta: { layout: 'auth' }
    },
];

const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { x: 0, y: 0 };
        }
    }
});

router.beforeEach((to, from, next) => {
    if (to.meta && to.meta.layout && to.meta.layout == 'auth') {
        store.commit('setLayout', 'auth');
    } else {
        store.commit('setLayout', 'app');
    }
    // Auth
    if (to.meta.authRequired) {
        if (store.getters.isLoggedIn === false) {
            next({ path: '/auth/login', });
        } else {
            next();
        }
    } else {
        if (to.path === '/auth/login') {
            if (store.getters.isLoggedIn === true) {
                next({ path: '/' })
            }
        }
    }
    next();
});

export default router;
