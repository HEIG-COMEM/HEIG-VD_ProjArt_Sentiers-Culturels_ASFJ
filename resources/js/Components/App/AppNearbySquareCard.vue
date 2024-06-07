<script setup>
import { getImgPath } from "@/utils/helper";
import { ref, reactive, onMounted } from "vue";
import AppSquareCard from "@/Components/App/AppSquareCard.vue";

const RADIUS = 10;
const isLoading = ref(true);
const error = reactive({ data: [] });
const nearby = reactive({ data: [] });

onMounted(() => {
    navigator.geolocation.getCurrentPosition((position) => {
        const API_ENDPOINT = `/api/routes/nearby?latitude=${position.coords.latitude}&longitude=${position.coords.longitude}&radius=${RADIUS}`;
        const fetchData = async () => {
            const response = await fetch(API_ENDPOINT);
            const data = await response.json();
            isLoading.value = false;
            nearby.data = data.data;
        };
        fetchData();
    });
});
</script>

<template>
    <div class="flex flex-row gap-4 items-center">
        <AppSquareCard
            v-if="isLoading"
            v-for="load in [1, 2, 3]"
            class="skeleton h-24 w-24"
            title=""
        />
        <AppSquareCard
            v-else-if="nearby.data.length"
            v-for="route in nearby.data"
            :key="route.id"
            :title="route.name"
            :href="`/route/${route.uuid}`"
            :img-path="getImgPath(route.pictures.at(0).path)"
            :img-alt="route.name"
        />
        <div v-else>
            <p class="text-base-200 text-center">
                Il n'y a pas de parcours dans un rayon de {{ RADIUS }} km.
            </p>
        </div>
    </div>
</template>

<style scoped></style>
