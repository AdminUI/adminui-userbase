<template>
    <product-grid route="/user/api/wishlist" :total-products="10" wishlisted-by-default></product-grid>
</template>

<script setup>
import ProductGrid from "@/js/components/ProductGrid";
</script>