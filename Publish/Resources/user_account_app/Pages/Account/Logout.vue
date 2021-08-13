<template>
    <div class="text-centre my-4">Logging out...</div>
</template>

<script setup>
import router from "../../router";
import { useSnackbar } from "@dsmdesign/vue3-snackbar";
import { api } from "@/js/plugins/axios";
import { user } from "@/js/store/account";
import { inject } from "vue";
const routes = inject("routes");
const snackbar = useSnackbar();


api.post(routes.logout)
    .then((res) => {
        if (res.data.status === true) {
            user.value = null;
            snackbar.add({
                type: 'success',
                text: 'You have been logged out.'
            })
            router.push({ name: "login" });
        }
        else {
            snackbar.add({
                type: 'error',
                text: 'There was a problem logging you out. Please try again later.'
            })
        }
    })
</script>