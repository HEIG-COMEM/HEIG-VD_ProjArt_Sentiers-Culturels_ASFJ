<script setup>
import { AlertCircle } from "@iconsans/vue/linear";
import BaseLink from "../Base/BaseLink.vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="flex flex-col justify-center h-full">
        <div class="p-4 flex flex-col gap-2 items-center text-base-200">
            <AlertCircle class="h-12 w-12" />
            <p class="text-xl">{{ title }}</p>
            <p class="text-center">
                <slot name="content" />
            </p>
        </div>
    </div>
</template>

<style scoped></style>
