<script setup>
import { ref, defineProps, onMounted, watch } from "vue";
import { useEventListener } from "@vueuse/core";
import WMO from "../../utils/WMO.json";

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
    // TODO: Uncomment this block to fetch data from API
    // fetchAPI().then((data) => {
    //     console.log(data);
    //     if (data) {
    //         weather.value = getWeather(data.daily);
    //     } else {
    //         error.value = data.status;
    //     }
    //     isLoading.value = false;
    // });
});
</script>

<template>
    <!-- TODO: Wait for UI design -->
    <dialog id="weather_modal" class="modal" :open="model">
        <div class="modal-box">
            <div v-if="isLoading" class="skeleton w-full h-32"></div>
            <div v-else-if="error">
                {{ error }}
            </div>
            <div v-else>
                <div v-for="item in weather" :key="item.day">
                    <div>day {{ item.day }}</div>
                    <img :src="item.icon" alt="weather icon" />
                    <div>maxTemp {{ item.maxTemp }}</div>
                    <div>minTemp {{ item.minTemp }}</div>
                    <div>rain {{ item.rain }}</div>
                    <div>snow {{ item.snow }}</div>
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
