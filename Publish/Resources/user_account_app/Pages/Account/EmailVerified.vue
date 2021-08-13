<template>
    <section class="my-4 text-centre">
        <svg-icon type="mdi" :path="mdiCheckBold" size="96px" style="color: green"></svg-icon>
        <h4 class="position-relative">
            Your email address has been verified. Redirecting to login
            <div class="d-inline-block position-absolute">
                <span v-for="i in dots" :key="i">.</span>
            </div>
        </h4>
    </section>
</template>

<script setup>
import { mdiCheckBold } from "@mdi/js";
import router from "../../router";
import { ref } from "vue";
const dots = ref(0);

const interval = setInterval(() => {
    if (dots.value >= 3) {
        clearInterval(interval);
        router.push({ name: "login" });
    }
    dots.value++;
}, 500)
</script>