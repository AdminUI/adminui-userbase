<template>
    <div class="dynamic-container" style="min-height: 50vh">
        <div class="row no-gutters">
            <div class="col-3 pr-2">
                <dsm-treeview
                    :class="{
                        'is-disabled': !isAuth,
                    }"
                    :items="sidebarOptions"
                    highlight-colour="var(--max-red)"
                    highlight-opacity="0.12"
                    @item-click="controller"
                ></dsm-treeview>
            </div>
            <div class="col-9">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script setup>
import DsmTreeview from "@dsmdesign/vue3-treeview";
import { useRouter } from "vue-router";
import routes from "./router/routes";
import { api } from "@/js/plugins/axios";
import { user } from "@/js/store/account";
import { useSnackbar } from "@dsmdesign/vue3-snackbar";
import { computed, provide } from "vue";

const props = defineProps({
    routes: {
        type: Object,
        default: () => ({}),
    },
    siteName: String,
    primary: {
        type: String,
        default: "steelblue",
    },
    secondary: {
        type: String,
        default: "darkgray",
    },
    reviewsEnabled: {
        type: String,
        default: "0",
    },
});

provide("routes", props.routes);
provide("site-name", props.siteName);
provide("colours", {
    primary: props.primary,
    secondary: props.secondary,
});

const snackbar = useSnackbar();
const router = useRouter();

const isAuth = computed(() => {
    return !!user.value && !!user.value.email;
});

/**
 * @constant {string} currentRoute - Name of the current Vue Router route
 */
const currentRoute = computed(() => router.currentRoute.value.name);

/**
 * @constant {Function} controller - Manages the click events for the menu items
 */
const controller = (item) => {
    if (item.command) item.command();
    else if (item.route) {
        router.push({
            name: item.route,
        });
    }
};

const logout = () => {
    api.get("/user/logout").then((res) => {
        if (res.data.status === true) {
            snackbar.add({
                type: "success",
                text: "You are now logged out. Redirecting...",
            });
            setTimeout(() => {
                window.location.href = "/";
            }, 2000);
        }
    });
};

/**
 * @computed {array} sidebarOptions - Create the User Account sidebar menu from the Vue Router routes.
 */
const sidebarOptions = computed(() => {
    const options = [];
    /**
     * @var {number} index - The current index/id of the menu item
     */
    let index = 1;

    // Filter out non-menu routes and then add the route to the options array
    routes
        .filter((r) => r.meta.menu !== false)
        .forEach((r) => {
            if (r.name === "reviews" && props.reviewsEnabled !== "1") return true;
            // Add the route to the menu
            options.push({
                id: index,
                label: `<div
                        class='
                            dsm__treeview--top-level-menu
                            ${currentRoute.value === r.name ? "active" : ""}
                            '
                    >
                        <i class='mdi mdi-sm mdi-${r.meta.icon} mr-2'></i>
                        ${r.meta.title}
                    </div>`,
                route: r.name,
            });
            // If the route has a divider, add this to the array
            if (r.meta.divider) {
                options.push({ divider: true });
            }
            // Increment the index for the next route
            index++;
        });
    // Add a divider to go before the logout option
    options.push({ divider: true });
    // Add the logout option
    options.push({
        id: index,
        label: `<div class='dsm__treeview--top-level-menu'><i class='mdi mdi-sm mdi-login mr-2'></i>Logout</div>`,
        route: "logout",
    });
    return options;
});
</script>

<style lang="scss">
.dsm__treeview {
    &--container {
        &.is-disabled {
            pointer-events: none;
            opacity: 0.5;
        }
    }

    &--top-level-menu {
        display: flex;
        align-items: center;
        text-transform: uppercase;

        &.active {
            color: var(--max-red);
            font-weight: bold;

            i {
                filter: invert(32%) sepia(82%) saturate(7486%) hue-rotate(356deg) brightness(91%) contrast(96%);
            }
        }

        i {
            position: absolute;
            margin-left: -2.5rem;
        }
    }
}

.user-c-info {
    color: #2196f3;
}
.user-c-success {
    color: #4caf50;
}
.user-c-error {
    color: #ff5252;
}
.user-c-warning {
    color: #ffc107;
}
</style>
