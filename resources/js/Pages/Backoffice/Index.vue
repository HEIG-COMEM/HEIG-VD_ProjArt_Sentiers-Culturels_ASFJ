<script setup>
import { Head } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import BaseLink from "@/Components/Base/BaseLink.vue";
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeLayout>
        <template v-slot:main>
            <div class="p-6 flex flex-col justify-center gap-14 pb-24">
                <img
                    src="/storage/logo-257x257.svg"
                    alt="logo de sentier culturel vaud"
                    class="w-1/3 mx-auto rounded-xl"
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
                            :route="route('backoffice.interest-points.create')"
                            color="primary"
                            class="w-full"
                            content="Créer un point d'intérêt"
                        />
                        <span class="self-center text-small text-gray-500"
                            >ou</span
                        >
                        <BaseButton
                            :route="route('backoffice.routes.create')"
                            color="primary"
                            class="w-full"
                            content="Créer un parcours"
                        />
                    </div>
                    <div class="w-full flex justify-center mt-8">
                        <BaseLink
                            :href="route('backoffice.tutorial')"
                            class="text-center mt-4"
                        >
                            Comment ça marche ?
                        </BaseLink>
                    </div>
                </div>
            </div>
        </template>
    </BackofficeLayout>
</template>

<style scoped></style>
