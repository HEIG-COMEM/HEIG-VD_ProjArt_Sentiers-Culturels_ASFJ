<script setup>
import { defineProps, reactive } from "vue";
import { Head } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";

const props = defineProps({
    route: {
        type: Object,
        required: true,
    },
});

const route = reactive(props.route.data);
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeLayout>
        <template v-slot:main>
            <h1>Edit {{ route.uuid }}</h1>
        </template>
    </BackofficeLayout>
</template>

<style scoped></style>
