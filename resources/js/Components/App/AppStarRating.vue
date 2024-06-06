<script setup>
import { defineProps, ref, watch } from "vue";
const props = defineProps({
    rating: {
        type: Number,
        required: true,
    },
    isUserInteraction: {
        type: Boolean,
        default: false,
    },
});
const rating = ref(props.rating);
const ratingArray = Array.from({ length: 10 }, (_, i) => i + 1);
const model = defineModel();

const updateRating = (value) => {
    if (props.isUserInteraction) {
        rating.value = value;
        model.value = value;
    }
};
</script>

<template>
    <div class="rating rating-lg rating-half">
        <input
            type="radio"
            name="rating-10"
            class="rating-hidden"
            :checked="!rating"
            @change="updateRating(0)"
        />
        <template v-for="i in ratingArray">
            <input
                type="radio"
                name="rating-10"
                class="bg-green-500 mask mask-star-2 mask-half-1"
                :class="{
                    'mask-half-1': i % 2 === 1,
                    'mask-half-2': i % 2 === 0,
                }"
                :disabled="!isUserInteraction"
                :checked="rating * 2 === i"
                @change="updateRating(i / 2)"
            />
        </template>
    </div>
</template>

<style scoped>
.rating input[type="radio"] {
    border: none;
}

.rating input[type="radio"]:checked {
    @apply bg-green-500;
}

.rating-hidden:checked {
    @apply !bg-transparent;
}
.rating-hidden:focus,
.rating-hidden:focus-visible {
    @apply ring-transparent ring-offset-transparent;
}
</style>
