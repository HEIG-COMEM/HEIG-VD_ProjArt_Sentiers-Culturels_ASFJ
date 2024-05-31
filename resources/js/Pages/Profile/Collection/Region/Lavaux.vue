<script setup>
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import { ArrowLeft, UserCircle2, Setting } from "@iconsans/vue/linear";

import { Link } from "@inertiajs/vue3";

import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";

import { reactive } from "vue";

const back = () => {
    if (window.history.length > 1) {
        window.history.go(-1);
        return;
    }
    window.history.back();
};

const routesLavaux = reactive([]);
</script>

<template>
    <Head title="Lavaux" />
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
                        <Link :href="route('profile.details')">
                            <UserCircle2 class="h-7 w-7" />
                        </Link>
                        <Link :href="route('profile.settings')">
                            <Setting class="h-7 w-7" />
                        </Link>
                    </div>
                </div>
                <h1 class="text-2xl font-medium">Lavaux</h1>
                <!-- TODO : edit href path, img-path, img-alt -->
                <!-- v-if="routesLavaux.length" -->
                <!-- v-for="route in routesLavaux" -->
                <AppHorizontalCard
                    title="Parcours sentier"
                    tag="Lorem"
                    href="#"
                    img-path="https://img.daisyui.com/images/stock/photo-1494232410401-ad00d5433cfa.jpg"
                    img-alt="lorem"
                    :is-active="true"
                />
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped>
.indicator-item {
    transform: translate(20%, -20%);
}
</style>
