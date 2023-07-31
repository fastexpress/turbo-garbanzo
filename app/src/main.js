import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import { register } from 'register-service-worker'
//bootstrap vue
import { BootstrapVue } from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue.css';
Vue.use(BootstrapVue);

//perfect scrollbar
import PerfectScrollbar from 'vue2-perfect-scrollbar';
Vue.use(PerfectScrollbar);

//vue-scrollactive
import VueScrollactive from 'vue-scrollactive';
Vue.use(VueScrollactive);

//vue-meta
import VueMeta from 'vue-meta';
Vue.use(VueMeta, {
    refreshOnceOnNavigation: true
});

//Sweetalert
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
const options = {
    confirmButtonColor: '#4361ee',
    cancelButtonColor: '#e7515a'
};
Vue.use(VueSweetalert2, options);

//portal vue
import PortalVue from 'portal-vue';
Vue.use(PortalVue);

// Mask Vue
import VueMask from "v-mask";
Vue.use(VueMask);

// Vue Loading
import VueLoading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
Vue.use(VueLoading);

// Vue moment
import VueMoment from 'vue-moment'
import moment from 'moment-timezone'

Vue.use(VueMoment, {
    moment,
})

Vue.config.productionTip = false;
// set default settings
import appSetting from './app-setting';
Vue.prototype.$appSetting = appSetting;
appSetting.init();

//REGISTER PWA
register('/serviceworker.js', {
    ready() {
        console.log('Service worker is active.')
    },
    registered() {
        console.log('Service worker has been registered.')
    },
    cached() {
        console.log('Content has been cached for offline use.')
    },
    updatefound() {
        console.log('New content is downloading.')
    },
    updated() {
        console.log('New content is available; please refresh.')
    },
    offline() {
        console.log('No internet connection found. App is running in offline mode.')
    },
    error(error) {
        console.error('Error during service worker registration:', error)
    }
})

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');
