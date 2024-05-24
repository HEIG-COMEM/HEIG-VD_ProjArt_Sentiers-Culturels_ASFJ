<script setup>
import MobileAppLayout from "@/Layouts/MobileAppLayout.vue";

import BaseTextInput from "@/Components/Base/BaseTextInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import BaseBadge from "@/Components/Base/BaseBadge.vue";
import AppMapLibre from "@/Components/App/AppMapLibre.vue";
import BaseBottomSheets from "@/Components/Base/BaseBottomSheets.vue";

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
            <AppMapLibre :interestPoints="interestPoints.data" />
            <BaseBottomSheets />
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
