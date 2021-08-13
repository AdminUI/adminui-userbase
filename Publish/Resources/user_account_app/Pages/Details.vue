<template>
    <section>
        <h5 class="my-2">Update Details</h5>
        <vue-form
            class="account-details-form mx-auto my-3"
            @submit="updateDetails"
            v-model:isValid="details.isValid"
        >
            <vue-input-text
                class="mb-2"
                v-model="details.first_name"
                name="first_name"
                label="First Name"
                :active-colour="colours.primary"
                floating
                :error="details.errors.first_name"
                rules="required|max:50"
            ></vue-input-text>
            <vue-input-text
                class="mb-2"
                v-model="details.last_name"
                name="last_name"
                label="Surname"
                :active-colour="colours.primary"
                floating
                :error="details.errors.last_name"
            ></vue-input-text>
            <vue-input-text
                class="mb-2"
                v-model="details.email"
                name="email"
                label="Email Address"
                :active-colour="colours.primary"
                floating
                :error="details.errors.email"
            ></vue-input-text>
            <div class="d-flex justify-content-centre">
                <vue-button
                    tile
                    :background="colours.primary"
                    type="submit"
                    :loading="details.loading"
                >Update Details</vue-button>
            </div>
        </vue-form>
        <h5 class="my-2">Change Password</h5>
        <vue-form
            class="account-details-form mx-auto my-3"
            @submit="updatePassword"
            v-model:isValid="password.isValid"
        >
            <vue-input-text
                type="password"
                class="mb-2"
                v-model="password.password"
                name="password"
                label="New Password"
                :active-colour="colours.primary"
                floating
                :error="password.errors.password"
            ></vue-input-text>
            <vue-input-text
                type="password"
                class="mb-3"
                v-model="password.password_confirmation"
                name="password_confirmation"
                label="Confirm New Password"
                :active-colour="colours.primary"
                floating
                :error="password.errors.password_confirmation"
            ></vue-input-text>

            <vue-input-text
                type="password"
                class="mb-2"
                v-model="password.old_password"
                name="old_password"
                label="Current Password"
                :active-colour="colours.primary"
                floating
                :error="password.errors.old_password"
            ></vue-input-text>
            <div class="d-flex justify-content-centre">
                <vue-button
                    tile
                    :background="colours.primary"
                    type="submit"
                    :loading="password.loading"
                >Update Password</vue-button>
            </div>
        </vue-form>
    </section>
</template>

<script setup>
import { reactive, inject } from "vue";
import { user } from "@/js/store/account";
import { useSnackbar } from "@dsmdesign/vue3-snackbar";
import { useForm } from "@/js/helpers/form";

const colours = inject('colours');

const snackbar = useSnackbar();

const details = useForm({
    url: "/user/api/profile", method: "PATCH", fields: {
        first_name: user.value.first_name,
        last_name: user.value.last_name,
        email: user.value.email,
    }
})

const password = useForm({
    url: '/user/api/password', method: 'PATCH', fields: {
        password: '',
        password_confirmation: '',
        old_password: '',
        email: user.value.email
    }
});

const updateDetails = async () => {
    try {
        const result = await details.submit();
        console.log(result);

        snackbar.add({
            type: 'success',
            text: 'Your details have been updated'
        })
    }
    catch {
        console.log("Error");
    }

}

const updatePassword = async () => {
    try {
        const result = await password.submit();
        console.log(result);
        snackbar.add({
            type: 'success',
            text: 'Your password has been changed'
        })
    }
    catch {
        console.log("Error");
    }
}
</script>

<style lang="scss" scoped>
.account-details-form {
    max-width: 600px;
}
</style>