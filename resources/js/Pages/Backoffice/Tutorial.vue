<script setup>
import { back } from "@/utils/helper";
import { Head } from "@inertiajs/vue3";
import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";
import AppBackofficeTutorial from "@/Components/App/AppBackofficeTutorial.vue";
import { ArrowLeft } from "@iconsans/vue/linear";
</script>

<template>
    <Head title="Comment ça marche ?" />
    <BackofficeLayout>
        <template v-slot:main>
            <div class="h-full w-full p-6 flex flex-col gap-2 overflow-scroll">
                <div class="flex flex-row justify-between">
                    <div class="rounded-full border-black">
                        <button
                            class="btn btn-circle btn-outline bg-base-100 border-base-200 min-h-0 h-7 w-7"
                            @click="back()"
                        >
                            <ArrowLeft class="w-6 h-6" />
                        </button>
                    </div>
                </div>
                <h1 class="text-2xl font-medium">Comment ça marche ?</h1>
                <div
                    class="absolute left-0 top-0 rounded-xl shadow-top-sm mt-32 p-2 pb-32 w-full h-[85vh] overflow-y-scroll"
                >
                    <AppBackofficeTutorial />
                </div>
            </div>
        </template>
    </BackofficeLayout>
</template>

<style scoped>
.indicator-item {
    transform: translate(20%, -20%);
}
</style>
