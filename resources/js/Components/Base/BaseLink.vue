<script setup>
import { Link } from "@inertiajs/vue3";
defineProps({
    href: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <Link :href="href" class="link link-primary"><slot /></Link>
</template>

<style scoped></style>
