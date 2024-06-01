<script setup>
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import { ArrowLeft, UserCircle2, Setting } from "@iconsans/vue/linear";

import { Link } from "@inertiajs/vue3";

import AppBadgeHorizontalCard from "@/Components/App/AppBadgeHorizontalCard.vue";

const back = () => {
    if (window.history.length > 1) {
        window.history.go(-1);
        return;
    }
    window.history.back();
};
</script>

<template>
    <Head title="Collection" />
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
                <h1 class="text-2xl font-medium">Collection</h1>
                <!-- TODO : edit href path and badge icon ?-->
                <AppBadgeHorizontalCard
                    badge="Régions"
                    :count="2"
                    :total="5"
                    :href="route('profile.collection.region')"
                />
                <AppBadgeHorizontalCard
                    badge="Villes"
                    :count="2"
                    :total="5"
                    :href="route('profile.collection.city')"
                />
                <AppBadgeHorizontalCard
                    badge="Châteaux"
                    :count="2"
                    :total="5"
                    :href="route('profile.collection.castle')"
                />
                <AppBadgeHorizontalCard
                    badge="Architecture"
                    :count="1"
                    :total="2"
                    :href="route('profile.collection.architecture')"
                />
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
