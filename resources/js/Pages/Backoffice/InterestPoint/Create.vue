<script setup>
import { reactive, ref, watch } from "vue";
import { Head, useForm } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";

import BasePrimaryButton from "@/Components/Base/BasePrimaryButton.vue";
import BaseInputError from "@/Components/Base/BaseInputError.vue";
import BaseSecondaryButton from "@/Components/Base/BaseSecondaryButton.vue";

import AppMapOnePin from "@/Components/App/AppMapOnePin.vue";
import AppBadgeHorizontalCard from "@/Components/App/AppBadgeHorizontalCard.vue";

const props = defineProps({
    tags: {
        type: Object,
        required: true,
    },
    badges: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    title: "",
    tag_id: "",
    badge_uuid: "",
    location: [],
    description: "",
    image: "",
});

const tags = reactive(props.tags.data);
const badges = reactive(props.badges.data);
const step = ref(1);
const maxStep = 4;

const handleFileUpload = (event, key) => {
    form[key] = event.target.files[0];
};

const submit = () => {
    form.hasErrors = false;
    form.errors = {};

    if (!form.title) {
        form.hasErrors = true;
        form.errors.title = "Le titre est obligatoire";
        return;
    }

    if (!form.tag_id) {
        form.hasErrors = true;
        form.errors.tag_id = "Le tag est obligatoire";
        return;
    }

    if (!form.location) {
        form.hasErrors = true;
        form.errors.location = "La localisation est obligatoire";
        return;
    }

    if (!form.description) {
        form.hasErrors = true;
        form.errors.description = "La description est obligatoire";
        return;
    }

    if (!form.image) {
        form.hasErrors = true;
        form.errors.image = "L'image est obligatoire";
        return;
    }

    form.post(route("backoffice.interest-points.store"), {
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
                <h1 class="text-2xl">Créer un point d'intérêt</h1>
                <form
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
                                Localiser
                            </li>
                            <li
                                class="step text-sm text-base-300"
                                @click="step = 3"
                                :class="{
                                    'step-primary': step >= 3,
                                    'text-primary': step >= 3,
                                }"
                            >
                                Image
                            </li>
                            <li
                                class="step text-sm text-base-300"
                                @click="step = 4"
                                :class="{
                                    'step-primary': step >= 4,
                                    'text-primary': step >= 4,
                                }"
                            >
                                Badge
                            </li>
                        </ul>
                    </div>
                    <div v-if="form.hasErrors">
                        <BaseInputError
                            message="Une erreur est survenue lors de la création de la route"
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
                                <span class="label-text">Localisation</span>
                            </div>
                            <div class="h-80 w-full border rounded-xl p-1">
                                <AppMapOnePin v-model="form.location" />
                            </div>
                            <BaseInputError :message="form.errors.location" />
                        </label>
                    </div>
                    <div v-show="step === 3" class="flex flex-col gap-4">
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
                    </div>
                    <div v-show="step === 4" class="flex flex-col gap-4">
                        <label class="form-control w-full" for="badge">
                            <div class="label">
                                <span class="label-text"
                                    >Choisir un badge :</span
                                >
                            </div>
                        </label>
                        <div
                            class="flex flex-col gap-2 w-full max-h-[80%] px-2 pb-20 overflow-x-scroll"
                        >
                            <template v-for="badge in badges" :key="badge.uuid">
                                <div
                                    class="flex flex-row items-center gap-2 w-full"
                                >
                                    <input
                                        type="radio"
                                        name="badge"
                                        class="radio"
                                        :value="badge.uuid"
                                        v-model="form.badge_uuid"
                                        :checked="
                                            form.badge_uuid === badge.uuid
                                        "
                                    />
                                    <AppBadgeHorizontalCard
                                        :badge="badge.name"
                                        :icon="badge.icon_path"
                                        class="flex-grow"
                                        @click="form.badge_uuid = badge.uuid"
                                    />
                                </div>
                            </template>
                        </div>
                        <BaseInputError :message="form.errors.badge_uuid" />
                    </div>
                    <div
                        class="absolute bottom-0 w-full p-6 left-0 bg-base-100 flex flex-col items-center"
                    >
                        <div
                            class="flex flex-row w-full h-full mb-16 gap-4 justify-center max-w-sm"
                        >
                            <BaseSecondaryButton
                                :type="step === 1 ? 'button' : 'reset'"
                                class="w-1/2"
                                @click="step !== 1 ? step-- : back()"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                {{ step === 1 ? "Annuler" : "Précédent" }}
                            </BaseSecondaryButton>
                            <BasePrimaryButton
                                type="button"
                                @click="step === maxStep ? submit() : step++"
                                class="w-1/2"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                {{ step === maxStep ? "Créer" : "Suivant" }}
                            </BasePrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </template>
    </BackofficeLayout>
</template>

<style scoped></style>
