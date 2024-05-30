<script setup>
import { Head } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeLayout>
        <template v-slot:main>
            <h1>Backoffice</h1>
        </template>
    </BackofficeLayout>
</template>

<style scoped></style>
