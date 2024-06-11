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
    isTotalOnly: {
        type: Boolean,
        required: false,
        default: false,
    },
    href: {
        type: String,
        required: false,
    },
    unit: {
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
            <img :src="'/storage/badges/' + icon" />
        </div>
        <div class="max-w-45">
            <p class="truncate overflow-hidden">{{ badge }}</p>
        </div>
        <template v-if="total">
            <div v-if="isTotalOnly" class="text-sm text-base-300">
                {{ total }} {{ unit }}
            </div>
            <div v-else class="text-sm text-base-300">
                {{ count }} / {{ total }} {{ unit }}
            </div>
        </template>
    </component>
</template>

<style scoped></style>
