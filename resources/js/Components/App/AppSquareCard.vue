<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    imgPath: {
        type: String,
        required: false,
    },
    imgAlt: {
        type: String,
        required: false,
    },
    title: {
        type: String,
        required: true,
    },
    href: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <component :is="href ? Link : 'div'" :href="href || ''">
        <div class="indicator w-24 rounded-2xl aspect-square bg-purple-200">
            <div v-if="imgPath" class="indicator">
                <img
                    class="rounded-2xl aspect-square relative"
                    :src="imgPath"
                    :alt="imgAlt"
                />
                <div
                    class="absolute bottom-0 bg-white h-6 w-full rounded-b-xl flex flex-col items-center justify-center"
                >
                    <p
                        class="text-xs text-center p-1 truncate max-h-6 max-w-[90%]"
                    >
                        {{ title }}
                    </p>
                </div>
            </div>
        </div>
    </component>
</template>

<style scoped></style>
