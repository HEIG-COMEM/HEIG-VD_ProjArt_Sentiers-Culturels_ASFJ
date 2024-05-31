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
        default: "#",
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
    <Link :href="route">
        <button class="btn" v-bind="$attrs" :class="className[color]">
            {{ content }}
        </button>
    </Link>
</template>

<style scoped></style>
