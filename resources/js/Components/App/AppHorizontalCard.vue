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
    isActive: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        required: true,
    },
    tag: {
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
    <Link :href>
        <div class="p-4 flex flex-row bg-base-100 rounded-3xl shadow-lg">
            <div v-if="imgPath" class="indicator mr-4">
                <span class="indicator-item badge border-none h-6 w-6 p-0">
                    <Tick2
                        class="h-5 w-5"
                        :class="{
                            'text-primary': isActive,
                            'text-base-200': !isActive,
                        }"
                    />
                </span>
                <img
                    class="block mx-auto w-24 rounded-2xl aspect-square"
                    :src="imgPath"
                    :alt="imgAlt"
                />
            </div>
            <div class="flex flex-row items-center justify-between w-full">
                <div class="space-y-0.5">
                    <p class="text-base text-black font-semibold">
                        {{ title }}
                    </p>
                    <p class="text-sm text-black font-medium">{{ tag }}</p>
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
