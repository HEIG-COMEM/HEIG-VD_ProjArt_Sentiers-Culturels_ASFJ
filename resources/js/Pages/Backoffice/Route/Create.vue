<script setup>
import { reactive, ref, computed, watch } from "vue";
import { Head, useForm } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";

import BasePrimaryButton from "@/Components/Base/BasePrimaryButton.vue";
import BaseInputError from "@/Components/Base/BaseInputError.vue";
import BaseSecondaryButton from "@/Components/Base/BaseSecondaryButton.vue";

import { PlusCircle, Trash } from "@iconsans/vue/linear";

import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";
import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";

const props = defineProps({
    tags: {
        type: Object,
        required: true,
    },
    difficulties: {
        type: Object,
        required: true,
    },
    interestpoints: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    title: "",
    tag_id: "Tag",
    difficulty_id: "Difficulté",
    description: "",
    image: "",
    badge: "",
    interestpoints: [],
});

const tags = reactive(props.tags.data);
const difficulties = reactive(props.difficulties.data);
const interestpoints = reactive(props.interestpoints.data);
const interestpointsAdded = reactive([]);
const showForm = ref(true);
const step = ref(1);
const maxStep = 3;

const interestpointsSearch = ref("");
const filteredInterestpoints = computed(() => {
    return interestpoints.filter((interestpoint) =>
        interestpoint.name
            .toLowerCase()
            .includes(interestpointsSearch.value.toLowerCase()),
    );
});

watch(interestpointsAdded, () => {
    form.interestpoints = interestpointsAdded.map((interestpoint) => {
        return interestpoint.id;
    });
});

const handleInterestpointClick = (interestpoint) => {
    if (interestpointsAdded.includes(interestpoint)) {
        interestpointsAdded.splice(
            interestpointsAdded.indexOf(interestpoint),
            1,
        );
    } else {
        interestpointsAdded.push(interestpoint);
    }
};

const handleFileUpload = (event, key) => {
    form[key] = event.target.files[0];
};
const url = new URL(window.location.href);
const getImgSrc = (path) => {
    return `${url.origin}/storage/pictures/${path}`;
};

const submit = () => {
    if (form.interestpoints.length < 2) {
        form.errors.interestpoints =
            "Veuillez ajouter au moins deux points d'intérêt";
        return;
    }
    form.post(route("backoffice.routes.store"), {
        // onFinish: () => form.reset(),
    });
};

const back = () => {
    window.history.back();
};
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeLayout>
        <template v-slot:main>
            <div class="flex flex-col gap-8 p-6 h-full w-full max-w-sm">
                <h1 class="text-2xl">Créer un sentier</h1>
                <!-- TOGGLE MENU -->
                <div class="flex flex-row justify-center w-full">
                    <div
                        class="flex flex-col gap-1 w-40 cursor-pointer text-center"
                        :class="{
                            'text-primary': showForm,
                            'text-base-200': !showForm,
                        }"
                        @click="showForm = true"
                    >
                        <p class="pl-6 pr-4 font-medium">Formulaire</p>
                        <span
                            class="w-full h-1 rounded-l-full"
                            :class="{
                                'bg-primary': showForm,
                                'bg-base-200': !showForm,
                            }"
                        ></span>
                    </div>
                    <div
                        class="flex flex-col gap-1 w-40 cursor-pointer text-center"
                        :class="{
                            'text-primary': !showForm,
                            'text-base-200': showForm,
                        }"
                        @click="showForm = false"
                    >
                        <p class="pl-4 pr-6 font-medium">Carte</p>
                        <span
                            class="w-full h-1 rounded-r-full"
                            :class="{
                                'bg-primary': !showForm,
                                'bg-base-200': showForm,
                            }"
                        ></span>
                    </div>
                </div>
                <form
                    v-show="showForm"
                    @submit.prevent="submit"
                    class="flex flex-col gap-6 justify-center"
                >
                    <div>
                        <ul class="steps w-full">
                            <li
                                class="step text-sm text-base-300"
                                @click="step = 1"
                                :class="{
                                    'step-primary': step >= 1,
                                    'text-primary': step >= 1,
                                }"
                            >
                                Informations
                            </li>
                            <li
                                class="step text-sm text-base-300"
                                @click="step = 2"
                                :class="{
                                    'step-primary': step >= 2,
                                    'text-primary': step >= 2,
                                }"
                            >
                                Images
                            </li>
                            <li
                                class="step text-sm text-base-300"
                                @click="step = 3"
                                :class="{
                                    'step-primary': step >= 3,
                                    'text-primary': step >= 3,
                                }"
                            >
                                Points d'intérêt
                            </li>
                        </ul>
                    </div>
                    <div v-if="form.hasErrors">
                        <BaseInputError
                            message="Une erreur est survenue lors de la création du sentier"
                            class="w-full"
                        />
                    </div>
                    <div v-show="step === 1" class="flex flex-col gap-4">
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text">Titre</span>
                            </div>
                            <input
                                type="text"
                                placeholder="Titre"
                                v-model="form.title"
                                class="input input-bordered w-full"
                                name="title"
                            />
                            <BaseInputError :message="form.errors.title" />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text">Tag</span>
                            </div>
                            <select
                                class="select select-bordered w-full"
                                v-model="form.tag_id"
                            >
                                <option disabled selected></option>
                                <option
                                    v-for="tag in tags"
                                    :key="tag.id"
                                    :value="tag.id"
                                >
                                    {{ tag.name }}
                                </option>
                            </select>

                            <BaseInputError :message="form.errors.tag_id" />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text">Difficulté</span>
                            </div>
                            <select
                                class="select select-bordered w-full"
                                v-model="form.difficulty_id"
                            >
                                <option disabled selected></option>
                                <option
                                    v-for="difficulty in difficulties"
                                    :key="difficulty.id"
                                    :value="difficulty.id"
                                >
                                    {{ difficulty.name }}
                                </option>
                            </select>

                            <BaseInputError
                                :message="form.errors.difficulty_id"
                            />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text">Déscription</span>
                            </div>
                            <textarea
                                class="textarea textarea-bordered"
                                placeholder="Déscription"
                                v-model="form.description"
                            ></textarea>
                            <BaseInputError
                                :message="form.errors.description"
                            />
                        </label>
                    </div>
                    <div v-show="step === 2" class="flex flex-col gap-4">
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text">Image</span>
                            </div>
                            <input
                                type="file"
                                class="file-input file-input-primary file-input-bordered w-full"
                                name="image"
                                accept="image/*"
                                v-on:change="handleFileUpload($event, 'image')"
                            />
                            <BaseInputError :message="form.errors.image" />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text"
                                    >Badge
                                    <span class="text-xs text-gray-500"
                                        >(Facultatif)</span
                                    ></span
                                >
                            </div>
                            <input
                                type="file"
                                class="file-input file-input-primary file-input-bordered w-full"
                                name="badge"
                                accept="image/*"
                                v-on:change="handleFileUpload($event, 'badge')"
                            />
                            <BaseInputError :message="form.errors.badge" />
                        </label>
                    </div>
                    <div
                        v-show="step === 3"
                        class="flex flex-col gap-4 items-center w-full"
                    >
                        <BaseInputError :message="form.errors.interestpoints" />
                        <div
                            class="flex flex-row gap-2 text-primary"
                            onclick="showInterestpointsPicker.showModal()"
                        >
                            Ajouter un point d’intérêt
                            <span class="content-center h-6 w-6"
                                ><PlusCircle class="h-full w-full"
                            /></span>
                        </div>
                        <div
                            class="flex flex-col gap-4 max-h-[50vh] pb-14 overflow-y-auto items-center w-full"
                        >
                            <div
                                class="flex flex-row gap-2 w-full items-center"
                                v-for="interestpoint in interestpointsAdded"
                                :key="interestpoint.id"
                            >
                                <span
                                    class="content-center h-6 w-6 text-error"
                                    @click="
                                        handleInterestpointClick(interestpoint)
                                    "
                                    ><Trash class="h-full w-full"
                                /></span>
                                <AppHorizontalCard
                                    :title="interestpoint.name"
                                    :imgPath="
                                        getImgSrc(
                                            interestpoint.pictures.at(0).path,
                                        )
                                    "
                                    :displayIsDone="false"
                                    class="flex-grow"
                                />
                            </div>
                        </div>
                    </div>
                    <div
                        class="absolute bottom-0 w-full p-6 left-0 bg-base-100 flex flex-col items-center"
                    >
                        <div
                            class="flex flex-row w-full h-full mb-16 gap-4 justify-center max-w-sm"
                        >
                            <BaseSecondaryButton
                                :type="step > 1 ? 'button' : 'button'"
                                class="w-1/2"
                                @click="step > 1 ? step-- : back()"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                {{ step === 1 ? "Annuler" : "Précédent" }}
                            </BaseSecondaryButton>
                            <BasePrimaryButton
                                type="button"
                                @click="step < maxStep ? step++ : submit()"
                                class="w-1/2"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                {{ step === maxStep ? "Créer" : "Suivant" }}
                            </BasePrimaryButton>
                        </div>
                    </div>
                </form>
                <div v-show="!showForm">MAP</div>

                <!-- INTEREST POINT PICKER -->
                <dialog id="showInterestpointsPicker" class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg">
                            Choisir un point d'intérêt
                        </h3>
                        <div
                            class="py-4 px-1 flex flex-col gap-2 max-h-[60vh] overflow-x-scroll"
                        >
                            <div>
                                <BaseSearchBar
                                    placeholder="Rechercher un point d'intérêt"
                                    v-model="interestpointsSearch"
                                />
                            </div>
                            <template
                                v-for="interestpoint in filteredInterestpoints"
                                :key="interestpoint.id"
                                class="w-full"
                            >
                                <div
                                    class="flex flex-row gap-2 items-center w-full"
                                >
                                    <input
                                        type="checkbox"
                                        :checked="
                                            interestpointsAdded.includes(
                                                interestpoint,
                                            )
                                        "
                                        @click="
                                            handleInterestpointClick(
                                                interestpoint,
                                            )
                                        "
                                        class="checkbox"
                                    />
                                    <AppHorizontalCard
                                        :title="interestpoint.name"
                                        :imgPath="
                                            getImgSrc(
                                                interestpoint.pictures.at(0)
                                                    .path,
                                            )
                                        "
                                        :displayIsDone="false"
                                        @click="
                                            handleInterestpointClick(
                                                interestpoint,
                                            )
                                        "
                                        class="flex-grow"
                                    />
                                </div>
                            </template>
                        </div>
                        <div class="modal-action">
                            <form method="dialog">
                                <button class="btn btn-primary">Valider</button>
                            </form>
                        </div>
                    </div>
                </dialog>
            </div>
        </template>
    </BackofficeLayout>
</template>

<style scoped></style>
