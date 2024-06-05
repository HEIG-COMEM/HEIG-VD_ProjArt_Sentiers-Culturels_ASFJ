<script setup>
import { defineProps } from "vue";

import { Head } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";
import AppMap from "@/Components/App/AppMap.vue";

const props = defineProps({
    interestpoints: {
        type: Object,
        required: true,
    },
    routes: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeLayout>
        <template v-slot:main>
            <AppMap
                :interestPoints="interestpoints.data"
                :isBackoffice="true"
                class="h-96 w-full"
            />
        </template>
    </BackofficeLayout>
</template>

<style scoped></style>
