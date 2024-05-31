<script setup>
import { defineProps } from "vue";
import { ArrowRight2 } from "@iconsans/vue/linear";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
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
    <Link :href>
        <div class="p-2 flex flex-row bg-base-100 rounded-3xl shadow-lg">
            <div class="indicator mr-4">
                <img
                    class="block mx-auto w-24 rounded-2xl aspect-square"
                    :src="'/storage/badges/' + icon"
                    alt=""
                    srcset=""
                />
            </div>

            <div class="flex flex-row items-center justify-between w-full">
                <p
                    class="truncate overflow-hidden text-base text-black font-semibold"
                >
                    {{ badge }}
                </p>
                <div class="flex flex-row items-center justify-end gap-2">
                    <div v-if="count && total" class="text-sm text-base-300">
                        {{ count }} / {{ total }}
                    </div>
                    <ArrowRight2 v-if="href" class="h-6 w-6" />
                </div>
            </div>
        </div>
    </Link>
</template>

<style scoped></style>
