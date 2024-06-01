<script setup>
import { reactive, ref, computed } from "vue";
import { Head } from "@inertiajs/vue3";
import { ArrowDown2 } from "@iconsans/vue/linear";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";
import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";

const props = defineProps({
    interestpoints: {
        type: Object,
        required: true,
    },
    routes: {
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

const showRoutes = ref(props.displayType === "routes");
const routeSearch = ref("");

const getImgPath = (path) => `/storage/pictures/${path}`;
const getTagsName = (tags) => tags.map((tag) => tag.name);

const filteredRoutes = computed(() => {
    return routes.filter((route) =>
        route.name.toLowerCase().includes(routeSearch.value.toLowerCase()),
    );
});
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
                        <div
                            class="flex flex-row gap-2 w-full overflow-y-scroll"
                        >
                            <div class="badge badge-primary gap-2">
                                <ArrowDown2
                                    class="inline-block w-4 h-4 stroke-current"
                                />
                                success
                            </div>
                        </div>
                    </div>

                    <!-- ROUTES LIST -->
                    <div class="flex flex-col gap-4 w-full">
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
                    </div>
                </div>
            </div>
        </template>
    </BackofficeLayout>
</template>

<style scoped></style>
