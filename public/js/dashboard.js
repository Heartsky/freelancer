
require('./bootstrap');
window.Vue = require('vue');
Vue.component('dashboard', require('./components/dashboard.vue'));

const app = new Vue({
    el: '#dashboard'
});
console.log("aaaaaaaaaaaaaa");
