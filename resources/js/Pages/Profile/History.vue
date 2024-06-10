<script setup>
import { back, getImgPath } from "@/utils/helper";
import { Head, Link } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import { ArrowLeft, UserCircle2, Setting } from "@iconsans/vue/linear";

import AppDatedRouteCard from "@/Components/App/AppDatedRouteCard.vue";
import AppNoData from "@/Components/App/AppNoData.vue";

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

// Create a computed of the histories that group by date not taking into account the time
const groupedHistories = computed(() => {
    return histories.reduce((acc, history) => {
        const date = new Date(history.start_timestamp).toDateString();
        if (!acc[date]) {
            acc[date] = [];
        }
        acc[date].push(history);
        return acc;
    }, {});
});

const tagsName = (tags) => tags.map((tag) => tag.name).slice(0, 4);

const formattedDate = (date) => {
    return new Date(date).toLocaleDateString("fr-FR", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
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
                <div class="flex flex-col gap-8">
                    <div
                        v-if="histories.length"
                        v-for="history in groupedHistories"
                        :key="route.id"
                        class="flex flex-col gap-1"
                    >
                        {{ formattedDate(history.at(0).start_timestamp) }}
                        <p class="text-sm text-base-300"></p>
                        <div class="flex flex-col gap-2">
                            <AppHorizontalCard
                                v-for="h in history"
                                :key="h.id"
                                :title="h.route.name"
                                :tags="tagsName(h.route.tags)"
                                :img-path="
                                    getImgPath(h.route.pictures.at(0).path)
                                "
                                :img-alt="h.route.name"
                                :href="route('route.show', h.route.uuid)"
                                :is-done="true"
                            />
                        </div>
                    </div>
                    <AppNoData
                        v-else
                        title="Historique"
                        text="pour explorer de nouveaux sentiers et retrouver l'historique de vos parcours dans cette section !"
                        :call-to-action="{
                            text: 'la carte',
                            href: '/map',
                        }"
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
