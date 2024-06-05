<script setup>
import { defineProps, reactive } from "vue";
import { Head } from "@inertiajs/vue3";

import MobileAppLayout from "@/Layouts/AppLayout.vue";
import AppMapGo from "@/Components/App/AppMapGo.vue";
const props = defineProps({
    route: {
        type: Object,
        required: true,
    },
    resume: {
        type: Boolean,
        default: false,
        required: false,
    },
});
const route = reactive(props.route.data);
</script>

<template>
    <Head title="Sentier" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="h-[100vh] w-[100vw]">
                <AppMapGo :route="route" />
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
