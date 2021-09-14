<template>
  <section class="container-fluid mt-4">
    <div class="row">
      <div class="col-12">
        <p>
          Use the below form to request return authorisation from
          {{ siteName }}.
        </p>
      </div>
      <div class="col-12">
        <vue-form v-model:isValid="returnForm.isValid" @submit="doSendReturn">
          <vue-input-text
            name="product_name"
            class="mb-2"
            v-model="returnForm.product_name"
            floating
            label="Product Name"
            :active-colour="colours.primary"
            :error="returnForm.errors.product_name"
          ></vue-input-text>
          <vue-input-text
            name="reason"
            class="mb-2"
            v-model="returnForm.reason"
            floating
            label="Reason for Return"
            :active-colour="colours.primary"
            :error="returnForm.errors.reason"
          ></vue-input-text>
          <vue-input-textarea
            name="comments"
            class="mb-2"
            v-model="returnForm.comments"
            floating
            label="Additional Comments"
            :active-colour="colours.primary"
            :error="returnForm.errors.comments"
          ></vue-input-textarea>
          <div class="d-flex justify-content-end">
            <vue-button tile :background="colours.primary" type="submit"
              >Send Returns Request</vue-button
            >
          </div>
        </vue-form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { inject } from "vue";
import { useSnackbar } from "@dsmdesign/vue3-snackbar";
import { useForm } from "@dsmdesign/vue3-helpers";
const snackbar = useSnackbar();
const colours = inject("colours");
const routes = inject("routes");
const siteName = inject("site-name");

const returnForm = useForm({
  url: routes.returns,
  method: "POST",
  fields: {
    product_name: "",
    reason: "",
    comments: "",
  },
});

const doSendReturn = async () => {
  try {
    const result = await returnForm.submit();

    snackbar.add({
      type: "success",
      text: "You request for return authorisation has been sent",
    });
    returnForm.reset();
  } catch (e) {
    console.log(e);
  }
};
</script>
