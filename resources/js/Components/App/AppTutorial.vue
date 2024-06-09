<script setup>
import {
    Discover,
    Tick2,
    Location,
    Map,
    LoginVertical,
    Star,
    Sun,
} from "@iconsans/vue/linear";
</script>

<template>
    <div class="px-6 pt-6 flex flex-col gap-5" v-bind="$attrs">
        <div class="flex flex-row gap-5 items-center">
            <div class="h-12 w-12">
                <Discover class="h-12 w-12"></Discover>
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-medium">Les sentiers</p>
                <p class="text-sm">
                    Découvre et choisi sentiers variés, adaptés à tous les
                    niveaux qui te feront découvrir des paysages et des sites
                    uniques.
                </p>
            </div>
        </div>
        <div class="flex flex-row gap-5 items-center">
            <div class="h-12 w-12">
                <Tick2 class="h-12 w-12"></Tick2>
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-medium">Badges</p>
                <p class="text-sm">
                    Collectionne des badges pour chaque sentier parcourus. Les
                    raretés sont bronze, argent et or.
                </p>
            </div>
        </div>
        <div class="flex flex-row gap-5 items-center">
            <div class="h-12 w-12">
                <Location class="h-12 w-12"></Location>
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-medium">Points d’intérêt</p>
                <p class="text-sm">
                    Découvre les points d’intérêt culturels, historiques et
                    naturels le long des sentiers.
                </p>
            </div>
        </div>
        <div class="flex flex-row gap-5 items-center">
            <div class="h-12 w-12">
                <Map class="h-12 w-12"></Map>
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-medium">Carte libre</p>
                <p class="text-sm">
                    Explore librement les points d’intérêts qui t’entourent.
                </p>
            </div>
        </div>
        <div class="flex flex-row gap-5 items-center">
            <div class="h-12 w-12">
                <LoginVertical class="h-12 w-12"></LoginVertical>
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-medium">Téléchargement</p>
                <p class="text-sm">
                    Pas de connexion ? Télécharge les cartes pour une
                    utilisation hors ligne.
                </p>
            </div>
        </div>
        <div class="flex flex-row gap-5 items-center">
            <div class="h-12 w-12">
                <Star class="h-12 w-12"></Star>
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-medium">Favoris</p>
                <p class="text-sm">
                    Sauvegarde et retrouve facilement tes sentiers préférés.
                </p>
            </div>
        </div>
        <div class="flex flex-row gap-5 items-center">
            <div class="h-12 w-12">
                <Sun class="h-12 w-12"></Sun>
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-medium">Météo</p>
                <p class="text-sm">
                    Consulte les prévisions météorologiques pour chaque sentier
                    afin de prévoir tes sorties.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
