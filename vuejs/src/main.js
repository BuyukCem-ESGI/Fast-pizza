import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import { FontAwesomeIcon } from './plugins/font-awesome'
import VueCreditCardValidation from 'vue-credit-card-validation';
import Notifications from '@kyvg/vue3-notification'

createApp(App)
  .use(router)
  .use(store)
  .use(VueCreditCardValidation)
  .use(Notifications)
  .component("font-awesome-icon", FontAwesomeIcon)
  .mount("#app");