<script setup>
import { defineProps } from "vue";
import BaseLink from "@/Components/Base/BaseLink.vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    link: {
        type: Object,
        required: false,
        validator: (value) => {
            const REQUIRED_KEYS = ["href", "text"];
            return (
                Object.keys(value).length === REQUIRED_KEYS.length &&
                REQUIRED_KEYS.every((key) => Object.keys(value).includes(key))
            );
        },
    },
});
</script>

<template>
    <div class="card bg-base-100 shadow-xl">
        <div
            class="card-body"
            :class="{
                'gap-0':
                    !Array.isArray($slots.default) ||
                    $slots.default.length === 0,
            }"
        >
            <div
                v-if="link"
                class="flex flex-row justify-between items-baseline"
            >
                <h2 class="card-title">{{ title }}</h2>
                <BaseLink :href="link.href" class="text-sm">{{
                    link.text
                }}</BaseLink>
            </div>
            <h2 v-else class="card-title">{{ title }}</h2>
            <div>
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped></style>
