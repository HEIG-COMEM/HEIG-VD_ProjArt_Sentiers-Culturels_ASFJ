<script setup>
import { Link } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
defineProps({
    interestPoints: Object,
});
</script>

<template>
    <div class="flex flex-col items-center">
        <h1 class="text-2xl">Interest Points</h1>
        <Link :href="route('interestpoint.create')">Create Interest Point</Link>
        <div>
            <ul>
                <li v-for="point in interestPoints.data" :key="point.id">
                    {{ point.name }}
                </li>
            </ul>
            <Pagination :pagination="interestPoints.meta" />
        </div>
    </div>
</template>

<style scoped></style>
