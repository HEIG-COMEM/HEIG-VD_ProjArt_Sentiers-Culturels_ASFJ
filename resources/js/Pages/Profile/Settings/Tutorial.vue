<script setup>
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import {
    ArrowLeft,
    UserCircle2,
    Setting,
    Discover,
    Tick2,
    Location,
    Map,
    LoginVertical,
    Star,
    Sun,
} from "@iconsans/vue/linear";

import { Link } from "@inertiajs/vue3";

const back = () => {
    window.history.back();
};
</script>

<template>
    <Head title="Comment ça marche ?" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="h-full w-full p-6 flex flex-col gap-2 overflow-scroll">
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
                <h1 class="text-2xl font-medium">Comment ça marche ?</h1>
                <div
                    class="flex flex-col gap-8 rounded-t-2xl p-6 w-full top-[30vh] shadow-top bg-purple-200 mt-4"
                >
                    <div
                        class="flex flex-row gap-5 items-center justify-between"
                    >
                        <div class="h-12 w-12">
                            <Discover class="h-12 w-12"></Discover>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-lg font-medium">Les sentiers</p>
                            <p class="text-sm">
                                Découvre et choisi sentiers variés, adaptés à
                                tous les niveaux qui te feront découvrir des
                                paysages et des sites uniques.
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex flex-row gap-5 items-center justify-between"
                    >
                        <div class="h-12 w-12">
                            <Tick2 class="h-12 w-12"></Tick2>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-lg font-medium">Badges</p>
                            <p class="text-sm">
                                Collectionne des badges pour chaque sentier
                                parcourus. Les raretés sont bronze, argent et
                                or.
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex flex-row gap-5 items-center justify-between"
                    >
                        <div class="h-12 w-12">
                            <Location class="h-12 w-12"></Location>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-lg font-medium">Points d’intérêt</p>
                            <p class="text-sm">
                                Découvre les points d’intérêt culturels,
                                historiques et naturels le long des sentiers.
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex flex-row gap-5 items-center justify-between"
                    >
                        <div class="h-12 w-12">
                            <Map class="h-12 w-12"></Map>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-lg font-medium">Carte libre</p>
                            <p class="text-sm">
                                Explore librement les points d’intérêts qui
                                t’entourent.
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex flex-row gap-5 items-center justify-between"
                    >
                        <div class="h-12 w-12">
                            <LoginVertical class="h-12 w-12"></LoginVertical>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-lg font-medium">Téléchargement</p>
                            <p class="text-sm">
                                Pas de connexion ? Télécharge les cartes pour
                                une utilisation hors ligne.
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex flex-row gap-5 items-center justify-between"
                    >
                        <div class="h-12 w-12">
                            <Star class="h-12 w-12"></Star>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-lg font-medium">Favoris</p>
                            <p class="text-sm">
                                Sauvegarde et retrouve facilement tes sentiers
                                préférés.
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex flex-row gap-5 items-center justify-between"
                    >
                        <div class="h-12 w-12">
                            <Sun class="h-12 w-12"></Sun>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-lg font-medium">Météo</p>
                            <p class="text-sm">
                                Consulte les prévisions météorologiques pour
                                chaque sentier afin de prévoir tes sorties.
                            </p>
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
