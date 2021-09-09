<template>
  <div>Order History</div>
</template>

<script setup>
import { api } from "@/js/plugins/axios";
import DataTable from "primevue/datatable/sfc";

api.get("/user/api/orders").then((res) => {
  console.log(res);
});
</script>
