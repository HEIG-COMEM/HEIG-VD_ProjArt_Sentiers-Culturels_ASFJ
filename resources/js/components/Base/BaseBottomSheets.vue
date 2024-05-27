<script lang="ts" setup>
import { ref } from "vue";
import { SwipeModal } from "@takuma-ru/vue-swipe-modal";

const isOpen = ref(false);
</script>

<template>
    <button @click="isOpen = true">Open modal</button>
    <SwipeModal
        v-model="isOpen"
        snapPoint="auto"
        :isPersistent="true /* boolean */"
    >
        <button @click="isOpen = false">Close</button>
        Modal content
    </SwipeModal>
</template>
