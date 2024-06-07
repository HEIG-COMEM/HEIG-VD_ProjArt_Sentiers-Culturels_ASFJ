<script setup>
import { back } from "@/utils/helper";
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import { ArrowLeft, UserCircle2, Setting } from "@iconsans/vue/linear";

import { Link } from "@inertiajs/vue3";

import AppBadgeHorizontalCard from "@/Components/App/AppBadgeHorizontalCard.vue";
import { reactive } from "vue";
import BaseLink from "@/Components/Base/BaseLink.vue";

const props = defineProps({
    badges: {
        type: Object,
        required: true,
    },
});

const badges = reactive(props.badges.data);
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
                <div class="w-full text-sm breadcrumbs min-h-12">
                    <ul>
                        <li>
                            <BaseLink :href="route('profile')">
                                Profile</BaseLink
                            >
                        </li>
                        <li>Collection</li>
                    </ul>
                </div>
                <div
                    class="flex flex-col gap-4 max-h-[90svh] px-2 py-4 overflow-y-scroll"
                >
                    <AppBadgeHorizontalCard
                        v-for="badge in badges"
                        :key="badge.id"
                        :badge="badge.name"
                        :count="badge.owned_children_count"
                        :total="badge.children_count"
                        :icon="badge.is_owned ? badge.icon_path : 'default.svg'"
                        :href="route('profile.collection.show', badge.uuid)"
                    />
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
