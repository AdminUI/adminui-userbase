<template>
    <div class="py-5 login-form">
        <h5 class="mt-2 mb-3">You are not currently logged in</h5>
        <vue-login-form :route="routes.login" @close="redirect"></vue-login-form>
        <div class="d-flex justify-content-between mt-4">
            <vue-button
                text
                tile
                @click="router.push({ name: 'forgot-password' })"
            >Forgot Your Password</vue-button>
            <vue-button text tile @click="router.push({ name: 'register' })">Register an Account</vue-button>
        </div>
    </div>
</template>

<script setup>
import VueLoginForm from "@/js/components/LoginForm";
import router from "../../router";
import { inject } from "vue";

const routes = inject('routes');

const redirect = () => {
    router.push({ name: "notifications" });
}
</script>

<style lang="scss" scoped>
.login-form {
    max-width: 500px;
    margin: 0 auto;
}
</style>