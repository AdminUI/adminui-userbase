<template>
    <div class="py-5 forgot-form">
        <h5 class="mt-2 mb-3">Get Password Reminder</h5>

        <vue-input-text v-model="email" outlined floating label="Email Address" name="forgot_email">
            <template #prepend-inner>
                <i class="mdi mdi-sm mdi-email-outline"></i>
            </template>
        </vue-input-text>
        <vue-button
            block
            bold
            background="var(--max-red)"
            @click="sendResetRequest"
            :loading="loading"
        >Send Reset Request</vue-button>
        <div class="mt-3">
            <router-link :to="{ name: 'login' }" custom v-slot="{ navigate }">
                <vue-button tile text @click="navigate" :loading="loading">Return to Login</vue-button>
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { ref, inject } from "vue";
const routes = inject("routes");
import { api } from "@/js/plugins/axios";

import { useSnackbar } from "@dsmdesign/vue3-snackbar";
const snackbar = useSnackbar();

const email = ref(null);
const loading = ref(false);

const sendResetRequest = () => {
    loading.value = true;
    api.post(routes.passwordReset, {
        email: email.value
    })
        .then(res => {
            snackbar.add({
                type: 'success',
                text: 'You should shortly receive password reset instructions in your email inbox if it\'s registered.'
            })
        })
        .finally(() => {
            loading.value = false;
        })
}
</script>

<style lang="scss">
.forgot-form {
    max-width: 500px;
    margin: 0 auto;
}
</style>