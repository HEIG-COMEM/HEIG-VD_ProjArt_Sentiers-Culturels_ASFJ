<script setup>
import { ref } from "vue";
const count = ref(0);
</script>

<template>
    <h1>Hello From vue</h1>
    <button
        class="pointer-events-auto ml-8 rounded-md bg-indigo-600 px-3 py-2 text-[0.8125rem] font-semibold leading-5 text-white hover:bg-indigo-500"
        @click="count++"
    >
        Increment
    </button>
    {{ count }}
</template>

<style scoped></style>
