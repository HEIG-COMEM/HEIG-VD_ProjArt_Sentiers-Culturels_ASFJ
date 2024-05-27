<script setup>
import { computed } from "vue";
const props = defineProps({
    content: {
        type: String,
        default: "Badge",
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
    <div class="badge" :class="className[color]">{{ content }}</div>
</template>

<style scoped></style>
