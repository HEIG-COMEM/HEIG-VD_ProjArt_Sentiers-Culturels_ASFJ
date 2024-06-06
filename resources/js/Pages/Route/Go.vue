<script setup>
import { computed, defineProps, onMounted, reactive, ref, watch } from "vue";
import { Head } from "@inertiajs/vue3";

import MobileAppLayout from "@/Layouts/AppLayout.vue";
import TheBottomSheets from "@/Components/The/TheBottomSheets.vue";
import AppMapGo from "@/Components/App/AppMapGo.vue";
import AppBadge from "@/Components/App/AppBadge.vue";
import BasePrimaryButton from "@/Components/Base/BasePrimaryButton.vue";
import { AlertCircle } from "@iconsans/vue/linear";
import AppStarRating from "@/Components/App/AppStarRating.vue";

const props = defineProps({
    route: {
        type: Object,
        required: true,
    },
    resume: {
        type: Boolean,
        default: false,
        required: false,
    },
    auth: {
        type: Object,
        required: true,
    },
});

const user = reactive(props.auth.user);
const userCoords = reactive({ lat: 0, lng: 0 });
const route = reactive(props.route.data);
const interestpoints = reactive(route.interest_points);
const currIndex = reactive({ value: 0 });
const currentInterestPoint = computed(() => {
    return interestpoints[currIndex.value];
});

const isCloseToStart = ref(false);
const isCloseToFinish = ref(false);
const isSuccessfulFinish = ref(false);
const finishResponse = reactive({});
const rate = ref(0);

watch(rate, (newValue) => {
    console.log(newValue);
});

const duration = computed(() => {
    return Math.floor(route.duration / 3600) +
        " h " +
        (route.duration % 3600) / 60
        ? Math.floor((route.duration % 3600) / 60) + " min"
        : "";
});

const distance = computed(() => {
    return route.length > 1000
        ? (route.length / 1000).toFixed(2) + " km"
        : route.length + " m";
});

watch(currentInterestPoint, (newValue) => {
    console.log(newValue);
});

const hasNextIP = computed(() => {
    return currIndex.value < interestpoints.length - 1;
});
const nextIP = () => {
    if (currIndex.value < interestpoints.length - 1) {
        currIndex.value++;
    }
};

const hasPrevIP = computed(() => {
    return currIndex.value > 0;
});
const prevIP = () => {
    if (currIndex.value > 0) {
        currIndex.value--;
    }
};

const getImgPath = (path) => `/storage/pictures/${path}`;
const getFirstTagsName = (tags, limit) =>
    tags.slice(0, limit).map((tag) => tag.name);

// Check if the user is close to the start of the route to allow him to start it
onMounted(() => {
    navigator.geolocation.getCurrentPosition((position) => {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        const API_ENDPOINT = `${window.location.origin}/api/route/go/${route.uuid}?lat=${lat}&lng=${lng}`;
        fetch(API_ENDPOINT)
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    throw new Error(data.error);
                } else {
                    isCloseToStart.value = true;
                    const toast = document.querySelector("#toast-target");
                    toast.innerHTML = `
                    <div class="alert alert-success max-w-[90vw] overflow-scroll">
                    <p>${data.success}</p>
                </div>
                `;
                }
            })
            .catch((error) => {
                error.log(error);
            })
            .finally(() => {
                setTimeout(() => {
                    const toast = document.querySelector("#toast-target");
                    toast.innerHTML = "";
                }, 5000);
            });
    });
});

// Check if the user is close enough to the end of the route to allow him to finish it
onMounted(() => {
    const options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0,
    };
    setTimeout(() => {
        return navigator.geolocation.watchPosition(
            (position) => {
                userCoords.lat = position.coords.latitude;
                userCoords.lng = position.coords.longitude;

                const API_ENDPOINT = `${window.location.origin}/api/route/go/${route.uuid}/checkEnd?lat=${position.coords.latitude}&lng=${position.coords.longitude}`;
                fetch(API_ENDPOINT)
                    .then((response) => response.json())
                    .then((data) => {
                        if (!data.error) {
                            isCloseToFinish.value = data.success;
                        }
                    });
            },
            null,
            options,
        );
    }, 500);
});

// When the user clicks on the button to claim the badge
const claimBadge = (uuid) => {
    const API_ENDPOINT = `${window.location.origin}/api/badge/claim/${uuid}`;
    fetch(API_ENDPOINT)
        .then((response) => response.json())
        .then((data) => {
            if (data.error) {
                throw new Error(data.error);
            } else {
                const toast = document.querySelector("#toast-target");
                toast.innerHTML = `
                    <div class="alert alert-success max-w-[90vw] overflow-scroll">
                    <p>Badge collecté</p>
                </div>`;
                currentInterestPoint.value.badge.isDone = true;
                setTimeout(() => {
                    toast.innerHTML = "";
                }, 2000);
            }
        })
        .catch((error) => {
            console.log(error);
        });
};

const finish = () => {
    console.log("Finish");
    const API_ENDPOINT = `${window.location.origin}/api/route/go/${route.uuid}/finish?lat=${userCoords.lat}&lng=${userCoords.lng}`;
    console.log(API_ENDPOINT);
    fetch(API_ENDPOINT)
        .then((response) => response.json())
        .then((data) => {
            finishResponse.data = data;
            if (data.error) {
                throw new Error(data.error);
            } else {
                isSuccessfulFinish.value = true;
            }
        })
        .catch((error) => {
            console.log(error);
        });
};

const interupt = () => {
    const API_ENDPOINT = `${window.location.origin}/api/route/go/${route.uuid}/interrupt`;
    fetch(API_ENDPOINT)
        .then((response) => response.json())
        .then((data) => {
            if (data.error) {
                throw new Error(data.error);
            } else {
                window.location.href = "/map";
            }
        })
        .catch((error) => {
            console.log(error);
        });
};

const setRating = () => {
    if (!rate.value) return;
    // TODO: Post the rating to the API
};
</script>

<template>
    <Head title="Sentier" />
    <MobileAppLayout>
        <template v-slot:main>
            <div
                class="toast toast-top toast-center z-10"
                id="toast-target"
            ></div>
            <div class="h-[80vh] w-[100vw]">
                <span @click="nextIP()">Next ({{ hasNextIP }})</span>
                <span @click="prevIP()">Prev ({{ hasPrevIP }})</span>
                <span>{{ isCloseToStart }}</span>
                <AppMapGo
                    :route="route"
                    :currentInterestPointIndex="currIndex"
                />
            </div>
            <TheBottomSheets>
                <template v-if="isCloseToStart" v-slot:tab>
                    <div class="flex flex-row gap-4">
                        <img
                            class="h-16 w-16 rounded-xl object-cover"
                            :src="
                                getImgPath(
                                    currentInterestPoint.pictures.at(0).path,
                                )
                            "
                            alt="interest point image"
                        />
                        <div class="flex flex-col gap-2">
                            <h1 class="text-lg font-bold">
                                {{ currentInterestPoint.name }}
                            </h1>
                            <div class="text-sm flex flex-row gap-2">
                                <div
                                    v-for="(tag, index) in getFirstTagsName(
                                        currentInterestPoint.tags,
                                        3,
                                    )"
                                    :key="index"
                                    class="badge badge-primary truncate"
                                >
                                    {{ tag }}
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <div
                    v-if="isCloseToStart"
                    class="p-5 flex flex-col gap-5 h-full"
                >
                    <p
                        :class="{
                            'flex-grow': !currentInterestPoint.badge,
                        }"
                    >
                        {{ currentInterestPoint.description }}
                    </p>
                    <div
                        v-if="currentInterestPoint.badge"
                        class="flex flex-col gap-2 flex-grow"
                    >
                        <h2 class="text-lg font-bold">Badge</h2>
                        <AppBadge
                            :badge="currentInterestPoint.badge.name"
                            :icon="currentInterestPoint.badge.icon_path"
                        />
                        <template
                            v-if="!currentInterestPoint.badge.isDone && user"
                        >
                            <BasePrimaryButton
                                id="currentInterestPoint.badge.uuid"
                                @click="
                                    claimBadge(currentInterestPoint.badge.uuid)
                                "
                            >
                                Collecter le badge
                            </BasePrimaryButton>
                        </template>
                        <template v-if="!user">
                            <BasePrimaryButton
                                onclick="window.location.href = '/register'"
                            >
                                Créer un compte pour collecter le badge
                            </BasePrimaryButton>
                        </template>
                    </div>
                    <div
                        class="flex flex-row w-full border-t border-base-200 gap-6 pt-4 justify-between"
                    >
                        <div class="flex flex-col gap-1">
                            <p class="font-bold">{{ route.name }}</p>
                            <p>{{ duration }} - {{ distance }}</p>
                        </div>
                        <BasePrimaryButton
                            v-if="isCloseToFinish"
                            onclick="finish_modal.showModal()"
                            @click="finish()"
                            >Terminer le sentier</BasePrimaryButton
                        >
                        <BasePrimaryButton
                            v-else
                            class="btn-error"
                            onclick="interrupt_modal.showModal()"
                            >Interrompre</BasePrimaryButton
                        >
                    </div>
                </div>
                <template v-if="!isCloseToStart" v-slot:tab>
                    <p class="text-center text-error">
                        Tu n'es pas encore assez proche du point de départ pour
                        commencer le sentier.
                    </p>
                </template>
            </TheBottomSheets>

            <!-- INTERUPT MODAL -->
            <dialog id="interrupt_modal" class="modal">
                <div
                    class="modal-box flex flex-col gap-6 justify-center text-center"
                >
                    <div class="flex flex-col w-full items-center">
                        <AlertCircle class="text-error h-16 w-16" />
                    </div>
                    <div class="flex flex-col text-center gap-2">
                        <div class="font-bold text-lg">Es-tu sûre ?</div>
                        <div>
                            Si tu quittes ce sentier, tu perdras ta progression
                            ainsi que tout les badges et statistiques liés à ce
                            sentier.
                        </div>
                    </div>
                    <div class="flex flex-row justify-between gap-4">
                        <form method="dialog" class="flex-grow w-1/3">
                            <button class="btn btn-base w-full">Retour</button>
                        </form>
                        <BasePrimaryButton
                            class="btn-error flex-grow w-1/3"
                            @click="interupt()"
                            >Interrompre</BasePrimaryButton
                        >
                    </div>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>

            <!-- FINISH MODAL -->
            <dialog id="finish_modal" class="modal">
                <div
                    class="modal-box flex flex-col gap-6 justify-center text-center"
                >
                    <template v-if="user">
                        <template v-if="finishResponse.data?.error">
                            <p class="text-error">
                                Oups ! Une erreur est survenue.
                            </p>
                        </template>
                        <template v-else>
                            <template
                                v-if="
                                    route.badge &&
                                    finishResponse.data?.has_won_badge
                                "
                            >
                                <div class="flex flex-col w-full items-center">
                                    <AppBadge
                                        class="w-full"
                                        :badge="route.badge.name"
                                        :icon="route.badge.icon_path"
                                    />
                                </div>
                                <div class="flex flex-col text-center gap-2">
                                    <div class="font-bold text-lg">
                                        Félicitation tu as gagné un badge !
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <div class="flex flex-col text-center gap-2">
                                    <div class="font-bold text-lg">
                                        Félicitation tu as terminé le sentier
                                        {{ route.name }} !
                                    </div>
                                </div>
                            </template>
                            <div>
                                <p>Donne une note au sentier</p>
                                <AppStarRating
                                    :rating="0"
                                    :isUserInteraction="true"
                                    v-model="rate"
                                />
                            </div>
                        </template>
                        <div class="flex flex-row justify-between gap-4">
                            <BasePrimaryButton
                                class="flex-grow"
                                @click="setRating()"
                                >Confirmer</BasePrimaryButton
                            >
                        </div>
                    </template>
                    <template v-else>
                        <div class="flex flex-col text-center gap-2">
                            <div class="font-bold text-lg">Félicitation !</div>
                            <div>
                                Tu as terminé le sentier {{ route.name }}.
                            </div>
                            <div class="text-sm text-primary mt-6">
                                Crée-toi un compte pour débloquer de nouvelles
                                fonctionnalités
                            </div>
                        </div>
                        <div class="flex flex-row justify-between gap-4">
                            <form method="dialog" class="flex-grow w-1/3">
                                <button
                                    class="btn btn-base w-full"
                                    onclick="window.location.href = '/map'"
                                >
                                    Fermer
                                </button>
                            </form>
                            <BasePrimaryButton
                                class="flex-grow w-1/3"
                                onclick="window.location.href = '/register'"
                                >Créer un compte</BasePrimaryButton
                            >
                        </div>
                    </template>
                </div>
            </dialog>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
