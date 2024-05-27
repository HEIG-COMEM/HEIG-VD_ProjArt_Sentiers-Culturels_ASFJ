<script setup>
import { defineProps, ref, watch } from "vue";

import MobileAppLayout from "@/Layouts/AppLayout.vue";

import BaseTextInput from "@/Components/Base/BaseInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import BaseBadge from "@/Components/Base/BaseBadge.vue";
import AppMapLibre from "@/Components/App/AppMapLibre.vue";
import BaseBottomSheets from "@/Components/Base/BaseBottomSheets.vue";
import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";

const props = defineProps({
    interestPoints: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <MobileAppLayout>
        <template v-slot:main>
            <BaseSearchBar placeholder="Search for interest points" />
            <div class="mt-20">
                <div class="card w-96 bg-base-100 shadow-xl min-h-56">
                    <div class="card-body">
                        <AppMapLibre :interestPoints="interestPoints.data" />
                    </div>
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
