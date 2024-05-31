<script setup>
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import { ArrowLeft, UserCircle2, Setting, Pencil } from "@iconsans/vue/linear";

import { Link } from "@inertiajs/vue3";

const back = () => {
    if (window.history.length > 1) {
        window.history.go(-1);
        return;
    }
    window.history.back();
};
</script>

<template>
    <Head title="Détails" />
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
                <h1 class="text-2xl font-medium">Détails</h1>
                <!-- TODO : edit href path, img-path, img-alt -->
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col gap-4 items-center">
                        <div class="avatar">
                            <div
                                class="w-24 rounded-full border border-base-300"
                            >
                                <img
                                    src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg"
                                />
                            </div>
                        </div>
                        <div
                            class="flex flex-row items-center justify-between gap-4"
                        >
                            <h2 class="text-lg font-medium">Manuel Neuer</h2>
                            <Pencil class="h-5 w-5" />
                        </div>
                    </div>
                    <!-- TODO : Div should take full width <-> -->
                    <div
                        class="flex flex-col gap-5 rounded-t-2xl p-6 pb-20 w-full top-[30vh] shadow-top bg-purple-200"
                    >
                        <div
                            class="flex flex-col gap-1 border-b border-base-300"
                        >
                            <span class="text-sm text-base-300">Prénom</span>
                            <p>Manuel</p>
                        </div>
                        <div
                            class="flex flex-col gap-1 border-b border-base-300"
                        >
                            <span class="text-sm text-base-300">Nom</span>
                            <p>Neuer</p>
                        </div>
                        <div
                            class="flex flex-col gap-1 border-b border-base-300"
                        >
                            <span class="text-sm text-base-300">Email</span>
                            <p>manuel.neuer@gmail.com</p>
                        </div>
                        <div
                            class="flex flex-col gap-1 border-b border-base-300"
                        >
                            <span class="text-sm text-base-300"
                                >Mot de passe</span
                            >
                            <p>**********</p>
                        </div>
                    </div>
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
