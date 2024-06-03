<script setup>
import { Head, Link } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import { UserCircle2, Setting } from "@iconsans/vue/linear";

import AppBadge from "@/Components/App/AppBadge.vue";
import AppBadgeCard from "@/Components/App/AppBadgeCard.vue";
import { reactive } from "vue";

const props = defineProps({
    collection: {
        type: Object,
        required: true,
    },
});

const collectionBadges = reactive(props.collection.data);
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
                            <AppBadge
                                v-for="badge in collectionBadges"
                                :key="badge.id"
                                :badge="badge.name"
                                :count="badge.owned_children_count"
                                :total="badge.children_count"
                                :icon="
                                    badge.is_owned ? badge.icon : 'default.svg'
                                "
                                :href="
                                    route('profile.collection.show', badge.uuid)
                                "
                            />
                        </div>
                    </AppBadgeCard>
                    <AppBadgeCard title="Exploits">
                        <div
                            class="flex flex-row justify-left gap-4 mt-2 flex-wrap"
                        >
                            <AppBadge badge="Distance" />
                            <AppBadge badge="Sentiers parcourus" />
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
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
