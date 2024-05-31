<script setup>
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import { UserCircle2, Setting } from "@iconsans/vue/linear";

import AppBadge from "@/Components/App/AppBadge.vue";
import AppBadgeCard from "@/Components/App/AppBadgeCard.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Profil" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="flex flex-col p-6 w-full h-full overflow-scroll">
                <div class="flex flex-row gap-4 justify-end">
                    <Link :href="route('profile.account')">
                        <UserCircle2 class="h-7 w-7" />
                    </Link>
                    <Link :href="route('profile.settings')">
                        <Setting class="h-7 w-7" />
                    </Link>
                </div>
                <div class="flex flex-col gap-4 mt-2">
                    <h1 class="text-2xl font-medium">Profil</h1>
                    <!-- TODO : edit href path -->
                    <AppBadgeCard
                        title="Collection"
                        :link="{
                            href: route('profile.collection'),
                            text: 'voir la collection',
                        }"
                    >
                        <div
                            class="flex flex-row justify-left gap-4 mt-2 flex-wrap"
                        >
                            <AppBadge badge="Régions" :count="2" :total="5" />
                            <AppBadge badge="Villes" :count="2" :total="5" />
                            <AppBadge badge="Châteaux" :count="2" :total="5" />
                            <AppBadge
                                badge="Architecture"
                                :count="1"
                                :total="2"
                            />
                        </div>
                    </AppBadgeCard>
                    <AppBadgeCard
                        title="Historique"
                        :link="{
                            href: route('profile.history'),
                            text: 'voir l\'historique',
                        }"
                    >
                    </AppBadgeCard>
                    <AppBadgeCard title="Exploits">
                        <div
                            class="flex flex-row justify-left gap-4 mt-2 flex-wrap"
                        >
                            <AppBadge badge="Distance" />
                            <AppBadge badge="Sentiers parcourus" />
                        </div>
                    </AppBadgeCard>
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
