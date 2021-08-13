import { createRouter, createWebHistory } from "vue-router";
import { user } from "@/js/store/account";
import routes from "./routes";
const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else
            return {
                top: 0,
                behavior: "smooth",
            };
    },
});

router.beforeEach((to, from) => {
    const isAuth = !!user.value && !!user.value.email;
    if (to.meta.auth !== false && isAuth === false) {
        return { name: "login" };
    } else if (to.meta.auth === false && isAuth === true) {
        return { name: "notifications" };
    }

    if (to.meta.title) document.title = `${to.meta.title} | Max Motorcycles`;
});

export default router;
