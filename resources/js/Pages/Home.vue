<script setup>
import { Head } from "@inertiajs/vue3";
import { defineProps, ref, watch } from "vue";

import MobileAppLayout from "@/Layouts/AppLayout.vue";

import AppMapLibre from "@/Components/App/AppMapLibre.vue";
import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";

const props = defineProps({
    interestPoints: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Accueil" />
    <MobileAppLayout>
        <template v-slot:main>
            <BaseSearchBar placeholder="Search for interest points" />
            <div class="mt-20">
                <div class="card w-96 bg-base-100 shadow-xl min-h-56">
                    <div class="card-body">
                        <AppMapLibre :interestPoints="interestPoints.data" />
                    </div>
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
