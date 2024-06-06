<script setup>
import { Head } from "@inertiajs/vue3";
import { reactive, ref, computed } from "vue";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";
import AppSquareCard from "@/Components/App/AppSquareCard.vue";
import AppNearbySquareCard from "@/Components/App/AppNearbySquareCard.vue";

import { ArrowDown2, CrossCircle } from "@iconsans/vue/linear";

const props = defineProps({
    interestpoints: {
        type: Object,
        required: true,
    },
    routes: {
        type: Object,
        required: true,
    },
    difficulties: {
        type: Object,
        required: true,
    },
    tags: {
        type: Object,
        required: true,
    },
    displayType: {
        type: String,
        required: false,
        default: "routes",
    },
    latestRoutes: {
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
const difficulties = reactive(props.difficulties.data);
const tags = reactive(props.tags.data);
const latestRoutes = reactive(props.latestRoutes.data);
const routesOrderedRating = reactive(props.routesOrderedRating.data);

const showRoutes = ref(1);
const discoverySearch = ref("");
const routeSearch = ref("");
const interestpointSearch = ref("");

const selectedDifficulty = ref(null);
const selectedTags = reactive([]);

const getImgPath = (path) => `/storage/pictures/${path}`;
const getTagsName = (tags) => tags.map((tag) => tag.name);

const discoverySearchResult = computed(() => {
    return [...routes, ...interestpoints].filter((item) =>
        item.name.toLowerCase().includes(discoverySearch.value.toLowerCase()),
    );
});

const filteredRoutes = computed(() => {
    let filter = routes.filter((route) =>
        route.name.toLowerCase().includes(routeSearch.value.toLowerCase()),
    );

    if (selectedDifficulty.value) {
        filter = filter.filter(
            (route) => route.difficulty_id === selectedDifficulty.value,
        );
    }

    if (selectedTags.length) {
        selectedTags.forEach((tag) => {
            filter = filter.filter((route) =>
                route.tags.some((routeTag) => routeTag.id === tag),
            );
        });
    }

    return filter;
});

const filteredInterestPoints = computed(() => {
    return interestpoints.filter((interestpoint) =>
        interestpoint.name
            .toLowerCase()
            .includes(interestpointSearch.value.toLowerCase()),
    );
});

const handleTagSelection = (tagId) => {
    if (selectedTags.includes(tagId)) {
        selectedTags.splice(selectedTags.indexOf(tagId), 1);
    } else {
        selectedTags.push(tagId);
    }
};

const hasFiltersApplied = computed(() => {
    if (selectedDifficulty.value || selectedTags.length) return true;
});

const clearFilters = () => {
    selectedDifficulty.value = null;
    selectedTags.splice(0);
};
</script>

<template>
    <Head title="Découverte" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="h-full w-full p-6 flex flex-col gap-2">
                <!-- TOGGLE MENU -->
                <div class="flex flex-row justify-center w-full">
                    <div
                        class="flex flex-col gap-1 w-25 cursor-pointer text-center"
                        :class="{
                            'text-primary': showRoutes === 1,
                            'text-base-200': showRoutes !== 1,
                        }"
                        @click="showRoutes = 1"
                    >
                        <p class="pl-5 pr-3 font-medium">Découverte</p>
                        <span
                            class="w-full h-1 rounded-l-full"
                            :class="{
                                'bg-primary': showRoutes === 1,
                                'bg-base-200': showRoutes !== 1,
                            }"
                        ></span>
                    </div>
                    <div
                        class="flex flex-col gap-1 w-25 cursor-pointer text-center"
                        :class="{
                            'text-primary': showRoutes === 2,
                            'text-base-200': showRoutes !== 2,
                        }"
                        @click="showRoutes = 2"
                    >
                        <p class="px-3 font-medium">Sentiers</p>
                        <span
                            class="w-full h-1"
                            :class="{
                                'bg-primary': showRoutes === 2,
                                'bg-base-200': showRoutes !== 2,
                            }"
                        ></span>
                    </div>
                    <div
                        class="flex flex-col gap-1 w-25 cursor-pointer text-center"
                        :class="{
                            'text-primary': showRoutes === 3,
                            'text-base-200': showRoutes !== 3,
                        }"
                        @click="showRoutes = 3"
                    >
                        <p class="pl-3 pr-5 font-medium">Points d'intérêt</p>
                        <span
                            class="w-full h-1 rounded-r-full"
                            :class="{
                                'bg-primary': showRoutes === 3,
                                'bg-base-200': showRoutes !== 3,
                            }"
                        ></span>
                    </div>
                </div>

                <!-- DISCOVERY -->
                <div
                    v-show="showRoutes === 1"
                    class="flex flex-col gap-8 justify-center w-full"
                >
                    <!-- FILTER SECTION -->
                    <div
                        class="flex flex-col justify-center w-full gap-4 self-center"
                    >
                        <BaseSearchBar
                            :is-absolute="false"
                            placeholder="Rechercher"
                            class="w-full"
                            v-model="discoverySearch"
                        />
                    </div>
                    <template v-if="!discoverySearch">
                        <!-- DISCOVERY LIST -->
                        <div
                            class="flex flex-col gap-4 w-full max-h-[70vh] px-3 pb-6 items-center"
                        >
                            <div class="flex flex-col gap-10">
                                <!-- TOP 3 -->
                                <div class="flex flex-col gap-4">
                                    <h2 class="text-lg font-semibold">
                                        Les mieux notés
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
                                    <h2 class="text-lg font-semibold">
                                        A proximité
                                    </h2>
                                    <AppNearbySquareCard />
                                </div>

                                <!-- NEW ONES -->
                                <div class="flex flex-col gap-4">
                                    <h2 class="text-lg font-semibold">
                                        Les plus récents
                                    </h2>
                                    <div
                                        class="flex flex-row gap-4 items-center"
                                    >
                                        <AppSquareCard
                                            v-if="latestRoutes.length"
                                            v-for="latestRoute in latestRoutes"
                                            :title="latestRoute.name"
                                            :href="`/route/${latestRoute.uuid}`"
                                            :img-path="
                                                getImgPath(
                                                    latestRoute.pictures.at(0)
                                                        .path,
                                                )
                                            "
                                            :img-alt="
                                                latestRoute.pictures.at(0).title
                                            "
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div
                            class="flex flex-col gap-4 w-full max-h-[71vh] px-3 pb-3 overflow-x-scroll"
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
                    </template>
                </div>

                <!-- ROUTES -->
                <div
                    v-show="showRoutes === 2"
                    class="flex flex-col gap-8 justify-center w-full"
                >
                    <!-- FILTER SECTION -->
                    <div class="flex flex-col justify-center w-full gap-4">
                        <BaseSearchBar
                            :is-absolute="false"
                            placeholder="Rechercher"
                            class="w-full"
                            v-model="routeSearch"
                        />

                        <div class="flex flex-row gap-2 w-full">
                            <div
                                class="rounded-lg flex flex-row justify-between gap-2 items-center bg-base-200 p-2 text-xs cursor-pointer flex-grow"
                                onclick="difficulty_modal.showModal()"
                                :class="{
                                    'bg-primary': selectedDifficulty,
                                    'text-primary-content': selectedDifficulty,
                                }"
                            >
                                Difficulté
                                <span class="h-4 w-4"
                                    ><ArrowDown2 class="h-full w-full"
                                /></span>
                            </div>
                            <div
                                class="rounded-lg flex flex-row justify-between gap-2 items-center bg-base-200 p-2 text-xs cursor-pointer flex-grow"
                                onclick="tags_modal.showModal()"
                                :class="{
                                    'bg-primary': selectedTags.length,
                                    'text-primary-content': selectedTags.length,
                                }"
                            >
                                Tags
                                {{
                                    selectedTags.length
                                        ? `(${selectedTags.length})`
                                        : ""
                                }}
                                <span class="h-4 w-4"
                                    ><ArrowDown2 class="h-full w-full"
                                /></span>
                            </div>
                            <div
                                v-show="hasFiltersApplied"
                                class="rounded-lg bg-base-200 p-2 text-xs cursor-pointer"
                                @click="clearFilters()"
                            >
                                <span class="h-4 w-4"
                                    ><CrossCircle class="h-full w-full"
                                /></span>
                            </div>
                        </div>
                    </div>

                    <!-- ROUTES LIST -->
                    <div
                        class="flex flex-col gap-4 w-full max-h-[70vh] px-3 pb-6 overflow-x-scroll"
                    >
                        <AppHorizontalCard
                            v-for="route in filteredRoutes"
                            :key="route.id"
                            :img-path="getImgPath(route.pictures.at(0).path)"
                            :img-alt="route.name"
                            :display-is-done="false"
                            :title="route.name"
                            :tags="getTagsName(route.tags)"
                            :href="`/route/${route.uuid}`"
                        />
                        <p
                            v-if="filteredRoutes.length === 0"
                            class="text-center text-base-200"
                        >
                            Aucun résultat
                        </p>
                    </div>
                </div>

                <!-- INTEREST POINTS -->
                <div
                    v-show="showRoutes === 3"
                    class="flex flex-col gap-8 justify-center w-full"
                >
                    <!-- FILTER SECTION -->
                    <div class="flex flex-col justify-center w-full gap-4">
                        <BaseSearchBar
                            :is-absolute="false"
                            placeholder="Rechercher"
                            class="w-full"
                            v-model="interestpointSearch"
                        />
                    </div>

                    <!-- INTEREST POINTS LIST -->
                    <div
                        class="flex flex-col gap-4 w-full max-h-[70vh] px-3 pb-6 overflow-x-scroll"
                    >
                        <AppHorizontalCard
                            v-for="interestpoint in filteredInterestPoints"
                            :key="interestpoint.id"
                            :title="interestpoint.name"
                            :img-path="
                                getImgPath(interestpoint.pictures.at(0).path)
                            "
                            :img-alt="interestpoint.name"
                            :display-is-done="false"
                            :href="`/interest-point/${interestpoint.uuid}`"
                        />
                    </div>
                </div>
            </div>

            <!-- DIFFICULTY MODAL SECTION -->
            <dialog id="difficulty_modal" class="modal">
                <div class="modal-box">
                    <h3 class="font-bold text-lg">Difficultés</h3>
                    <div class="py-4">
                        <div
                            v-for="difficulty in difficulties"
                            :key="difficulty.id"
                            class="flex flex-row gap-2 items-center mb-2"
                            @click="selectedDifficulty = difficulty.id"
                        >
                            <input
                                type="radio"
                                :checked="selectedDifficulty === difficulty.id"
                                class="radio"
                                name="difficulty"
                            />
                            <label :for="difficulty.id">{{
                                difficulty.name
                            }}</label>
                        </div>
                    </div>
                    <form method="dialog" class="flex justify-end">
                        <button
                            class="btn btn-primary"
                            @click="validateSelection"
                        >
                            Valider
                        </button>
                    </form>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>

            <!-- TAGS MODAL SECTION -->
            <dialog id="tags_modal" class="modal">
                <div class="modal-box">
                    <h3 class="font-bold text-lg mb-2">Tags</h3>
                    <div class="py-4 max-h-[70vh] overflow-x-scroll">
                        <div
                            v-for="tag in tags"
                            :key="tag.id"
                            class="flex flex-row gap-2 items-center mb-2 px-2"
                            @click="handleTagSelection(tag.id)"
                        >
                            <input
                                type="checkbox"
                                :checked="selectedTags.includes(tag.id)"
                                class="checkbox"
                                name="tag"
                            />
                            <label :for="tag.id">{{ tag.name }}</label>
                        </div>
                    </div>
                    <form method="dialog" class="flex justify-end">
                        <button
                            class="btn btn-primary"
                            @click="validateSelection"
                        >
                            Valider
                        </button>
                    </form>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>
        </template>
    </MobileAppLayout>
</template>

<style scoped>
.indicator-item {
    transform: translate(20%, -20%);
}
</style>
