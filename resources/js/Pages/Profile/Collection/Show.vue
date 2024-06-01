<script setup>
import { Head, Link } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import { ArrowLeft, UserCircle2, Setting } from "@iconsans/vue/linear";

import AppBadgeHorizontalCard from "@/Components/App/AppBadgeHorizontalCard.vue";
import BaseLink from "@/Components/Base/BaseLink.vue";
import { reactive } from "vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";

const props = defineProps({
    badge: {
        type: Object,
        required: true,
    },
    breadcrumb: {
        type: Object,
        required: true,
    },
});

const badge = reactive(props.badge.data);
const breadcrumb = reactive(props.breadcrumb.data);

const tagsName = (tags) => tags.map((tag) => tag.name).slice(0, 4);

const url = new URL(window.location.href);
const getImgSrc = (path) => {
    return `${url.origin}/storage/pictures/${path}`;
};

const back = () => {
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
                <h1 class="text-2xl font-medium">{{ badge.name }}</h1>
                <div class="w-full text-sm breadcrumbs">
                    <ul>
                        <li>
                            <BaseLink :href="route('profile')">
                                Profile</BaseLink
                            >
                        </li>
                        <li>
                            <BaseLink :href="route('profile.collection')">
                                Collection</BaseLink
                            >
                        </li>
                        <li v-for="(item, index) in breadcrumb" :key="item.id">
                            <template v-if="index !== breadcrumb.length - 1">
                                <BaseLink
                                    :href="
                                        route(
                                            'profile.collection.show',
                                            item.uuid,
                                        )
                                    "
                                >
                                    {{ item.name }}</BaseLink
                                >
                            </template>
                            <template v-else>
                                {{ item.name }}
                            </template>
                        </li>
                    </ul>
                </div>
                <AppBadgeHorizontalCard
                    v-if="badge.children.length"
                    v-for="badge in badge.children"
                    :key="badge.id"
                    :badge="badge.name"
                    :count="badge.owned_children_count"
                    :total="badge.children_count"
                    :icon="badge.icon_path"
                    :href="route('profile.collection.show', badge.uuid)"
                />
                <template v-else>
                    <div class="w-full flex flex-col gap-4 items-center">
                        <div class="card w-full bg-base-100 shadow-xl">
                            <figure class="px-10 pt-10">
                                <img
                                    :src="`/storage/badges/${badge.icon_path}`"
                                    alt="Shoes"
                                    class="rounded-xl"
                                />
                            </figure>
                            <div class="card-body items-center text-center">
                                <h2 class="card-title">{{ badge.name }}</h2>
                                <p v-if="badge.description">
                                    {{ badge.description }}
                                </p>
                                <div
                                    class="w-full text-left font-semibold mt-6"
                                >
                                    <template v-if="badge.interest_point">
                                        <h3 v-if="badge.is_owned">
                                            Vous avez obtenu ce badge en
                                            visitant le point d'intérêt :
                                        </h3>
                                        <h3 v-else>
                                            Pour obtenir ce badge, visitez le
                                            point d'intérêt :
                                        </h3>
                                        <AppHorizontalCard
                                            v-if="badge.interest_point"
                                            :title="badge.interest_point.name"
                                            :imgPath="
                                                getImgSrc(
                                                    badge.interest_point.pictures.at(
                                                        0,
                                                    ).path,
                                                )
                                            "
                                            :displayIsDone="false"
                                            :imgAlt="badge.interest_point.name"
                                            :href="`/interest-point/${badge.interest_point.uuid}`"
                                        />
                                    </template>
                                    <template v-if="badge.route">
                                        <h3 v-if="badge.is_owned">
                                            Vous avez obtenu ce badge en
                                            terminant le sentier :
                                        </h3>
                                        <h3 v-else>
                                            Pour obtenir ce badge, terminez le
                                            sentier :
                                        </h3>
                                        <AppHorizontalCard
                                            v-if="badge.route"
                                            :title="badge.route.name"
                                            :imgPath="
                                                getImgSrc(
                                                    badge.route.pictures.at(0)
                                                        .path,
                                                )
                                            "
                                            :displayIsDone="false"
                                            :imgAlt="badge.route.name"
                                            :href="`/route/${badge.route.uuid}`"
                                        />
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
