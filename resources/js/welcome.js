require('./csrf.js');
import '@mdi/font/css/materialdesignicons.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
Vue.use(Vuetify);
Vue.component('welcome-component', require('./components/WelcomeComponent.vue').default);
new Vue({
  el: '#welcome',
  vuetify: new Vuetify({
  theme: {
    dark: true,
  },
}),
});
