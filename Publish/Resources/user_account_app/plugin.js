import { defineAsyncComponent } from "vue";

const asyncSetup = () => {
    return new Promise((resolve, reject) => {
        const isUserPage = /^\/user\/account/i.test(window.location.pathname);

        if (isUserPage === false) return resolve(null);
        else {
            import(/* webpackChunkName: "user_account_app" */ "./router").then(
                (res) => {
                    const UserAccount = defineAsyncComponent(() =>
                        import(/* webpackChunkName: "user_account_app" */ "./index.vue")
                    );

                    resolve({
                        install: function (app, config) {
                            app.component("vue-user-account-app", UserAccount);
                            app.use(res.default);
                        },
                    });
                }
            );
        }
    });
};

export default asyncSetup;
