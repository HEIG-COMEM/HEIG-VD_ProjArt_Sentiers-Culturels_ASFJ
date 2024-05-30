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

const trimTags = props.tags.slice(0, 4);
</script>

<template>
    <Link :href>
        <div class="p-2 flex flex-row bg-base-100 rounded-3xl shadow-lg">
            <div v-if="imgPath" class="indicator mr-4">
                <span class="indicator-item badge border-none h-6 w-6 p-0">
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
                            class="badge badge-primary"
                        >
                            {{ tag }}
                        </div>
                    </div>
                </div>
                <ArrowRight2 v-if="href" class="h-6 w-6" />
            </div>
        </div>
    </Link>
</template>

<style scoped>
.indicator-item {
    transform: translate(20%, -20%);
}
</style>
