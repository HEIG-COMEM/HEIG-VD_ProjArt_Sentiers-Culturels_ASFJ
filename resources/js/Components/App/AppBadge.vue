<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    icon: {
        type: String,
        default: "default.svg",
    },
    badge: {
        type: String,
        required: true,
    },
    count: {
        type: Number,
        required: false,
    },
    total: {
        type: Number,
        reuiqred: false,
    },
    href: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <component
        :is="href ? Link : 'div'"
        :href
        class="flex flex-col items-center"
    >
        <div
            class="border border-neutral-200 w-20 h-20 bg-neutral-100 shadow-md rounded-md flex items-center justify-center mb-2"
        >
            <img :src="'/storage/badges/' + icon" alt="" srcset="" />
        </div>
        <div class="max-w-20">
            <p class="truncate overflow-hidden">{{ badge }}</p>
        </div>
        <div v-if="total" class="text-sm text-base-300">
            {{ count }} / {{ total }}
        </div>
    </component>
</template>

<style scoped></style>
