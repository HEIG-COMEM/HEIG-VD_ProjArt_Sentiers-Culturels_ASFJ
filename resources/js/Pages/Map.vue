<script setup>
import { Head } from "@inertiajs/vue3";
import { defineProps, ref, watch } from "vue";

import MobileAppLayout from "@/Layouts/AppLayout.vue";

import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";
import AppMap from "@/Components/App/AppMap.vue";
import BaseFilter from "@/Components/Base/BaseFilter.vue";

const props = defineProps({
    interestPoints: {
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
    <Head title="Carte" />
    <MobileAppLayout>
        <template v-slot:main>
            <BaseSearchBar placeholder="Search for interest points" />
            <div class="absolute top-20 left-[1rem] z-[1]">
                <BaseFilter
                    filterName="Difficultés"
                    :filterValue="[
                        { key: 'facile', value: 'Facile', checked: true },
                        { key: 'moyen', value: 'Moyen' },
                        { key: 'difficile', value: 'Difficile' },
                    ]"
                />
                <BaseFilter
                    filterName="Tag"
                    :filterValue="[
                        { key: 'facile', value: 'Facile', checked: true },
                        { key: 'moyen', value: 'Moyen', checked: true },
                        { key: 'difficile', value: 'Difficile', checked: true },
                    ]"
                />
            </div>
            <AppMap
                :interestPoints="interestPoints"
                :routes="routes"
                class="h-96 w-full"
            />
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
