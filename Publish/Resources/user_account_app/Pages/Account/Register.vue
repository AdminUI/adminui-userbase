<template>
    <h5 class="my-2">Register a new account with {{ name }}</h5>
    <section class="user-registration-container py-3">
        <vue-form
            class="user-registration-form mx-auto my-3"
            @submit="registerAccount"
            v-model:isValid="register.isValid"
        >
            <fieldset class="form-fieldset">
                <vue-input-text
                    class="mb-2"
                    v-model="register.first_name"
                    name="first_name"
                    label="First Name"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.first_name"
                    rules="required|max:50"
                ></vue-input-text>

                <vue-input-text
                    class="mb-2"
                    v-model="register.last_name"
                    name="last_name"
                    label="Surname"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.last_name"
                    rules="required|max:50"
                ></vue-input-text>

                <vue-input-text
                    class="mb-2"
                    v-model="register.email"
                    name="email"
                    label="Email Address"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.email"
                    rules="required|max:100"
                ></vue-input-text>

                <vue-input-text
                    class="mb-2"
                    v-model="register.phone"
                    name="phone"
                    label="Phone Number"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.phone"
                    rules="required|max:100"
                ></vue-input-text>
            </fieldset>

            <h6 class="mb-2 text-left">Address Details</h6>
            <fieldset class="form-fieldset">
                <vue-input-text
                    class="mb-2"
                    v-model="register.nickname"
                    name="nickname"
                    label="Nickname"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.nickname"
                    rules="required"
                    hint="Add a nickname by which this initial address will be known."
                ></vue-input-text>

                <vue-input-text
                    class="mb-2"
                    v-model="register.address1"
                    name="address1"
                    label="Address Line 1"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.address1"
                    rules="required"
                ></vue-input-text>

                <vue-input-text
                    class="mb-2"
                    v-model="register.address2"
                    name="address2"
                    label="Address Line 2"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.address2"
                ></vue-input-text>

                <vue-input-text
                    class="mb-2"
                    v-model="register.town"
                    name="town"
                    label="Town / City"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.town"
                    rules="required"
                ></vue-input-text>

                <vue-input-text
                    class="mb-2"
                    v-model="register.county"
                    name="county"
                    label="County"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.county"
                ></vue-input-text>

                <vue-input-text
                    class="mb-2"
                    v-model="register.postcode"
                    name="postcode"
                    label="Postcode"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.postcode"
                    rules="required"
                ></vue-input-text>
            </fieldset>

            <h6 class="mb-2 text-left">Password</h6>
            <fieldset class="form-fieldset">
                <vue-input-text
                    type="password"
                    class="mb-2"
                    v-model="register.password"
                    name="password"
                    label="Password"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.password"
                    rules="required"
                ></vue-input-text>

                <vue-input-text
                    type="password"
                    class="mb-2"
                    v-model="register.password_confirmation"
                    name="password_confirmation"
                    label="Confirm Password"
                    active-colour="var(--max-red)"
                    floating
                    :error="register.errors.password_confirmation"
                    rules="required"
                ></vue-input-text>
            </fieldset>

            <fieldset class="form-fieldset">
                <div class="d-flex justify-content-between mt-4">
                    <vue-button text tile @click="router.push({ name: 'login' })">Return to Login</vue-button>
                    <vue-button tile type="submit" background="var(--max-red)">Register</vue-button>
                </div>
            </fieldset>
        </vue-form>
    </section>
</template>

<script setup>
import { useSnackbar } from "@dsmdesign/vue3-snackbar";
import { useForm } from "@/js/helpers/form";
import router from "../../router";
import { inject } from "vue";
const name = inject('site-name');
const routes = inject('routes');

const snackbar = useSnackbar();
const register = useForm({
    url: routes.register, method: "POST", fields: {
        first_name: "",
        last_name: "",
        email: "",
        phone: "",
        nickname: "",
        address1: "",
        address2: "",
        town: "",
        county: "",
        postcode: "",
        password: "",
        password_confirmation: ""
    }
})

const registerAccount = async () => {
    try {
        const result = await register.submit();
        console.log(result);

        snackbar.add({
            type: 'success',
            text: 'Please check your e-mail for instructions on how to confirm your registration.'
        })
    }
    catch {
        console.log("Error");
    }
}
</script>

<style lang="scss">
.user-registration-container {
    text-align: center;

    .form-fieldset {
        border: 0;
        padding: 0;
        max-width: 600px;
        margin: 0 auto;
    }
}
</style>