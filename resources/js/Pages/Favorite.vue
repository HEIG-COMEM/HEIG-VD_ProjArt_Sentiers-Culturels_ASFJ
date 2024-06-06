<script setup>
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";
import AppNoData from "@/Components/App/AppNoData.vue";

import { reactive } from "vue";

const props = defineProps({
    routes: {
        type: Object,
        required: false,
    },
});

const favorites = reactive(props.routes.data);

const tagsName = (tags) => tags.map((tag) => tag.name);
const getImgPath = (path) => `/storage/pictures/${path}`;
</script>

<template>
    <Head title="Favoris" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="h-full w-full p-6 flex flex-col gap-2">
                <h1 class="text-2xl font-medium">Favoris</h1>
                <div
                    v-if="favorites.length"
                    class="flex flex-col gap-4 w-full h-full overflow-y-auto px-2 pb-6"
                >
                    <AppHorizontalCard
                        v-for="favorite in favorites"
                        :title="favorite.name"
                        :tags="tagsName(favorite.tags)"
                        :href="route('route.show', favorite.uuid)"
                        :img-path="getImgPath(favorite.pictures.at(0).path)"
                        :img-alt="favorite.name"
                        :is-done="favorite.isDone"
                    />
                </div>
                <AppNoData
                    v-else
                    title="Liste de favoris"
                    text="pour commencer à ajouter des
                            sentiers à votre collection !"
                    :call-to-action="{
                        text: 'découvrir',
                        href: route('discovery'),
                    }"
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
