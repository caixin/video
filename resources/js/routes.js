import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const router = new VueRouter({
  mode: "history",
  routes: [
    {
      path: "/",
      name: "name",
      component: () => import("@/js/components/Home")
    },
    {
      path: "/about",
      name: "about",
      component: () => import("@/js/components/About")
    }
  ]
});

export default router;
