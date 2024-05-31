<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout>
        <template v-slot:main>
            <slot name="content" />
        </template>
    </AppLayout>
</template>

<style scoped></style>
