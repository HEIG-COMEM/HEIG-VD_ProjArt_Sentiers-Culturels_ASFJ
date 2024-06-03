<script setup>
import { defineProps, reactive } from "vue";
import { Head } from "@inertiajs/vue3";
import { ArrowLeft } from "@iconsans/vue/linear";
import { useForm } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";
import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";

const props = defineProps({
    interestpoint: {
        type: Object,
        required: true,
    },
});

const interestpoint = reactive(props.interestpoint.data);

const form = useForm({
    uuid: interestpoint.uuid,
});

const submit = () => {
    form.delete(route("backoffice.interest-points.destroy", form.uuid));
};

const back = () => {
    window.history.back();
};
const tagsName = (tags) => tags.map((tag) => tag.name).slice(0, 4);
const getImgSrc = (path) => {
    return `${window.location.origin}/storage/pictures/${path}`;
};
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeLayout>
        <template v-slot:main>
            <!-- TOP BTNS -->
            <div class="fixed z-[1] p-6 top-0 left-0 w-full">
                <div class="flex flex-row justify-between">
                    <div>
                        <button
                            class="btn btn-circle btn-outline btn-primary bg-base-100"
                            @click="back()"
                        >
                            <ArrowLeft class="w-7 h-7" />
                        </button>
                    </div>
                </div>
            </div>
            <div class="z-0 w-full h-full">
                <div class="h-[40vh] w-full fixed -z-10 top-0">
                    <img
                        class="w-full h-full object-cover"
                        :src="getImgSrc(interestpoint.pictures.at(0).path)"
                        :alt="interestpoint.pictures.at(0).title"
                    />
                </div>
                <div
                    class="mt-[30vh] min-h-[70vh] pb-20 rounded-t-xl bg-base-100 p-6 flex flex-col gap-8 shadow-top relative"
                >
                    <div
                        v-if="interestpoint.badge"
                        class="absolute top-[-35px] right-8 bg-base-100 rounded-full p-2 shadow-2xl"
                    >
                        <img
                            class="rounded-full h-full w-full object-cover"
                            :src="`/storage/badges/${interestpoint.badge.icon_path}`"
                            :alt="interestpoint.pictures.at(0).title"
                        />
                    </div>
                    <div>
                        <h1 class="text-2xl font-semibold mb-4">
                            {{ interestpoint.name }}
                        </h1>
                        <p>
                            {{ interestpoint.description }}
                        </p>
                    </div>
                    <div class="flex flex-row gap-2">
                        <BaseButton
                            color="primary"
                            content="Modifier"
                            class="flex-grow"
                            :route="
                                route(
                                    'backoffice.interest-points.edit',
                                    interestpoint.uuid,
                                )
                            "
                        />
                        <BaseButton
                            color="error"
                            content="Supprimer"
                            class="flex-grow"
                            onclick="confirm_delete.showModal()"
                        />
                    </div>
                    <div v-if="interestpoint.routes.length">
                        <h3 class="text-xl">Sentiers liés</h3>
                        <div
                            class="flex flex-col gap-2 max-h-[35vh] px-2 pb-8 overflow-x-scroll"
                        >
                            <AppHorizontalCard
                                v-for="sentier in interestpoint.routes"
                                :title="sentier.name"
                                :tags="tagsName(sentier.tags)"
                                :imgPath="getImgSrc(sentier.pictures[0].path)"
                                :imgAlt="sentier.pictures.at(0).title"
                                :href="
                                    route(
                                        'backoffice.routes.show',
                                        sentier.uuid,
                                    )
                                "
                                :isDone="sentier.isDone"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <dialog id="confirm_delete" class="modal">
                <div class="modal-box">
                    <h3 class="font-bold text-lg">
                        Supprimer ce point d'intérêt ?
                    </h3>
                    <p class="py-4">
                        <span class="text-error font-bold">Attention !</span
                        ><br />
                        Cette action est irréversible.
                    </p>
                    <div class="modal-action">
                        <form @submit.prevent="submit()">
                            <button class="btn btn-error" type="submit">
                                Supprimer ce point d'intérêt
                            </button>
                        </form>
                        <form method="dialog">
                            <button class="btn">Annuler</button>
                        </form>
                    </div>
                </div>
            </dialog>
        </template>
    </BackofficeLayout>
</template>

<style scoped></style>
