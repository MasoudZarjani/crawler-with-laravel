require('./bootstrap');

window.Vue = require('vue');

import Vuetify from 'vuetify'
import VueRouter from 'vue-router'
import fa from 'vuetify/es5/locale/fa'
import {
    loadProgressBar
} from 'axios-progress-bar'

loadProgressBar()

Vue.config.productionTip = false;
Vue.use(VueRouter)
Vue.use(Vuetify, {
    theme: {
        primary: '#02C286',
        secondary: '#FFFFFF',
        accent: '#FFFFFF',
        error: '#FF5252',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FFC107',
    },
    customProperties: true,
    iconfont: 'md',
    rtl: true,
    lang: {
        locales: {
            fa
        },
        current: 'fa'
    },
})

Vue.component('home', require('./components/index.vue'));

const routes = [
    
]

const router = new VueRouter({
    routes: routes,
    linkActiveClass: 'list__tile--active',
    //mode: 'history'
});

const app = new Vue({
    el: '#app',
    data() {
        return {
            routes: routes,
        }
    },
    router,
});
