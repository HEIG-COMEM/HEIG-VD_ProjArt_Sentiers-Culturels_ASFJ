<script setup>
import { defineProps } from "vue";

defineProps({
    icon: {
        type: String,
        default: "default.png",
    },
    badge: {
        type: String,
        required: true,
    },
    count: {
        type: Number,
        required: true,
    },
    total: {
        type: Number,
        reuiqred: true,
    },
});
</script>

<template>
    <div class="flex flex-col items-center">
        <div
            class="border border-neutral-200 w-20 h-20 bg-neutral-100 shadow-md rounded-md flex items-center justify-center mb-2"
        >
            <img :src="'storage/badges/' + icon" alt="" srcset="" />
        </div>
        <div class="max-w-20">
            <p class="truncate overflow-hidden">{{ badge }}</p>
        </div>
        <div v-if="count && total" class="text-sm text-base-300">
            {{ count }} / {{ total }}
        </div>
    </div>
</template>

<style scoped></style>
