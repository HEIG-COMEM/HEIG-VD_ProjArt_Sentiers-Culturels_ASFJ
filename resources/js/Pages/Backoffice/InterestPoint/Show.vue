<script setup>
import { defineProps, reactive } from "vue";
import { Head } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";

const props = defineProps({
    interestpoint: {
        type: Object,
        required: true,
    },
});

const interestpoint = reactive(props.interestpoint.data);
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeLayout>
        <template v-slot:main>
            <h1>Show {{ interestpoint.uuid }}</h1>
        </template>
    </BackofficeLayout>
</template>

<style scoped></style>
