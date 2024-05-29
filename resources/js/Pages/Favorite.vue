<script setup>
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";
import AppNoData from "@/Components/App/AppNoData.vue";

import { reactive } from "vue";

const favorites = reactive([]);
</script>

<template>
    <Head title="Favoris" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="h-full w-full p-6 flex flex-col gap-2">
                <h1 class="text-2xl font-medium">Favoris</h1>
                <!-- TODO : edit href path, img-path, img-alt -->
                <AppHorizontalCard
                    v-if="favorites.length"
                    v-for="favorite in favorites"
                    title="Parcours sentier"
                    tag="Lorem"
                    href="/"
                    img-path="https://img.daisyui.com/images/stock/photo-1494232410401-ad00d5433cfa.jpg"
                    img-alt="lorem"
                    :is-active="true"
                />
                <!-- TODO : edit href path -->
                <AppNoData
                    v-else
                    title="liste de favoris"
                    text="pour commencer à ajouter des
                            sentiers à votre collection !"
                    :call-to-action="{ text: 'découvrir', href: '/lorem' }"
                />
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped>
.indicator-item {
    transform: translate(20%, -20%);
}
</style>
