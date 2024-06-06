<script setup>
import { Head } from "@inertiajs/vue3";
import { reactive, ref, computed } from "vue";
import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";

import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";

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
});

const routes = reactive(props.routes.data);
const interestpoints = reactive(props.interestpoints.data);
const difficulties = reactive(props.difficulties.data);
const tags = reactive(props.tags.data);

const showRoutes = ref(props.displayType === "routes");
const routeSearch = ref("");
const interestpointSearch = ref("");

const selectedDifficulty = ref(null);
const selectedTags = reactive([]);

const getImgPath = (path) => `/storage/pictures/${path}`;
const getTagsName = (tags) => tags.map((tag) => tag.name);

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
                route.tags.some((routeTag) =>
                    selectedTags.includes(routeTag.id),
                ),
            );
        });
    }

    return filter;
});

const filteredInterestPoints = computed(() => {
    let filter = interestpoints.filter((interestpoint) =>
        interestpoint.name
            .toLowerCase()
            .includes(interestpointSearch.value.toLowerCase()),
    );

    if (selectedTags.length) {
        selectedTags.forEach((tag) => {
            filter = filter.filter((route) =>
                route.tags.some((routeTag) =>
                    selectedTags.includes(routeTag.id),
                ),
            );
        });
    }

    return filter;
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
    <Head title="Backoffice" />
    <BackofficeLayout>
        <template v-slot:main>
            <div class="flex flex-col gap-8 p-6 h-full w-full max-w-sm">
                <!-- TOGGLE MENU -->
                <div class="flex flex-row justify-center w-full">
                    <div
                        class="flex flex-col gap-1 w-40 cursor-pointer text-center"
                        :class="{
                            'text-primary': showRoutes,
                            'text-base-200': !showRoutes,
                        }"
                        @click="showRoutes = true"
                    >
                        <p class="pl-6 pr-4 font-medium">Sentiers</p>
                        <span
                            class="w-full h-1 rounded-l-full"
                            :class="{
                                'bg-primary': showRoutes,
                                'bg-base-200': !showRoutes,
                            }"
                        ></span>
                    </div>
                    <div
                        class="flex flex-col gap-1 w-40 cursor-pointer text-center"
                        :class="{
                            'text-primary': !showRoutes,
                            'text-base-200': showRoutes,
                        }"
                        @click="showRoutes = false"
                    >
                        <p class="pl-4 pr-6 font-medium">Points d'intérêt</p>
                        <span
                            class="w-full h-1 rounded-r-full"
                            :class="{
                                'bg-primary': !showRoutes,
                                'bg-base-200': showRoutes,
                            }"
                        ></span>
                    </div>
                </div>

                <!-- ROUTES -->
                <div
                    v-show="showRoutes"
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
                            :href="`/backoffice/routes/${route.uuid}`"
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
                    v-show="!showRoutes"
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
                            :tags="getTagsName(interestpoint.tags)"
                            :img-path="
                                getImgPath(interestpoint.pictures.at(0).path)
                            "
                            :img-alt="interestpoint.name"
                            :display-is-done="false"
                            :href="
                                route(
                                    'backoffice.interest-points.show',
                                    interestpoint.uuid,
                                )
                            "
                        />
                    </div>
                </div>
            </div>

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
    </BackofficeLayout>
</template>

<style scoped></style>
