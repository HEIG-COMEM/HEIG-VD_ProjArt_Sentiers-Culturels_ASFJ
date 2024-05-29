<script setup>
import { defineProps, onMounted, reactive, ref } from "vue";
import { ArrowLeft } from "@iconsans/vue/linear";

import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

import AppHorizontalCard from "./AppHorizontalCard.vue";

const props = defineProps({
    uuid: {
        type: String,
        required: true,
    },
});

const interestPoint = reactive({});
const isLoading = ref(true);
const error = ref(null);

const url = new URL(window.location.href);

onMounted(() => {
    const API_ENDPOINT = `${url.origin}/api/interest-point/${props.uuid}`;
    console.log(API_ENDPOINT);
    fetch(API_ENDPOINT)
        .then((response) => response.json())
        .then((data) => {
            interestPoint.value = data.data;
            isLoading.value = false;
        });
});

const getImgSrc = (path) => {
    return `${url.origin}/storage/pictures/${path}`;
};

const back = () => {
    window.history.back();
};
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
            <div v-if="isLoading" class="w-full h-32 skeleton"></div>
            <!-- TODO: Improve Design -->
            <div v-else class="z-0 w-full">
                <div class="h-[40vh] w-full fixed -z-10 top-0">
                    <img
                        class="w-full h-full object-cover"
                        :src="
                            getImgSrc(interestPoint.value.pictures.at(0).path)
                        "
                        :alt="interestPoint.value.pictures.at(0).title"
                    />
                </div>
                <div
                    class="mt-[35vh] sm:mt-[65vh] pb-20 rounded-t-xl bg-base-100 p-6 flex flex-col gap-8"
                >
                    <div>
                        <h1 class="text-2xl font-semibold mb-4">
                            {{ interestPoint.value.name }}
                        </h1>
                        <p>
                            {{ interestPoint.value.description }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl">Sentiers liés</h3>
                        <div class="flex flex-col gap-2">
                            <AppHorizontalCard
                                v-for="sentier in interestPoint.value.routes"
                                :title="sentier.name"
                                tag="Sentier"
                                :imgPath="
                                    getImgSrc(sentier.pictures.at(0).path)
                                "
                                :imgAlt="sentier.pictures.at(0).title"
                                :href="`/route/${sentier.uuid}`"
                                :isDone="sentier.isDone"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<style scoped></style>
