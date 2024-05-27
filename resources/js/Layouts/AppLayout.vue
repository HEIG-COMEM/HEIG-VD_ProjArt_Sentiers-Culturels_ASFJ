<script setup>
import TheNavBar from "@/Components/The/TheNavBar.vue";
</script>

<template>
    <div class="flex flex-col sm:justify-center items-center bg-gray-100">
        <slot name="main" />
        <TheNavBar />
    </div>
</template>

<style scoped></style>
