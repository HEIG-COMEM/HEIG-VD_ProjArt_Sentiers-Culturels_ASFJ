<script setup>
import { Tick2, ArrowRight2 } from "@iconsans/vue/linear";
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
    isDone: {
        type: Boolean,
        default: false,
    },
    displayIsDone: {
        type: Boolean,
        default: true,
    },
    title: {
        type: String,
        required: true,
    },
    tags: {
        type: Array,
        required: false,
        default: () => [],
    },
    href: {
        type: String,
        required: false,
    },
});

const MAX_TAGS_TO_DISPLAY = 2;
const trimTags = props.tags.slice(0, MAX_TAGS_TO_DISPLAY);
</script>

<template>
    <component :is="href ? Link : 'div'" :href="href || ''">
        <div class="p-2 flex flex-row bg-base-100 rounded-3xl shadow-lg">
            <div v-if="imgPath" class="indicator mr-4">
                <span
                    v-if="displayIsDone"
                    class="indicator-item badge border-none h-6 w-6 p-0"
                >
                    <Tick2
                        class="h-5 w-5"
                        :class="{
                            'text-primary': isDone,
                            'text-base-200': !isDone,
                        }"
                    />
                </span>
                <div class="w-24">
                    <img
                        class="object-cover rounded-2xl aspect-square"
                        :src="imgPath"
                        :alt="imgAlt"
                    />
                </div>
            </div>
            <div class="flex flex-row items-center justify-between w-full">
                <div class="space-y-0.5">
                    <p class="text-base text-black font-semibold">
                        {{ title }}
                    </p>
                    <div
                        v-if="tags.length"
                        class="flex flex-row flex-wrap gap-2"
                    >
                        <div
                            v-for="tag in trimTags"
                            class="badge badge-primary truncate"
                        >
                            {{ tag }}
                        </div>
                        <div
                            v-if="props.tags.length > MAX_TAGS_TO_DISPLAY"
                            class="badge badge-primary"
                        >
                            ...
                        </div>
                    </div>
                </div>
                <ArrowRight2 v-if="href" class="h-6 w-6 min-w-[30px]" />
            </div>
        </div>
    </component>
</template>

<style scoped>
.indicator-item {
    transform: translate(20%, -20%);
}
</style>
