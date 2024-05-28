<script setup>
import { Head } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";
import AppNoDataCard from "@/Components/App/AppNoDataCard.vue";

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
                <h1 class="text-2xl font-medium">Favoris</h1>
                <AppNoDataCard
                    title="liste de favoris"
                    text="Cliquez sur Découvrir pour commencer à ajouter des
                            sentiers à votre collection !"
                />
                <!-- <AppHorizontalCard
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
                /> -->
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
