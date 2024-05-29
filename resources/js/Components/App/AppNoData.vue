<script setup>
import { AlertCircle } from "@iconsans/vue/linear";
import BaseLink from "../Base/BaseLink.vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    text: {
        type: String,
        required: true,
    },
    callToAction: {
        type: Object,
        required: true,
        validate: (value) => {
            if (value.text && value.href) {
                return true;
            }
            return false;
        },
    },
});
</script>

<template>
    <div class="flex flex-col justify-center h-full">
        <div class="p-4 flex flex-col gap-2 items-center text-base-200">
            <AlertCircle class="h-12 w-12" />
            <p>Votre {{ title }} est vide ?</p>
            <p class="text-center">
                Cliquez sur
                <BaseLink :href="callToAction.href">{{
                    callToAction.text
                }}</BaseLink>
                {{ text }}
            </p>
        </div>
    </div>
</template>

<style scoped></style>
