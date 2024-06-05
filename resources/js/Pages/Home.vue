<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { defineProps, reactive } from "vue";

import MobileAppLayout from "@/Layouts/AppLayout.vue";

import BaseLink from "@/Components/Base/BaseLink.vue";
import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";
import AppMapSmall from "@/Components/App/AppMapSmall.vue";
import AppSquareCard from "@/Components/App/AppSquareCard.vue";
import AppNearbySquareCard from "@/Components/App/AppNearbySquareCard.vue";

const props = defineProps({
    routesOrderedRating: {
        type: Object,
        required: true,
    },
});

const routesOrderedRating = reactive(props.routesOrderedRating.data);

const getImgPath = (path) => `/storage/pictures/${path}`;
</script>

<template>
    <Head title="Accueil" />
    <MobileAppLayout>
        <template v-slot:main>
            <BaseSearchBar placeholder="Search for interest points" />
            <div
                class="mt-20 pb-20 w-full px-4 flex flex-col items-center gap-16 overflow-x-scroll"
            >
                <Link :href="route('map')" class="w-full">
                    <div class="card w-full bg-base-100 shadow-xl min-h-56">
                        <div class="card-body p-0">
                            <AppMapSmall />
                        </div>
                    </div>
                </Link>
                <div>
                    <h1
                        class="text-primary text-2xl text-center font-semibold mb-6"
                    >
                        Découvre le canton de Vaud à travers ses sentiers
                    </h1>
                    <p class="text-base text-neutral-content text-center">
                        Explore la richesse culturelle et le patrimoine unique
                        du canton de Vaud. Pars à l’aventure sur des sentiers
                        variés et collectionne des badges uniques pour compléter
                        des familles.
                    </p>
                    <div class="flex flex-col items-center">
                        <BaseLink
                            :href="route('settings.tutorial')"
                            class="text-base underline text-base-300 mt-2"
                            >Comment ça marche ?</BaseLink
                        >
                    </div>
                </div>
                <div
                    class="flex flex-col gap-4 w-full max-h-[70vh] px-3 pb-6 items-center"
                >
                    <div class="flex flex-col gap-10">
                        <!-- TOP 3 -->
                        <div class="flex flex-col gap-4">
                            <h2 class="text-base text-xl font-semibold">
                                Sentiers les plus aimés
                            </h2>
                            <div class="flex flex-row gap-4 items-center">
                                <AppSquareCard
                                    v-if="routesOrderedRating.length"
                                    v-for="routeMostLiked in routesOrderedRating"
                                    :title="routeMostLiked.name"
                                    :href="`/route/${routeMostLiked.uuid}`"
                                    :img-path="
                                        getImgPath(
                                            routeMostLiked.pictures.at(0).path,
                                        )
                                    "
                                    :img-alt="
                                        routeMostLiked.pictures.at(0).title
                                    "
                                />
                            </div>
                        </div>

                        <!-- NEARBY -->
                        <div class="flex flex-col gap-4">
                            <h2 class="text-base text-xl font-semibold">
                                Sentiers à proximité
                            </h2>
                            <AppNearbySquareCard />
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
