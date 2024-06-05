<script setup>
import { ref, reactive, onMounted } from "vue";
import AppSquareCard from "@/Components/App/AppSquareCard.vue";

const isLoading = ref(true);
const error = reactive({ data: [] });
const nearby = reactive({ data: [] });

const getImgPath = (path) => `/storage/pictures/${path}`;

onMounted(() => {
    navigator.geolocation.getCurrentPosition((position) => {
        const API_ENDPOINT = `/api/routes/nearby?latitude=${position.coords.latitude}&longitude=${position.coords.longitude}&radius=5`;
        const fetchData = async () => {
            const response = await fetch(API_ENDPOINT);
            const data = await response.json();
            nearby.data = data.data;
        };
        fetchData();
    });
});
</script>

<template>
    <div class="flex flex-row gap-4 items-center">
        <AppSquareCard
            v-if="!nearby.data.length"
            v-for="load in [1, 2, 3]"
            class="skeleton h-24 w-24"
            title=""
        />
        <AppSquareCard
            v-else
            v-for="route in nearby.data"
            :key="route.id"
            :title="route.name"
            :href="`/route/${route.uuid}`"
            :img-path="getImgPath(route.pictures.at(0).path)"
            :img-alt="route.name"
        />
    </div>
</template>

<style scoped></style>
