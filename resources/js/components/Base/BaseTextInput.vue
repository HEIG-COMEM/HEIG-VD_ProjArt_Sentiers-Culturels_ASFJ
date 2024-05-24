<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
    type: String,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        v-model="model"
        v-bind="$attrs"
        class="input input-bordered w-full max-w-xs"
    />
</template>
