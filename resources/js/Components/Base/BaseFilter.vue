<script setup>
import { defineProps } from "vue";

defineProps({
    filterName: {
        type: String,
        required: true,
    },
    filterValue: {
        type: Array,
        required: true,
        validate: (value) => {
            const REQUIRED_KEYS = ["key", "value"];
            return value.every((item) =>
                REQUIRED_KEYS.every((key) => key in item),
            );
        },
    },
});
</script>

<template>
    <div class="dropdown dropdown-bottom">
        <div tabindex="0" role="button" class="badge badge-primary m-1 py-3">
            {{ filterName }}
        </div>
        <ul
            tabindex="0"
            class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52"
        >
            <li v-for="filterValue in filterValue" :key="filterValue.key">
                <div class="form-control">
                    <label class="label cursor-pointer">
                        <input
                            type="checkbox"
                            :checked="filterValue.checked"
                            class="checkbox"
                        />
                        <span class="ml-2 label-text">{{
                            filterValue.value
                        }}</span>
                    </label>
                </div>
            </li>
        </ul>
    </div>
</template>

<style scoped></style>
