<script setup>
import TheAdminNavBar from "@/Components/The/TheAdminNavBar.vue";
</script>

<template>
    <div
        class="flex flex-col h-dvh sm:justify-center items-center bg-gray-100 pb-16"
    >
        <slot name="main" />
        <TheAdminNavBar />
    </div>
</template>

<style scoped></style>
