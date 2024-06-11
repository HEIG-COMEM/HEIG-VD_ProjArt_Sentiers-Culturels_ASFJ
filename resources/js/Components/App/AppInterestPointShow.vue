<script setup>
import { back, getImgPath } from "@/utils/helper";
import { defineProps, onMounted, reactive, ref } from "vue";
import { ArrowLeft } from "@iconsans/vue/linear";

import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

import AppHorizontalCard from "./AppHorizontalCard.vue";
import AppError from "./AppError.vue";
import BaseLink from "../Base/BaseLink.vue";

const props = defineProps({
    uuid: {
        type: String,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
});

const interestPoint = reactive({});
const auth = reactive(props.user);
const isLoading = ref(true);
const error = ref(null);

const url = new URL(window.location.href);

const fetchData = () => {
    const API_ENDPOINT = `${url.origin}/api/interest-point/${props.uuid}`;
    fetch(API_ENDPOINT)
        .then((response) => response.json())
        .then((data) => {
            interestPoint.value = data.data;
            isLoading.value = false;
        })
        .catch((err) => {
            error.value = err;
            isLoading.value = false;
        });
};

onMounted(() => {
    fetchData();
});

const handleClaimBadge = () => {
    const API_ENDPOINT = `${url.origin}/api/interest-point/${props.uuid}/claim-badge`;
    // get the user location
    navigator.geolocation.getCurrentPosition((position) => {
        const { latitude, longitude } = position.coords;
        fetch(API_ENDPOINT, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify({
                lat: latitude,
                lng: longitude,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    throw new Error(data.error);
                }
                isLoading.value = true;
                fetchData();
            })
            .catch((err) => {
                const alertTarget = document.getElementById("alert-target");
                alertTarget.innerHTML = `
                <div role="alert" class="alert alert-error">
                              <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>${err.message}</span>
                        </div>`;
                setTimeout(() => {
                    alertTarget.innerHTML = "";
                }, 5000);
            });
    });
};

const tagsName = (tags) => tags.map((tag) => tag.name).slice(0, 4);
</script>

<template>
    <Head title="Point d'interet" />
    <AppLayout>
        <template v-slot:main>
            <!-- TOP BTNS -->
            <div class="fixed z-[1] p-6 top-0 left-0 w-full">
                <div class="flex flex-row justify-between">
                    <div>
                        <button
                            class="btn btn-circle btn-outline btn-primary bg-base-100"
                            @click="back()"
                        >
                            <ArrowLeft class="w-7 h-7" />
                        </button>
                    </div>
                </div>
            </div>

            <div
                id="alert-target"
                class="absolute z-10 w-full px-6 top-6"
            ></div>

            <div v-if="isLoading" class="w-full h-32 skeleton"></div>
            <div v-else-if="error" class="w-full h-full">
                <AppError title="Oups !">
                    <template v-slot:content>
                        Une erreur est survenue.<br />
                        Retour à l'
                        <BaseLink :href="route('home')">accueil</BaseLink>.
                    </template>
                </AppError>
            </div>
            <div v-else class="z-0 w-full h-full">
                <div class="h-[40vh] w-full fixed -z-10 top-0">
                    <img
                        class="w-full h-full object-cover"
                        :src="
                            getImgPath(interestPoint.value.pictures.at(0).path)
                        "
                        :alt="interestPoint.value.pictures.at(0).title"
                    />
                </div>
                <div
                    class="relative mt-[30vh] h-[70vh] pb-20 rounded-t-xl bg-base-100 p-6 flex flex-col gap-8 shadow-top"
                >
                    <div
                        v-if="interestPoint.value.badge"
                        class="absolute top-[-35px] right-8 bg-base-100 rounded-full p-2 shadow-2xl"
                    >
                        <img
                            class="rounded-full h-full w-full object-cover"
                            :src="`/storage/badges/${interestPoint.value.badge.isDone ? interestPoint.value.badge.icon_path : 'default.svg'}`"
                        />
                    </div>
                    <div class="flex flex-col gap-4 h-full">
                        <div>
                            <span
                                v-for="(tags, index) in interestPoint.value
                                    .tags"
                                class="text-sm text-base-300"
                                >{{ tags.name
                                }}<template
                                    v-if="
                                        index !==
                                        interestPoint.value.tags.length - 1
                                    "
                                    >,
                                </template>
                            </span>
                            <h1 class="text-2xl font-semibold mb-4">
                                {{ interestPoint.value.name }}
                            </h1>
                        </div>
                        <div
                            class="flex flex-col gap-8 h-full overflow-auto pb-6"
                        >
                            <p>
                                {{ interestPoint.value.description }}
                            </p>
                            <div
                                v-if="interestPoint.value.badge"
                                class="flex flex-col gap-2 flex-grow"
                            >
                                <p class="text-lg font-bold">Badge</p>
                                <div class="flex flex-col items-center">
                                    <div
                                        class="border border-neutral-200 w-20 h-20 bg-neutral-100 shadow-md rounded-md flex items-center justify-center mb-2"
                                    >
                                        <img
                                            :src="`/storage/badges/${interestPoint.value.badge.icon_path}`"
                                        />
                                    </div>
                                    <div class="max-w-45 text-center">
                                        <p class="truncate overflow-hidden">
                                            {{ interestPoint.value.badge.name }}
                                        </p>
                                        <p
                                            v-if="
                                                interestPoint.value.badge.isDone
                                            "
                                            class="text-xs text-base-300"
                                        >
                                            Vous avez déjà collecté ce badge
                                        </p>
                                    </div>
                                </div>
                                <template v-if="auth.user">
                                    <button
                                        v-if="!interestPoint.value.badge.isDone"
                                        class="btn btn-primary"
                                        @click="handleClaimBadge()"
                                    >
                                        Collecter le badge
                                    </button>
                                </template>
                                <template v-else>
                                    <a
                                        class="btn btn-primary"
                                        :href="route('login')"
                                    >
                                        Connectez-vous pour collecter le badge
                                    </a>
                                </template>
                            </div>
                            <div v-if="interestPoint.value.routes.length">
                                <p class="text-xl font-bold">Sentiers liés</p>
                                <div class="flex flex-col gap-2 px-2">
                                    <AppHorizontalCard
                                        v-for="sentier in interestPoint.value
                                            .routes"
                                        :title="sentier.name"
                                        :tags="tagsName(sentier.tags)"
                                        :imgPath="
                                            getImgPath(
                                                sentier.pictures.at(0).path,
                                            )
                                        "
                                        :imgAlt="sentier.pictures.at(0).title"
                                        :href="`/route/${sentier.uuid}`"
                                        :isDone="sentier.isDone"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<style scoped></style>
