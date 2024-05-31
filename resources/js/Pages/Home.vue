<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { defineProps, ref, watch } from "vue";

import MobileAppLayout from "@/Layouts/AppLayout.vue";

import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";
import AppMapSmall from "@/Components/App/AppMapSmall.vue";

const props = defineProps({
    top3: {
        type: Object,
        required: true,
    },
});

const showFunctionalities = ref(true);
</script>

<template>
    <Head title="Accueil" />
    <MobileAppLayout>
        <template v-slot:main>
            <BaseSearchBar placeholder="Search for interest points" />
            <div
                class="mt-20 pb-20 w-full px-4 flex flex-col items-center gap-20"
            >
                <Link :href="route('map')" class="w-full">
                    <div class="card w-full bg-base-100 shadow-xl min-h-56">
                        <div class="card-body">
                            <AppMapSmall />
                        </div>
                    </div>
                </Link>
                <div>
                    <h1
                        class="text-primary text-2xl text-center font-semibold mb-6"
                    >
                        Découvre le canton de Vaud à travers ses sentiers
                    </h1>
                    <p class="text-base text-neutral-content text-center">
                        Explore la richesse culturelle et le patrimoine unique
                        du canton de Vaud. Pars à l’aventure sur des sentiers
                        variés et collectionne des badges uniques pour compléter
                        des familles.
                    </p>
                </div>
                <div class="collapse collapse-arrow shadow-xl bg-base-100">
                    <input
                        type="radio"
                        name="my-accordion-2"
                        :checked="showFunctionalities"
                    />
                    <div
                        class="collapse-title text-xl font-medium text-primary"
                        @click="showFunctionalities = !showFunctionalities"
                    >
                        Fonctionnalités
                    </div>
                    <div class="collapse-content">
                        <p>hello</p>
                    </div>
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
