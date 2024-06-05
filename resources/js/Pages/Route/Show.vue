<script setup>
import { Head, Link } from "@inertiajs/vue3";
import {
    computed,
    defineProps,
    onMounted,
    onUnmounted,
    reactive,
    ref,
    watch,
} from "vue";
import {
    ArrowLeft,
    ArrowDown,
    Star,
    LoginVertical as Download,
    Sun,
    Clock3,
    Activity,
    MenuHamburger3,
    Tag,
    Tick2,
} from "@iconsans/vue/linear";
import { Star as StarFull } from "@iconsans/vue/bold";

import MobileAppLayout from "@/Layouts/AppLayout.vue";
import AppStarRating from "@/Components/App/AppStarRating.vue";
import AppWeatherForecastCard from "@/Components/App/AppWeatherForecastCard.vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";

const props = defineProps({
    route: {
        type: Object,
        required: true,
    },
    auth: {
        type: Object,
        required: true,
    },
});

const route = ref(props.route);
const auth = ref(props.auth);
const isAuth = computed(() => auth.value.user !== null);
console.log(isAuth.value);

const getImgPath = (path) => `/storage/pictures/${path}`;
const bgImgPath = computed(() => getImgPath(route.value.pictures.at(0).path));

const duration = computed(() => {
    return Math.floor(route.value.duration / 3600) +
        " h " +
        (route.value.duration % 3600) / 60
        ? Math.floor((route.value.duration % 3600) / 60) + " min"
        : "";
});

const distance = computed(() => {
    return route.value.length > 1000
        ? (route.value.length / 1000).toFixed(2) + " km"
        : route.value.length + " m";
});

const rates = ref(route.value.rates);

const rate = computed(() => {
    return (
        rates.value.reduce((acc, rate) => acc + rate.rate, 0) /
        rates.value.length
    );
});

const roundedRate = computed(() => {
    const res = (Math.round(rate.value * 2) / 2).toFixed(1);
    return isNaN(res) ? "-" : res;
});

const interestPoints = reactive(route.value.interest_points);

const isFavorite = ref(route.value.isFavorite);
const showWeather = ref(false);
const toggleMenuFirstActive = ref(true);

const back = () => {
    window.history.back();
};

const toggleFav = () => {
    const API_ENDPOINT = `/api/favorite/${route.value.uuid}`;

    fetch(API_ENDPOINT).then((response) => {
        if (response.ok) {
            isFavorite.value = !isFavorite.value;
        } else {
            console.error("Error while toggling favorite");
        }
    });
};

const download = () => {
    // TODO: Implement download
    console.error("Download not implemented yet");
};

const currentIndex = ref(1);
const observer = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const index = Array.from(
                    entry.target.parentNode.children,
                ).indexOf(entry.target);

                currentIndex.value = index + 1;
            }
        });
    },
    {
        root: document.querySelector(".scroll-container"),
        rootMargin: "0px",
        threshold: 0.5,
    },
);

onMounted(() => {
    const children = document.querySelectorAll(".scroll-container > *");

    children.forEach((child) => {
        observer.observe(child);
    });
});

onUnmounted(() => {
    const children = document.querySelectorAll(".scroll-container > *");

    children.forEach((child) => {
        observer.unobserve(child);
    });
});
</script>

<template>
    <Head title="Sentier" />
    <MobileAppLayout>
        <template v-slot:main>
            <!-- WEATHER -->
            <AppWeatherForecastCard
                :lat="+route.start_lat"
                :long="+route.start_long"
                v-model="showWeather"
            />

            <!-- TOP BTNS -->
            <div class="absolute z-[1] p-6 top-0 left-0 w-full">
                <div class="flex flex-row justify-between">
                    <div>
                        <button
                            class="btn btn-circle btn-outline btn-primary bg-base-100 border-none h-6"
                            @click="back()"
                        >
                            <ArrowLeft class="w-7 h-7" />
                        </button>
                    </div>
                    <div class="flex flex-row gap-2">
                        <button
                            v-if="isAuth"
                            class="btn btn-circle btn-outline btn-primary bg-base-100 border-none h-6"
                            @click="toggleFav()"
                        >
                            <component
                                :is="isFavorite ? StarFull : Star"
                                class="w-7 h-7"
                            />
                        </button>
                        <button
                            class="btn btn-circle btn-outline btn-primary bg-base-100 border-none h-6"
                            @click="download()"
                        >
                            <Download class="w-7 h-7" />
                        </button>
                        <button
                            class="btn btn-circle btn-outline btn-primary bg-base-100 border-none h-6"
                            @click="showWeather = !showWeather"
                        >
                            <svg
                                class="w-7 h-7 text-primary"
                                viewBox="0 0 27 22"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M16.4762 7.28375C17.2418 7.01701 18.0468 6.88136 18.8575 6.8825C19.675 6.8825 20.4613 7.01875 21.195 7.26875M7.395 10.5113C7.05317 10.4448 6.70574 10.4113 6.3575 10.4113C3.39875 10.4125 1 12.7825 1 15.7063C1 18.63 3.39875 21 6.3575 21H18.8575C22.8025 21 26 17.84 26 13.9412C26 10.8512 23.9925 8.225 21.195 7.26875M7.395 10.5113C7.10139 9.72696 6.9515 8.89619 6.9525 8.05875C6.9525 4.16 10.15 1 14.095 1C17.77 1 20.7962 3.7425 21.195 7.26875M7.395 10.5113C8.08662 10.6456 8.745 10.9149 9.3325 11.3038L7.395 10.5113Z"
                                />
                                <path
                                    d="M16.4762 7.28375C17.2418 7.01701 18.0468 6.88136 18.8575 6.8825C19.675 6.8825 20.4613 7.01875 21.195 7.26875M21.195 7.26875C23.9925 8.225 26 10.8512 26 13.9412C26 17.84 22.8025 21 18.8575 21H6.3575C3.39875 21 1 18.63 1 15.7063C1 12.7825 3.39875 10.4125 6.3575 10.4113C6.70574 10.4113 7.05317 10.4448 7.395 10.5113M21.195 7.26875C20.7963 3.7425 17.77 1 14.095 1C10.15 1 6.9525 4.16 6.9525 8.05875C6.9515 8.89619 7.10139 9.72696 7.395 10.5113M7.395 10.5113C8.08662 10.6456 8.745 10.9149 9.3325 11.3038"
                                    stroke="#4c8c2b"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- BOTTOM BTNS -->
            <div class="absolute z-[1] p-6 bottom-16 left-0 w-full">
                <div class="flex flex-col items-center gap-2">
                    <Link href="#" class="btn btn-primary w-full">
                        Commencer le sentier
                    </Link>
                </div>
            </div>

            <!-- SCROLL INFOS -->
            <div
                v-if="toggleMenuFirstActive"
                class="absolute z-[1] p-6 top-[50%] left-0 flex flex-col gap-2 items-center"
            >
                <span
                    class="scroll-indicator"
                    :class="{
                        'scroll-indicator-active': currentIndex === 1,
                    }"
                >
                </span>
                <span
                    class="scroll-indicator"
                    :class="{
                        'scroll-indicator-active': currentIndex === 2,
                    }"
                ></span>
                <span
                    class="scroll-indicator"
                    :class="{
                        'scroll-indicator-active': currentIndex === 3,
                    }"
                ></span>
            </div>

            <!-- CONTENT -->
            <div
                class="background-image w-full h-full bg-primary flex flex-col justify-center max-h-screen overflow-hidden"
            >
                <div class="backdrop-blur-md w-full h-full relative">
                    <!-- TOGGLE MENU -->
                    <div
                        class="absolute flex flex-row justify-center w-full z-[1] p-6 top-24 sm:top-32 left-0"
                    >
                        <div
                            class="flex flex-col gap-1 w-40 cursor-pointer text-center"
                            :class="{
                                'text-base-100': toggleMenuFirstActive,
                                'text-base-200': !toggleMenuFirstActive,
                            }"
                            @click="toggleMenuFirstActive = true"
                        >
                            <p class="pl-6 pr-4 font-medium">Informations</p>
                            <span
                                class="w-full h-1 rounded-l-full"
                                :class="{
                                    'bg-base-100': toggleMenuFirstActive,
                                    'bg-base-200': !toggleMenuFirstActive,
                                }"
                            ></span>
                        </div>
                        <div
                            class="flex flex-col gap-1 w-40 cursor-pointer text-center"
                            :class="{
                                'text-base-100': !toggleMenuFirstActive,
                                'text-base-200': toggleMenuFirstActive,
                            }"
                            @click="toggleMenuFirstActive = false"
                        >
                            <p class="pl-4 pr-6 font-medium">
                                Points d’intérêts
                            </p>
                            <span
                                class="w-full h-1 rounded-r-full"
                                :class="{
                                    'bg-base-100': !toggleMenuFirstActive,
                                    'bg-base-200': toggleMenuFirstActive,
                                }"
                            ></span>
                        </div>
                    </div>
                    <div class="flex flex-col w-full h-[80%] items-center">
                        <div class="max-w-sm h-full mt-52">
                            <template v-if="toggleMenuFirstActive">
                                <div
                                    class="scroll-container w-full h-[70%] overflow-scroll"
                                >
                                    <!-- ROUTE -->
                                    <div
                                        class="flex flex-col justify-center items-center h-full gap-8 p-16"
                                    >
                                        <div class="w-36 h-36 relative">
                                            <Tick2
                                                class="h-7 w-7 bg-base-100 rounded-full absolute -top-2 -right-2"
                                                :class="{
                                                    'text-primary':
                                                        route.isDone,
                                                    'text-base-200':
                                                        !route.isDone,
                                                }"
                                            />
                                            <img
                                                :src="bgImgPath"
                                                :alt="
                                                    route.pictures.at(0).title
                                                "
                                                class="w-full h-full rounded-md"
                                            />
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <h1
                                                class="text-2xl font-bold text-base-100 text-center"
                                            >
                                                {{ route.name }}
                                            </h1>
                                            <p
                                                class="text-sm font-semibold mt-2 text-center text-base-100 max-h-32 overflow-scroll"
                                            >
                                                {{ route.description }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- GENERAL INFO -->
                                    <div
                                        class="flex flex-col justify-center items-center h-full gap-8 p-16"
                                    >
                                        <div
                                            class="flex flex-col items-center gap-10"
                                        >
                                            <h1
                                                class="text-2xl font-bold text-base-100"
                                            >
                                                Informations générales
                                            </h1>
                                            <div
                                                class="flex flex-col w-full gap-4"
                                            >
                                                <div
                                                    class="flex flex-row items-center gap-4"
                                                >
                                                    <Clock3
                                                        class="h-7 w-7 text-base-100"
                                                    />
                                                    <p
                                                        class="text-base-100 text-base font-semibold"
                                                    >
                                                        {{ duration }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="flex flex-row items-center gap-4"
                                                >
                                                    <Activity
                                                        class="h-7 w-7 text-base-100"
                                                    />
                                                    <p
                                                        class="text-base-100 text-base font-semibold"
                                                    >
                                                        {{ distance }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="flex flex-row items-center gap-4"
                                                >
                                                    <MenuHamburger3
                                                        class="h-7 w-7 text-base-100 -rotate-90 self-start"
                                                    />
                                                    <p
                                                        class="text-base-100 text-base font-semibold"
                                                    >
                                                        Difficulté
                                                        {{
                                                            route.difficulty
                                                                .name
                                                        }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="flex flex-row items-center gap-4"
                                                >
                                                    <Tag
                                                        class="h-7 w-7 text-base-100 self-start"
                                                    />
                                                    <div
                                                        class="text-base-100 text-base font-semibold flex flex-col gap-2 max-h-24 overflow-scroll flex-grow"
                                                    >
                                                        <div
                                                            v-for="tag in route.tags"
                                                            class="badge badge-primary"
                                                        >
                                                            {{ tag.name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- RATING -->
                                    <div
                                        class="flex flex-col justify-center items-center h-full gap-8 p-16"
                                    >
                                        <div
                                            class="flex flex-col items-center gap-10"
                                        >
                                            <h1
                                                class="text-2xl font-bold text-base-100"
                                            >
                                                Note du sentier
                                            </h1>
                                            <div
                                                class="flex flex-col items-center gap-2"
                                            >
                                                <p class="text-base-100">
                                                    <span class="text-6xl">{{
                                                        roundedRate
                                                    }}</span
                                                    ><span>/5</span>
                                                </p>
                                                <AppStarRating
                                                    :rating="+roundedRate"
                                                />
                                                <p
                                                    class="text-base-100 text-sm"
                                                >
                                                    ({{ route.rates.length }}
                                                    avis)
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- INTEREST POINTS -->
                            <template v-else>
                                <div
                                    class="flex flex-col items-center p-6 gap-4"
                                >
                                    <p class="text-2xl font-bold text-base-100">
                                        {{ interestPoints.length }} Points
                                        d’intérêts
                                    </p>
                                    <div
                                        class="bg-base-300 w-full flex flex-col gap-2 items-center rounded-xl p-6 bg-opacity-40 h-96 overflow-scroll"
                                    >
                                        <AppHorizontalCard
                                            v-for="(
                                                interestPoint, index
                                            ) in interestPoints"
                                            class="w-full"
                                            :key="index"
                                            :title="interestPoint.name"
                                            :description="
                                                interestPoint.description
                                            "
                                            :href="
                                                '/interest-point/' +
                                                interestPoint.uuid
                                            "
                                            :imgPath="
                                                getImgPath(
                                                    interestPoint.pictures.at(0)
                                                        .path,
                                                )
                                            "
                                        />
                                    </div>
                                </div>
                            </template>
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

.scroll-container {
    scroll-snap-type: y mandatory;
}

.scroll-container > * {
    scroll-snap-align: center;
}

.scroll-indicator {
    width: 0.3rem;
    height: 0.3rem;
    background-color: #b8bbbe;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.scroll-indicator-active {
    width: 0.5rem;
    height: 0.5rem;
    background-color: #fdfdfa;
}
</style>
