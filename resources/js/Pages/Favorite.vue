<script setup>
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";
import { Tick2, ArrowRight2 } from "@iconsans/vue/linear";
import { Link } from "@inertiajs/vue3";

const getUserFavorites = () => {
    // fetch the user's favorite sentiers from the database
    // return the favorite sentiers
};
</script>

<template>
    <Head title="Favoris" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="h-full w-full p-6 flex flex-col gap-2">
                <AppHorizontalCard
                    title="Parcours sentier"
                    tag="Lorem"
                    href="/"
                    img-path="https://img.daisyui.com/images/stock/photo-1494232410401-ad00d5433cfa.jpg"
                    img-alt="lorem"
                    :is-active="true"
                />
                <AppHorizontalCard
                    title="Parcours sentier"
                    tag="Lorem"
                    href="/"
                    img-path="https://img.daisyui.com/images/stock/photo-1494232410401-ad00d5433cfa.jpg"
                    img-alt="lorem"
                />
                <AppHorizontalCard
                    title="Parcours sentier"
                    tag="Lorem"
                    href="/"
                    img-path="https://img.daisyui.com/images/stock/photo-1494232410401-ad00d5433cfa.jpg"
                    img-alt="lorem"
                />
                <AppHorizontalCard
                    title="Parcours sentier"
                    tag="Lorem"
                    href="/"
                    img-path="https://img.daisyui.com/images/stock/photo-1494232410401-ad00d5433cfa.jpg"
                    img-alt="lorem"
                />
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped>
.indicator-item {
    transform: translate(20%, -20%);
}

.badge {
}
</style>
