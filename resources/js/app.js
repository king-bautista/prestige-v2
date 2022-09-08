/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('admin-users', require('./components/Admin/Users.vue').default);
Vue.component('admin-roles', require('./components/Admin/Roles.vue').default);
Vue.component('admin-modules', require('./components/Admin/Modules.vue').default);
Vue.component('admin-categories', require('./components/Admin/Categories.vue').default);
Vue.component('admin-supplementals', require('./components/Admin/Supplementals.vue').default);
Vue.component('admin-classifications', require('./components/Admin/Classifications.vue').default);
Vue.component('admin-amenities', require('./components/Admin/Amenities.vue').default);
Vue.component('admin-tags', require('./components/Admin/Tags.vue').default);
Vue.component('admin-brands', require('./components/Admin/Brands.vue').default);
Vue.component('admin-products', require('./components/Admin/Products.vue').default);
Vue.component('admin-sites', require('./components/Admin/Sites.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
