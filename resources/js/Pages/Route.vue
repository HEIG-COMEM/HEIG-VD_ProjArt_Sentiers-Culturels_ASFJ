<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";
import {
    ArrowLeft,
    Star,
    LoginVertical as Download,
    Sun,
} from "@iconsans/vue/linear";
import { Star as StarFull } from "@iconsans/vue/bold";

import MobileAppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    route: {
        type: Object,
        required: true,
    },
});
const bgImgPath = `/storage/pictures/${props.route.pictures.at(0).path}`;

const isFavorite = ref(false); // TODO: Bind with user favorites
const showWeather = ref(false);

const back = () => {
    if (window.history.length > 1) {
        window.history.go(-1);
        return;
    }
    window.history.back();
};
</script>

<template>
    <Head title="Sentier" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="absolute z-[1] p-6 top-0 left-0 w-full">
                <div class="flex flex-row justify-between">
                    <div>
                        <button
                            class="btn btn-circle btn-outline btn-primary bg-base-100 border-none"
                            @click="back()"
                        >
                            <ArrowLeft class="w-7 h-7" />
                        </button>
                    </div>
                    <div class="flex flex-col gap-2">
                        <button
                            class="btn btn-circle btn-outline btn-primary bg-base-100 border-none"
                            @click="back()"
                        >
                            <component
                                :is="isFavorite ? StarFull : Star"
                                class="w-7 h-7"
                            />
                        </button>
                        <button
                            class="btn btn-circle btn-outline btn-primary bg-base-100 border-none"
                            @click="back()"
                        >
                            <Download class="w-7 h-7" />
                        </button>
                        <button
                            class="btn btn-circle btn-outline btn-primary bg-base-100 border-none"
                            @click="back()"
                        >
                            <Sun
                                class="w-7 h-7"
                                @click="showWeather = !showWeather"
                            />
                        </button>
                    </div>
                </div>
            </div>
            <div
                class="background-image w-full h-full bg-primary flex flex-col justify-center"
            >
                <div class="backdrop-blur-md w-full h-full">
                    <div
                        class="flex flex-col justify-center items-center h-full gap-8 p-16"
                    >
                        <img :src="bgImgPath" class="w-36 h-36 rounded-md" />
                        <div class="flex flex-col items-center">
                            <h1 class="text-2xl font-bold text-base-100">
                                {{ route.name }}
                            </h1>
                            <p
                                class="text-sm font-semibold mt-2 px-4 text-center text-base-100"
                            >
                                {{ route.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped>
.background-image {
    background-image: url("/storage/route/background-route-detail.jpeg");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh;
    width: 100vw;
}
</style>
