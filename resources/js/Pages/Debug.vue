<script setup>
import { ref } from "vue";

import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    counter: Number,
});

const counter = ref(props.counter);
</script>

<template>
    <div class="flex flex-col items-center">
        <h1 class="text-2xl">Debug</h1>
        <p>{{ counter }}</p>
        <PrimaryButton @click="counter++">Increment</PrimaryButton>
    </div>
</template>

<style scoped></style>
