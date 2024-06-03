<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    content: {
        type: String,
        default: "Button",
    },
    color: {
        type: String,
        default: "neutral",
        validator: (value) =>
            [
                "primary",
                "secondary",
                "accent",
                "ghost",
                "link",
                "info",
                "success",
                "warning",
                "error",
            ].includes(value),
    },
    route: {
        type: String,
        required: false,
    },
});

const className = {
    primary: "badge-primary",
    secondary: "badge-secondary",
    accent: "badge-accent",
    ghost: "badge-ghost",
    link: "badge-link",
    info: "badge-info",
    success: "badge-success",
    warning: "badge-warning",
    error: "badge-error",
};
</script>

<template>
    <component
        :is="route ? Link : 'button'"
        v-bind="$attrs"
        class="btn"
        :class="className[color]"
        :href="route"
    >
        {{ content }}
    </component>
</template>

<style scoped></style>
