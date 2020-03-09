require('./csrf.js');
import '@mdi/font/css/materialdesignicons.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
Vue.use(Vuetify);
Vue.component('register-component', require('./components/RegisterComponent.vue').default);
new Vue({
    el: '#register',
    vuetify: new Vuetify(),
});
