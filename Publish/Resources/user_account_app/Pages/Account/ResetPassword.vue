<template>
    <div class="py-5 reset-form">
        <h5 class="mt-2 mb-3">Set New Password</h5>
        <vue-form
            class="user-registration-form mx-auto my-3"
            @submit="doPasswordChange"
            v-model:isValid="passwordChange.isValid"
        >
            <vue-input-text
                class="mb-2"
                v-model="passwordChange.password"
                name="password"
                label="New Password"
                active-colour="var(--max-red)"
                floating
                :error="passwordChange.errors.password"
                rules="required|max:50"
                type="password"
            ></vue-input-text>

            <vue-input-text
                class="mb-2"
                v-model="passwordChange.password_confirmation"
                name="password_confirmation"
                label="Confirm New Password"
                active-colour="var(--max-red)"
                floating
                :error="passwordChange.errors.password_confirmation"
                rules="required|max:50"
                type="password"
            ></vue-input-text>

            <div class="d-flex justify-content-between mt-4">
                <vue-button text tile @click="router.push({ name: 'login' })">Return to Login</vue-button>
                <vue-button background="var(--max-red)" tile type="submit">Change Password</vue-button>
            </div>
        </vue-form>
    </div>
</template>

<script setup>
import { inject } from "vue";
import { useSnackbar } from "@dsmdesign/vue3-snackbar";
import { useRoute, useRouter } from "vue-router";
import { useForm } from "@/js/helpers/form";
const route = useRoute();
const routes = inject('routes');
const router = useRouter();
const snackbar = useSnackbar();

const token = route.params.token;
const email = route.query.email;

const passwordChange = useForm({
    url: routes.passwordChange, method: "POST", fields: {
        token,
        email,
        password: "",
        password_confirmation: ""
    }
});

const doPasswordChange = async () => {
    try {
        const result = await passwordChange.submit();

        snackbar.add({
            type: 'success',
            text: 'Your password has been changed. Redirecting back to login.'
        })

        setTimeout(() => {
            router.push({ name: 'login' });
        }, 2000)
    }
    catch {
        snackbar.add({
            type: 'error',
            text: 'There was a problem changing your password. Please try again later.'
        })
    }
}
</script>

<style lang="scss" scoped>
.reset-form {
    max-width: 500px;
    margin: 0 auto;
}
</style>