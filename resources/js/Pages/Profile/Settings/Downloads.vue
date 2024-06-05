<script setup>
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import {
    ArrowLeft,
    UserCircle2,
    Setting,
    ArrowRight2,
} from "@iconsans/vue/linear";

import { Link } from "@inertiajs/vue3";
import AppNoData from "@/Components/App/AppNoData.vue";

const back = () => {
    window.history.back();
};
</script>

<template>
    <Head title="Téléchargements" />
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
                <h1 class="text-2xl font-medium">Téléchargements</h1>
                <AppNoData
                    title="Les téléchargements ne sont pas encore disponibles"
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
