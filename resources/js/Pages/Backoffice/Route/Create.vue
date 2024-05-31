<script setup>
import { Head } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeLayout>
        <template v-slot:main>
            <div class="flex flex-col p-6 h-full w-full">
                <h1 class="text-2xl">Créer une route</h1>
            </div>
        </template>
    </BackofficeLayout>
</template>

<style scoped></style>
