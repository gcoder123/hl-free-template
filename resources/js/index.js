import Vue from "vue";
import VueRouter from "vue-router";
import VueCarousel from "vue-carousel";
import App from "./App";
import routes from "./routes";
import "./components";

Vue.use(VueRouter);
Vue.use(VueCarousel);

const router = new VueRouter({
    mode: "history",
    routes: routes,
});

new Vue({
    router,
    render: h => h(App)
}).$mount("#root");
