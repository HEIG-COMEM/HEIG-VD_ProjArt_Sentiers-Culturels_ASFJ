<script setup>
import { defineProps } from "vue";
import AppInterestPointShow from "@/Components/App/AppInterestPointShow.vue";
defineProps({
    uuid: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <AppInterestPointShow :uuid />
</template>

<style scoped></style>
