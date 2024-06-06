<script setup>
import { getImgPath } from "@/utils/helper";
import { Head, Link } from "@inertiajs/vue3";
import { defineProps, reactive, ref, computed } from "vue";

import MobileAppLayout from "@/Layouts/AppLayout.vue";

import BaseLink from "@/Components/Base/BaseLink.vue";
import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";
import AppMapSmall from "@/Components/App/AppMapSmall.vue";
import AppSquareCard from "@/Components/App/AppSquareCard.vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";
import AppNearbySquareCard from "@/Components/App/AppNearbySquareCard.vue";

const props = defineProps({
    routes: {
        type: Object,
        required: true,
    },
    interestpoints: {
        type: Object,
        required: true,
    },
    routesOrderedRating: {
        type: Object,
        required: true,
    },
});

const routes = reactive(props.routes.data);
const interestpoints = reactive(props.interestpoints.data);
const routesOrderedRating = reactive(props.routesOrderedRating.data);

const discoverySearch = ref("");

const discoverySearchResult = computed(() => {
    return [...routes, ...interestpoints].filter((item) =>
        item.name.toLowerCase().includes(discoverySearch.value.toLowerCase()),
    );
});
</script>

<template>
    <Head title="Accueil" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="h-full w-full flex flex-col gap-2">
                <!-- FILTER SECTION -->
                <div class="flex flex-col w-full gap-4 p-6">
                    <BaseSearchBar
                        :is-absolute="false"
                        placeholder="Rechercher"
                        class="w-full"
                        v-model="discoverySearch"
                    />
                </div>
                <template v-if="!discoverySearch">
                    <div
                        class="w-full px-4 flex flex-col items-center gap-16 overflow-x-scroll"
                    >
                        <Link :href="route('map')" class="w-full">
                            <div
                                class="card w-full bg-base-100 shadow-xl min-h-56"
                            >
                                <div class="card-body p-0">
                                    <AppMapSmall />
                                </div>
                            </div>
                        </Link>
                        <div>
                            <h1
                                class="text-primary text-2xl text-center font-semibold mb-6"
                            >
                                Découvre le canton de Vaud à travers ses
                                sentiers
                            </h1>
                            <p
                                class="text-base text-neutral-content text-center"
                            >
                                Explore la richesse culturelle et le patrimoine
                                unique du canton de Vaud. Pars à l’aventure sur
                                des sentiers variés et collectionne des badges
                                uniques pour compléter des familles.
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
                                    <h2 class="text-xl font-semibold">
                                        Les plus aimés
                                    </h2>
                                    <div
                                        class="flex flex-row gap-4 items-center"
                                    >
                                        <AppSquareCard
                                            v-if="routesOrderedRating.length"
                                            v-for="routeMostLiked in routesOrderedRating"
                                            :title="routeMostLiked.name"
                                            :href="`/route/${routeMostLiked.uuid}`"
                                            :img-path="
                                                getImgPath(
                                                    routeMostLiked.pictures.at(
                                                        0,
                                                    ).path,
                                                )
                                            "
                                            :img-alt="
                                                routeMostLiked.pictures.at(0)
                                                    .title
                                            "
                                        />
                                    </div>
                                </div>

                                <!-- NEARBY -->
                                <div class="flex flex-col gap-4">
                                    <h2 class="text-xl font-semibold">
                                        A proximité
                                    </h2>
                                    <AppNearbySquareCard />
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div
                        class="px-4 flex flex-col items-center overflow-x-scroll"
                    >
                        <div
                            class="flex flex-col gap-4 w-full max-h-[75vh] px-3 pb-3 overflow-x-scroll"
                        >
                            <AppHorizontalCard
                                v-for="item in discoverySearchResult"
                                :key="item.id"
                                :title="item.name"
                                :img-path="getImgPath(item.pictures.at(0).path)"
                                :img-alt="item.name"
                                :display-is-done="false"
                                :href="
                                    item.type === 'route'
                                        ? `/route/${item.uuid}`
                                        : `/interest-point/${item.uuid}`
                                "
                            />
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
