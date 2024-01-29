<script setup>
import Header from "./Invoice/Header.vue";
</script>
<template>
    <Header />
    <div class="page_not_found">
        Page Not Found
        <router-link to="/" class="routerlink"
            >Back to Invoice HomePage</router-link
        >
    </div>
</template>
