<script setup>
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import { UserCircle2, Setting } from "@iconsans/vue/linear";

import BaseLink from "@/Components/Base/BaseLink.vue";
import AppBadge from "@/Components/App/AppBadge.vue";
import AppCard from "@/Components/App/AppCardBadge.vue";
</script>

<template>
    <Head title="Collection" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="flex flex-col p-6 w-full h-full">
                <div class="flex flex-row gap-4 justify-end">
                    <UserCircle2 class="h-6 w-6" />
                    <Setting class="h-6 w-6" />
                </div>
                <div class="flex flex-col gap-4 mt-8">
                    <!-- TODO : edit href path -->
                    <AppCard
                        title="Ta collection"
                        :link="{
                            href: '/collection',
                            text: 'voir la collection',
                        }"
                    >
                        <div
                            class="flex flex-row justify-left gap-4 mt-2 flex-wrap"
                        >
                            <AppBadge badge="Régions" :count="2" :total="5" />
                            <AppBadge badge="Villes" :count="2" :total="5" />
                            <AppBadge badge="Châteaux" :count="2" :total="5" />
                        </div>
                    </AppCard>
                    <AppCard title="Tes exploits">
                        <div
                            class="flex flex-row justify-left gap-4 mt-2 flex-wrap"
                        >
                            <AppBadge badge="Distance" />
                            <AppBadge badge="Sentiers parcourus" />
                        </div>
                    </AppCard>
                    <!-- TODO : edit href path -->
                    <AppCard
                        title="Historique"
                        :link="{
                            href: '/lorem',
                            text: 'voir l\'historique',
                        }"
                    >
                    </AppCard>
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
