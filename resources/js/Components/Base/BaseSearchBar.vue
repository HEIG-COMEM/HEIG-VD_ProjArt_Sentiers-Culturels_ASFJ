<script setup>
import { defineProps, defineModel } from "vue";

const props = defineProps({
    placeholder: {
        type: String,
        required: false,
        default: "Search",
    },
});

const model = defineModel();
</script>

<template>
    <label class="input input-bordered flex items-center gap-2">
        <input
            type="text"
            class="grow ring-transparent"
            :placeholder="placeholder"
            v-model="model"
        />
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 16 16"
            fill="currentColor"
            class="w-4 h-4 opacity-70"
        >
            <path
                fill-rule="evenodd"
                d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                clip-rule="evenodd"
            />
        </svg>
    </label>
</template>

<style scoped>
label {
    position: absolute;
    top: 1rem;
    left: 1rem;
    right: 1rem;
    z-index: 100;
}
input {
    border: none;
}

input:focus,
input:focus-within,
input:focus-visible,
label:focus-within,
label:focus {
    outline: none;
}

/* remove blue border on input while user focus */
input:focus-visible {
    outline-width: 0;
    box-shadow: none;
}
</style>
