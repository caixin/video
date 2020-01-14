import "./bootstrap";
import Vue from "vue";
import Vuetify from "vuetify";

// Route information for Vue Router
import Routes from "@/js/routes.js";

// Component File
import App from "@/js/views/App";

Vue.use(Vuetify);

const vuetify = new Vuetify();
const app = new Vue({
  el: "#app",
  router: Routes,
  vuetify,
  render: h => h(App)
});

export default app;
