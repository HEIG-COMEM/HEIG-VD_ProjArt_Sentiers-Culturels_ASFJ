<script setup>
import { back, getImgPath } from "@/utils/helper";
import { reactive, ref, computed, watch } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";

import BackofficeLayout from "@/Layouts/BackofficeLayout.vue";

import BasePrimaryButton from "@/Components/Base/BasePrimaryButton.vue";
import BaseInputError from "@/Components/Base/BaseInputError.vue";
import BaseSecondaryButton from "@/Components/Base/BaseSecondaryButton.vue";

import {
    PlusCircle,
    Trash,
    Plus,
    Cross,
    ArrowUp2,
    ArrowDown2,
} from "@iconsans/vue/linear";

import AppHorizontalCard from "@/Components/App/AppHorizontalCard.vue";
import AppBadgeHorizontalCard from "@/Components/App/AppBadgeHorizontalCard.vue";
import BaseSearchBar from "@/Components/Base/BaseSearchBar.vue";
import AppMapDirectionReactive from "@/Components/App/AppMapDirectionReactive.vue";

const props = defineProps({
    route: {
        type: Object,
        required: true,
    },
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
    badges: {
        type: Object,
        required: true,
    },
    seasons: {
        type: Object,
        required: true,
    },
});

const route = reactive(props.route.data);
const tags = reactive(props.tags.data);
const difficulties = reactive(props.difficulties.data);
const interestpoints = reactive(props.interestpoints.data);
const badges = reactive(props.badges.data);
const seasons = reactive(props.seasons.data);
const seasonsAdded = reactive(route.seasons);
const interestpointsAdded = reactive(route.interest_points);
const tagsAdded = reactive(route.tags);
const showForm = ref(true);
const step = ref(1);
const maxStep = 4;

const form = useForm({
    title: route.name,
    tags: route.tags,
    difficulty_id: route.difficulty.id,
    description: route.description,
    image: "",
    badge_uuid: route.badge?.uuid,
    interestpoints: [],
    seasons: [],
});

const filteredSeasons = computed(() => {
    return seasons.filter(
        (season) =>
            !seasonsAdded.some((seasonAdded) => seasonAdded.id === season.id),
    );
});

watch(seasonsAdded, () => {
    form.seasons = seasonsAdded.map((season) => {
        return season.id;
    });
});

// Execute on first load
form.seasons = seasonsAdded.map((season) => {
    return season.id;
});

const handleSeasonClick = (season) => {
    if (seasonsAdded.includes(season)) {
        seasonsAdded.splice(seasonsAdded.indexOf(season), 1);
    } else {
        seasonsAdded.push(season);
    }
};

const interestpointsSearch = ref("");
const remainingInterestpoints = computed(() => {
    return interestpoints.filter(
        (interestpoint) =>
            !interestpointsAdded.some(
                (interestpointAdded) =>
                    interestpointAdded.id === interestpoint.id,
            ),
    );
});
const filteredInterestpoints = computed(() => {
    return remainingInterestpoints.value.filter((interestpoint) =>
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

// Execute on first load
form.interestpoints = interestpointsAdded.map((interestpoint) => {
    return interestpoint.id;
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

const changeOrder = (interestpoint, direction) => {
    const index = interestpointsAdded.indexOf(interestpoint);
    const newIndex = index + direction;

    if (newIndex < 0 || newIndex >= interestpointsAdded.length) {
        return;
    }

    const indexes = [index, newIndex].sort((a, b) => a - b);
    interestpointsAdded.splice(
        indexes[0],
        2,
        interestpointsAdded[indexes[1]],
        interestpointsAdded[indexes[0]],
    );
};

const tagsSearch = ref("");
const remainingTags = computed(() => {
    return tags.filter(
        (tag) => !tagsAdded.some((tagAdded) => tagAdded.id === tag.id),
    );
});

const filteredTags = computed(() => {
    return remainingTags.value.filter((tag) =>
        tag.name.toLowerCase().includes(tagsSearch.value.toLowerCase()),
    );
});

watch(tagsAdded, () => {
    form.tags = tagsAdded.map((tag) => {
        return tag.id;
    });
});

// Execute on first load
form.tags = tagsAdded.map((tag) => {
    return tag.id;
});

const handleTagClick = (tag) => {
    if (tagsAdded.includes(tag)) {
        tagsAdded.splice(tagsAdded.indexOf(tag), 1);
    } else {
        tagsAdded.push(tag);
    }
};

const imagePreview = ref(
    `${document.location.origin}/storage/pictures/${route.pictures.at(0).path}`,
);
const handleFileUpload = (event, key) => {
    form[key] = event.target.files[0];
    imagePreview.value = URL.createObjectURL(form[key]);
};

const submit = () => {
    form.hasErrors = false;
    form.errors = {};

    if (!form.title) {
        form.errors.title = "Le titre est obligatoire";
        form.hasErrors = true;
    }

    if (!form.tags.length) {
        form.errors.tags = "Au moins un tag est obligatoire";
        form.hasErrors = true;
    }

    if (!form.seasons.length) {
        form.errors.seasons = "Au moins une saison est obligatoire";
        form.hasErrors = true;
    }

    if (!form.difficulty_id) {
        form.errors.difficulty_id = "La difficulté est obligatoire";
        form.hasErrors = true;
    }

    if (!form.description) {
        form.errors.description = "La description est obligatoire";
        form.hasErrors = true;
    }

    if (form.interestpoints.length < 2) {
        form.errors.interestpoints =
            "Veuillez ajouter au moins deux points d'intérêt";
        form.hasErrors = true;
    }

    if (form.hasErrors) {
        return;
    }

    // Using a POST request to send the form data and fake a PUT request
    // Due to PHP not supporting file uploads with PUT requests
    router.post(`/backoffice/routes/${route.uuid}`, {
        _method: "PUT",
        // decompose the form
        ...form,
    });
};
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeLayout>
        <template v-slot:main>
            <div class="flex flex-col gap-8 p-6 h-full w-full max-w-sm">
                <h1 class="text-2xl">Éditer un sentier</h1>
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
                                Image
                            </li>
                            <li
                                class="step text-sm text-base-300"
                                @click="step = 3"
                                :class="{
                                    'step-primary': step >= 3,
                                    'text-primary': step >= 3,
                                }"
                            >
                                Badge
                            </li>
                            <li
                                class="step text-sm text-base-300"
                                @click="step = 4"
                                :class="{
                                    'step-primary': step >= 4,
                                    'text-primary': step >= 4,
                                }"
                            >
                                Points d'intérêt
                            </li>
                        </ul>
                        <BaseInputError
                            v-if="form.hasErrors"
                            message="Une ou plusieurs erreurs sont présentes dans le formulaire"
                            class="w-full"
                        />
                    </div>
                    <div
                        v-show="step === 1"
                        class="flex flex-col gap-4 h-[55vh] pb-14 overflow-x-scroll"
                    >
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
                            <div
                                class="flex flex-row gap-2 text-primary items-center"
                                onclick="showSeasonsPicker.showModal()"
                            >
                                <div class="label">
                                    <span class="label-text"
                                        >Saison
                                        <span class="text-xs text-gray-500">
                                            (au moins une)
                                        </span></span
                                    >
                                </div>
                                <span class="content-center h-6 w-6"
                                    ><PlusCircle class="h-full w-full"
                                /></span>
                            </div>
                            <div class="w-full flex flex-wrap gap-4">
                                <template
                                    v-for="season in seasonsAdded"
                                    :key="season.id"
                                >
                                    <div
                                        class="badge badge-primary badge-outline gap-2 cursor-pointer"
                                        @click="handleSeasonClick(season)"
                                    >
                                        <span class="h-6 w-6">
                                            <Cross class="h-full w-full" />
                                        </span>
                                        <span>{{ season.name }}</span>
                                    </div>
                                </template>
                            </div>
                            <BaseInputError :message="form.errors.seasons" />
                        </label>
                        <label class="form-control w-full">
                            <div
                                class="flex flex-row gap-2 text-primary items-center"
                                onclick="showTagsPicker.showModal()"
                            >
                                <div class="label">
                                    <span class="label-text"
                                        >Tag
                                        <span class="text-xs text-gray-500">
                                            (au moins un)
                                        </span></span
                                    >
                                </div>
                                <span class="content-center h-6 w-6"
                                    ><PlusCircle class="h-full w-full"
                                /></span>
                            </div>
                            <div
                                class="w-full flex flex-wrap gap-y-2 gap-x-4 px-2"
                            >
                                <template
                                    v-for="tag in tagsAdded"
                                    :key="tag.id"
                                >
                                    <div class="badge badge-primary gap-2">
                                        <span
                                            class="h-6 w-6 cursor-pointer"
                                            @click="handleTagClick(tag)"
                                        >
                                            <Cross class="h-full w-full" />
                                        </span>
                                        <span>{{ tag.name }}</span>
                                    </div>
                                </template>
                            </div>
                            <BaseInputError :message="form.errors.tags" />
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
                        <div v-if="imagePreview">
                            <p class="text-sm text-base-300">
                                Prévisualisation de l'image :
                            </p>
                            <img
                                :src="imagePreview"
                                alt="Prévisualisation de l'image"
                                class="rounded-xl w-full h-80 object-cover"
                            />
                        </div>
                    </div>
                    <div
                        v-show="step === 3"
                        class="flex flex-col gap-4 items-center w-full"
                    >
                        <label class="form-control w-full" for="badge">
                            <div class="label flex flex-row justify-between">
                                <span class="label-text"
                                    >Choisir un badge :
                                    <span class="text-xs text-gray-500">
                                        (optionnel)
                                    </span></span
                                >
                                <span
                                    v-show="form.badge_uuid"
                                    @click="form.badge_uuid = ''"
                                    class="text-error"
                                    ><Trash
                                /></span>
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
                        v-show="step === 4"
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
                                class="flex flex-row gap-2 w-full items-center px-2"
                                v-for="interestpoint in interestpointsAdded"
                                :key="interestpoint.id"
                            >
                                <div class="flex flex-col gap-1 cursor-pointer">
                                    <span
                                        class="cursor-pointer"
                                        @click="changeOrder(interestpoint, -1)"
                                        ><ArrowUp2
                                    /></span>
                                    <span
                                        class="cursor-pointer"
                                        @click="changeOrder(interestpoint, +1)"
                                        ><ArrowDown2
                                    /></span>
                                </div>
                                <AppHorizontalCard
                                    :title="interestpoint.name"
                                    :imgPath="
                                        getImgPath(
                                            interestpoint.pictures.at(0).path,
                                        )
                                    "
                                    :displayIsDone="false"
                                    class="flex-grow"
                                />
                                <span
                                    class="content-center h-6 w-6 text-error cursor-pointer"
                                    @click="
                                        handleInterestpointClick(interestpoint)
                                    "
                                    ><Trash class="h-full w-full"
                                /></span>
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
                                {{
                                    step === maxStep
                                        ? "Modifier le sentier"
                                        : "Suivant"
                                }}
                            </BasePrimaryButton>
                        </div>
                    </div>
                </form>
                <div v-show="!showForm" class="h-full">
                    <AppMapDirectionReactive
                        v-model="interestpointsAdded"
                        class="rounded-xl"
                    />
                </div>

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
                                            getImgPath(
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

                <!-- SEASONS PICKER -->
                <dialog id="showSeasonsPicker" class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg">
                            Choisir une ou plusieurs saisons
                        </h3>
                        <div
                            class="py-4 px-1 flex flex-col gap-2 max-h-[60vh] overflow-x-scroll"
                        >
                            <div class="w-full flex flex-wrap gap-4">
                                <template
                                    v-for="season in filteredSeasons"
                                    :key="season.id"
                                >
                                    <div
                                        class="badge badge-primary badge-outline gap-2 cursor-pointer"
                                        @click="handleSeasonClick(season)"
                                    >
                                        <span class="h-6 w-6">
                                            <Plus class="h-full w-full" />
                                        </span>
                                        <span>{{ season.name }}</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="modal-action">
                            <form method="dialog">
                                <button class="btn btn-primary">Valider</button>
                            </form>
                        </div>
                    </div>
                </dialog>

                <!-- TAGS PICKER -->
                <dialog id="showTagsPicker" class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg">
                            Choisir un ou plusieurs tags
                        </h3>
                        <div
                            class="py-4 px-1 flex flex-col gap-2 max-h-[60vh] overflow-x-scroll"
                        >
                            <div>
                                <BaseSearchBar
                                    placeholder="Rechercher un tag"
                                    v-model="tagsSearch"
                                />
                            </div>
                            <div class="w-full flex flex-wrap gap-4">
                                <template
                                    v-for="tag in filteredTags"
                                    :key="tag.id"
                                >
                                    <div
                                        class="badge badge-primary gap-2 cursor-pointer"
                                        @click="handleTagClick(tag)"
                                    >
                                        <span class="h-6 w-6">
                                            <Plus class="h-full w-full" />
                                        </span>
                                        <span>{{ tag.name }}</span>
                                    </div>
                                </template>
                                <span
                                    class="text-xs text-gray-500"
                                    v-show="!filteredTags.length"
                                >
                                    Aucun tag disponnible
                                </span>
                            </div>
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
