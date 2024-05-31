<script setup>
import { Head } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeLayout>
        <template v-slot:main>
            <div class="p-6 flex flex-col justify-center gap-28">
                <img
                    src="/storage/logo.svg"
                    alt="logo de sentier culturel vaud"
                    class="w-1/2 mx-auto rounded-xl"
                />
                <div class="bg-white p-8 rounded-2xl">
                    <h1 class="text-3xl font-bold text-center">
                        Bienvenue sur le backoffice
                    </h1>
                    <p class="text-center text-gray-500 mt-4">
                        Vous pouvez gérer les différentes ressources de
                        l'application
                    </p>
                    <div
                        class="flex flex-col content-center items-center w-full gap-4 mt-8"
                    >
                        <BaseButton
                            :href="route('backoffice.routes.create')"
                            color="primary"
                            class="w-full"
                            content="Créer un parcours"
                        />
                        <span class="self-center text-small">ou</span>
                        <BaseButton
                            :href="route('backoffice.interest-points.create')"
                            color="primary"
                            class="w-full"
                            content="Créer un point d'intérêt"
                        />
                    </div>
                </div>
            </div>
        </template>
    </BackofficeLayout>
</template>

<style scoped></style>
