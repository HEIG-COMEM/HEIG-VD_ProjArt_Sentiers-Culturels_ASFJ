<script setup>
import { getImgPath, getTagsName } from "@/utils/helper";
import { computed, defineProps, onMounted, reactive, ref, watch } from "vue";
import { ArrowDown2, CrossCircle } from "@iconsans/vue/linear";

import { Head } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";
import AppMap from "@/Components/App/AppMap.vue";
import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";
import AppNoData from "@/Components/App/AppNoData.vue";

const props = defineProps({
    interestpoints: {
        type: Object,
        required: true,
    },
    tags: {
        type: Object,
        required: true,
    },
});

const interestPoints = reactive(props.interestpoints.data);
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

const search = ref("");
const searchResults = computed(() => {
    return filteredInterestPoints.value.filter((point) =>
        point.name.toLowerCase().includes(search.value.toLowerCase()),
    );
});
</script>

<template>
    <Head title="Carte" />
    <BackofficeLayout>
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
                        :href="
                            route('backoffice.interest-points.show', item.uuid)
                        "
                    />
                    <AppNoData
                        v-if="!searchResults.length"
                        title="Aucun résultat"
                    />
                </div>
            </div>
            <AppMap
                v-show="!search"
                :interestPoints="ipforMap"
                :isBackoffice="true"
                :autoFlyToUserLocation="false"
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
    </BackofficeLayout>
</template>

<style scoped></style>
