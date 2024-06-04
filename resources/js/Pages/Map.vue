<script setup>
import { Head } from "@inertiajs/vue3";
import { computed, defineProps, onMounted, reactive, ref, watch } from "vue";
import { ArrowDown2, CrossCircle } from "@iconsans/vue/linear";

import MobileAppLayout from "@/Layouts/AppLayout.vue";

import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";
import AppMap from "@/Components/App/AppMap.vue";
import AppWeatherForecastCard from "@/Components/App/AppWeatherForecastCard.vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";
import AppNoData from "@/Components/App/AppNoData.vue";

const props = defineProps({
    interestPoints: {
        type: Object,
        required: true,
    },
    tags: {
        type: Object,
        required: true,
    },
});

const interestPoints = reactive(props.interestPoints.data);
const ipforMap = ref([]);
const tags = reactive(props.tags.data);
const selectedTags = reactive([]);
const filteredInterestPoints = computed(() => {
    let res = interestPoints.filter((point) => {
        if (selectedTags.length) {
            return point.tags.some((tag) => selectedTags.includes(tag.id));
        }
        return true;
    });
    ipforMap.value = res;
    return res;
});

const handleTagSelection = (tagId) => {
    if (selectedTags.includes(tagId)) {
        selectedTags.splice(selectedTags.indexOf(tagId), 1);
    } else {
        selectedTags.push(tagId);
    }
};
const hasFiltersApplied = computed(() => {
    if (selectedTags.length) return true;
});
const clearFilters = () => {
    selectedTags.splice(0);
};

const showWeather = ref(false);
const coordinates = ref(null);
const search = ref("");
const searchResults = computed(() => {
    return filteredInterestPoints.value.filter((point) =>
        point.name.toLowerCase().includes(search.value.toLowerCase()),
    );
});

const getImgPath = (path) => `/storage/pictures/${path}`;
const getTagsName = (tags) => tags.map((tag) => tag.name);

onMounted(() => {
    navigator.geolocation.getCurrentPosition((position) => {
        coordinates.value = {
            lat: position.coords.latitude,
            long: position.coords.longitude,
        };
    });
});
</script>

<template>
    <Head title="Carte" />
    <MobileAppLayout>
        <template v-slot:main>
            <BaseSearchBar
                placeholder="Rechercher des points d'intêrets"
                v-model="search"
            />
            <div class="absolute top-20 left-[1rem] z-[1] flex flex-row gap-2">
                <div
                    class="rounded-lg flex flex-row justify-between gap-2 items-center bg-base-200 p-2 text-xs cursor-pointer flex-grow"
                    onclick="tags_modal.showModal()"
                    :class="{
                        'bg-primary': selectedTags.length,
                        'text-primary-content': selectedTags.length,
                    }"
                >
                    Tags
                    {{ selectedTags.length ? `(${selectedTags.length})` : "" }}
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
            <div v-show="search" class="mt-32 h-full min-w-full">
                <div
                    class="flex flex-col gap-4 w-full max-h-[80vh] px-3 pb-12 overflow-x-scroll"
                >
                    <AppHorizontalCard
                        v-for="item in searchResults"
                        :key="item.id"
                        :title="item.name"
                        :img-path="getImgPath(item.pictures.at(0).path)"
                        :img-alt="item.name"
                        :display-is-done="false"
                        :tags="getTagsName(item.tags)"
                        :href="`/interest-point/${item.uuid}`"
                    />
                    <AppNoData
                        v-if="!searchResults.length"
                        title="Aucun résultat"
                    />
                </div>
            </div>
            <template v-if="coordinates">
                <div v-show="!search" class="absolute z-[1] bottom-24 left-6">
                    <button
                        class="btn btn-circle btn-outline btn-primary bg-white"
                        @click="showWeather = !showWeather"
                    >
                        <svg
                            class="w-7 h-7 text-primary"
                            viewBox="0 0 27 22"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M16.4762 7.28375C17.2418 7.01701 18.0468 6.88136 18.8575 6.8825C19.675 6.8825 20.4613 7.01875 21.195 7.26875M7.395 10.5113C7.05317 10.4448 6.70574 10.4113 6.3575 10.4113C3.39875 10.4125 1 12.7825 1 15.7063C1 18.63 3.39875 21 6.3575 21H18.8575C22.8025 21 26 17.84 26 13.9412C26 10.8512 23.9925 8.225 21.195 7.26875M7.395 10.5113C7.10139 9.72696 6.9515 8.89619 6.9525 8.05875C6.9525 4.16 10.15 1 14.095 1C17.77 1 20.7962 3.7425 21.195 7.26875M7.395 10.5113C8.08662 10.6456 8.745 10.9149 9.3325 11.3038L7.395 10.5113Z"
                            />
                            <path
                                d="M16.4762 7.28375C17.2418 7.01701 18.0468 6.88136 18.8575 6.8825C19.675 6.8825 20.4613 7.01875 21.195 7.26875M21.195 7.26875C23.9925 8.225 26 10.8512 26 13.9412C26 17.84 22.8025 21 18.8575 21H6.3575C3.39875 21 1 18.63 1 15.7063C1 12.7825 3.39875 10.4125 6.3575 10.4113C6.70574 10.4113 7.05317 10.4448 7.395 10.5113M21.195 7.26875C20.7963 3.7425 17.77 1 14.095 1C10.15 1 6.9525 4.16 6.9525 8.05875C6.9515 8.89619 7.10139 9.72696 7.395 10.5113M7.395 10.5113C8.08662 10.6456 8.745 10.9149 9.3325 11.3038"
                                stroke="#4c8c2b"
                                stroke-width="1.5"
                                stroke-linecap="round"
                            />
                        </svg>
                    </button>
                </div>
                <AppWeatherForecastCard
                    :lat="46.519962"
                    :long="6.633597"
                    v-model="showWeather"
                />
            </template>
            <AppMap
                v-show="!search"
                :interestPoints="ipforMap"
                class="h-96 w-full"
            />

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

<style scoped></style>
