<script setup>
import { defineProps } from "vue";
import AppInterestPointShow from "@/Components/App/AppInterestPointShow.vue";
defineProps({
    uuid: {
        type: String,
        required: true,
    },
    auth: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <AppInterestPointShow :uuid :user="auth" />
</template>

<style scoped></style>
