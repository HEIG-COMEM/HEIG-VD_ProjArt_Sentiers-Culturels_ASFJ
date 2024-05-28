<script setup>
import { AlertCircle } from "@iconsans/vue/linear";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    text: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="flex flex-col justify-center h-full">
        <div
            class="p-4 flex flex-col gap-2 items-center bg-base-100 rounded-3xl shadow-lg text-base-200"
        >
            <AlertCircle class="h-12 w-12" />
            <p>Votre {{ title }} est vide ?</p>
            <p class="text-center">
                {{ text }}
            </p>
        </div>
    </div>
</template>

<style scoped></style>
