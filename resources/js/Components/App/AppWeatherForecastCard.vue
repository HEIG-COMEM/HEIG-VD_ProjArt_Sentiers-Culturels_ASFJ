<script setup>
import { ref, defineProps, onMounted } from "vue";
import { useEventListener } from "@vueuse/core";
import WMO from "../../utils/WMO.json";
import { Cross } from "@iconsans/vue/linear";

const props = defineProps({
    lat: {
        type: Number,
        reqired: true,
    },
    long: {
        type: Number,
        reqired: true,
    },
});

const model = defineModel();

const weather = ref(null);
const isLoading = ref(true);
const error = ref(null);

const visibleDay = ref(1);

const getWeatherIcon = (code) => {
    return WMO[code].day.image;
};

const getWeather = (weather) => {
    const weatherData = [];
    for (let i = 0; i < weather.time.length; i++) {
        weatherData.push({
            day: weather.time[i],
            icon: getWeatherIcon(weather.weather_code[i]),
            maxTemp: weather.temperature_2m_max[i],
            minTemp: weather.temperature_2m_min[i],
            rain: weather.rain_sum[i],
            snow: weather.snowfall_sum[i],
        });
    }
    return weatherData;
};

const fetchAPI = async () => {
    const API_URL = `https://api.open-meteo.com/v1/forecast?latitude=${props.lat}&longitude=${props.long}&daily=weather_code,temperature_2m_max,temperature_2m_min,precipitation_sum,rain_sum,showers_sum,snowfall_sum&timezone=Europe%2FBerlin&forecast_days=3`;
    const response = await fetch(API_URL);
    const data = await response.json();
    return data;
};

useEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        model.value = false;
    }
});

onMounted(() => {
    fetchAPI().then((data) => {
        if (data) {
            weather.value = getWeather(data.daily);
        } else {
            error.value = data.status;
        }
        isLoading.value = false;
    });
});

const getDay = (day) => {
    const date = new Date(day);
    return date.toLocaleDateString("fr-FR", { weekday: "short" });
};

const getDayNumber = (day) => {
    const date = new Date(day);
    return date.getDate();
};
</script>

<template>
    <dialog id="weather_modal" class="modal bg-base-100" :open="model">
        <div class="modal-box">
            <div v-if="isLoading" class="skeleton w-full h-32"></div>
            <div v-else-if="error">
                {{ error }}
            </div>
            <div v-else>
                <div class="flex flex-row w-full justify-between">
                    <h3 class="text-xl font-medium">Météo</h3>
                    <button @click="model = !model">
                        <Cross class="h-7 w-7" />
                    </button>
                </div>
                <div class="flex flex-row justify-around w-full max-h-8 mt-5">
                    <div
                        class="flex flex-col items-center h-full gap-2 cursor-pointer select-none flex-grow border-t-2 border-t-base-200"
                        :class="{
                            'border-t-primary': visibleDay === 1,
                        }"
                        @click="visibleDay = 1"
                    >
                        <span
                            :class="{
                                'text-base-300': visibleDay !== 1,
                            }"
                        >
                            {{ getDay(weather[0].day) }}
                        </span>
                        <span
                            class="rounded-full w-8 h-8 flex items-center justify-center"
                            :class="{
                                'text-white': visibleDay === 1,
                                'bg-primary': visibleDay === 1,
                            }"
                        >
                            {{ getDayNumber(weather[0].day) }}
                        </span>
                    </div>
                    <div
                        class="flex flex-col items-center h-full gap-2 cursor-pointer select-none flex-grow border-t-2 border-t-base-200"
                        :class="{
                            'border-t-primary': visibleDay === 2,
                        }"
                        @click="visibleDay = 2"
                    >
                        <span
                            :class="{
                                'text-base-300': visibleDay !== 2,
                            }"
                        >
                            {{ getDay(weather[1].day) }}
                        </span>
                        <span
                            class="rounded-full w-8 h-8 flex items-center justify-center"
                            :class="{
                                'text-white': visibleDay === 2,
                                'bg-primary': visibleDay === 2,
                            }"
                        >
                            {{ getDayNumber(weather[1].day) }}
                        </span>
                    </div>
                    <div
                        class="flex flex-col items-center h-full gap-2 cursor-pointer select-none flex-grow border-t-2 border-t-base-200"
                        :class="{
                            'border-t-primary': visibleDay === 3,
                        }"
                        @click="visibleDay = 3"
                    >
                        <span
                            :class="{
                                'text-base-300': visibleDay !== 3,
                            }"
                        >
                            {{ getDay(weather[2].day) }}
                        </span>
                        <span
                            class="rounded-full w-8 h-8 flex items-center justify-center"
                            :class="{
                                'text-white': visibleDay === 3,
                                'bg-primary': visibleDay === 3,
                            }"
                        >
                            {{ getDayNumber(weather[2].day) }}
                        </span>
                    </div>
                </div>
                <div
                    class="flex flex-col gap-8 mt-16 bg-gray-200 p-6 rounded-lg"
                >
                    <div class="flex flex-row items-center">
                        <span class="text-4xl font-bold"
                            >{{ weather[visibleDay - 1].maxTemp }}°</span
                        >
                        <img
                            class="w-24 h-24"
                            :src="weather[visibleDay - 1].icon"
                            :alt="weather[visibleDay - 1].day"
                        />
                    </div>
                    <div class="flex flex-row gap-16">
                        <div class="flex flex-col gap-2">
                            <span
                                >Max:
                                <span class="text-lg font-medium"
                                    >{{
                                        weather[visibleDay - 1].maxTemp
                                    }}°</span
                                ></span
                            >
                            <span
                                >Min:
                                <span class="text-lg font-medium"
                                    >{{
                                        weather[visibleDay - 1].minTemp
                                    }}°</span
                                ></span
                            >
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                >Pluie:
                                <span class="text-lg font-medium"
                                    >{{ weather[visibleDay - 1].rain }}mm</span
                                ></span
                            >
                            <span
                                >Neige:
                                <span class="text-lg font-medium"
                                    >{{ weather[visibleDay - 1].snow }}mm</span
                                ></span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button @click="model = !model">close</button>
        </form>
    </dialog>
</template>

<style scoped>
.modal {
    background-color: #0006;
    animation: modal-pop 0.2s ease-out;
}
</style>
