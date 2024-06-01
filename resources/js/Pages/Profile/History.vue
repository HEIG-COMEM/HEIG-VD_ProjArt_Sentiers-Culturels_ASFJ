<script setup>
import { Head, Link } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import { ArrowLeft, UserCircle2, Setting } from "@iconsans/vue/linear";

import AppDatedRouteCard from "@/Components/App/AppDatedRouteCard.vue";

import { reactive, computed } from "vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";

const props = defineProps({
    histories: {
        type: Object,
        required: true,
    },
});

console.log(props);
const histories = reactive(props.histories.data);
console.log(histories); // TODO: remove this line
const currentDate = null;

// Create a computed of the histories that group by date not taking into account the time
const groupedHistories = computed(() => {
    return histories.reduce((acc, history) => {
        const date = new Date(history.created_at).toDateString();
        if (!acc[date]) {
            acc[date] = [];
        }
        acc[date].push(history);
        return acc;
    }, {});
});

console.log(groupedHistories.value); // TODO: remove this line

const tagsName = (tags) => tags.map((tag) => tag.name).slice(0, 4);

const url = new URL(window.location.href);
const getImgSrc = (path) => {
    return `${url.origin}/storage/pictures/${path}`;
};

const back = () => {
    window.history.back();
};
</script>

<template>
    <Head title="History" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="h-full w-full p-6 flex flex-col gap-2">
                <div class="flex flex-row justify-between">
                    <div class="rounded-full border-black">
                        <button
                            class="btn btn-circle btn-outline bg-base-100 border-base-200 min-h-0 h-7 w-7"
                            @click="back()"
                        >
                            <ArrowLeft class="w-6 h-6" />
                        </button>
                    </div>
                    <div class="flex flex-row gap-4 justify-end">
                        <Link :href="route('profile.account')">
                            <UserCircle2 class="h-7 w-7" />
                        </Link>
                        <Link :href="route('profile.settings')">
                            <Setting class="h-7 w-7" />
                        </Link>
                    </div>
                </div>
                <h1 class="text-2xl font-medium">Historique</h1>
                <!-- TODO : edit href path, img-path, img-alt -->
                <!-- v-if="routesHistory.length" -->
                <!-- v-for="route in routesHistory" -->
                <div class="flex flex-col gap-6">
                    <template v-for="history in histories" :key="route.id">
                        <h2 class="text-xl font-medium">{{ history }}</h2>
                        <AppHorizontalCard
                            :imgPath="
                                getImgSrc(history.route.pictures.at(0).path)
                            "
                            :imgAlt="history.route.name"
                            :is-done="true"
                            :title="history.route.name"
                            :tags="tagsName(history.route.tags)"
                            :href="route('route.show', history.route.uuid)"
                        />
                    </template>

                    <AppDatedRouteCard
                        date="Lundi - 27 mai 2024"
                        title="Parcours sentier"
                        tag="Lorem"
                        href="#"
                        img-path="https://img.daisyui.com/images/stock/photo-1494232410401-ad00d5433cfa.jpg"
                        img-alt="lorem"
                        :is-active="true"
                    />
                    <AppDatedRouteCard
                        date=""
                        title="Parcours sentier"
                        tag="Lorem"
                        href="#"
                        img-path="https://img.daisyui.com/images/stock/photo-1494232410401-ad00d5433cfa.jpg"
                        img-alt="lorem"
                        :is-active="true"
                    />
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped>
.indicator-item {
    transform: translate(20%, -20%);
}
</style>
