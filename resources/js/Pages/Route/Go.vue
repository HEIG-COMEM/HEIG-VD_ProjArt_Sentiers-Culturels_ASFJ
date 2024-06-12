<script setup>
import { back, getImgPath } from "@/utils/helper";
import { computed, defineProps, onMounted, reactive, ref, watch } from "vue";
import { Head } from "@inertiajs/vue3";

import MobileAppLayout from "@/Layouts/AppLayout.vue";
import TheBottomSheets from "@/Components/The/TheBottomSheets.vue";
import AppMapGo from "@/Components/App/AppMapGo.vue";
import AppBadge from "@/Components/App/AppBadge.vue";
import BasePrimaryButton from "@/Components/Base/BasePrimaryButton.vue";
import { AlertCircle, ArrowLeft } from "@iconsans/vue/linear";
import AppStarRating from "@/Components/App/AppStarRating.vue";
import AppWeatherForecastCard from "@/Components/App/AppWeatherForecastCard.vue";

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

const showWeather = ref(false);

const isCloseToStart = ref(false);
const isCloseToFinish = ref(false);
const finishResponse = reactive({});
const rate = ref(0);

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

const getFirstTagsName = (tags, limit) =>
    tags.slice(0, limit).map((tag) => tag.name);

// Check if the user is close to the start of the route to allow him to start it
onMounted(() => {
    const options = {
        enableHighAccuracy: true,
        maximumAge: Infinity,
    };

    navigator.geolocation.getCurrentPosition(
        (position) => {
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
                        <div role="alert" class="alert alert-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>${data.success}</span>
                        </div>
                `;
                    }
                })
                .catch((error) => {
                    console.error(error);
                })
                .finally(() => {
                    setTimeout(() => {
                        const toast = document.querySelector("#toast-target");
                        toast.innerHTML = "";
                    }, 5000);
                });
        },
        null,
        options,
    );
});

// Check if the user is close enough to the end of the route to allow him to finish it
onMounted(() => {
    const options = {
        enableHighAccuracy: true,
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
    const API_ENDPOINT = `${window.location.origin}/api/badge/claim/${uuid}?lat=${userCoords.lat}&lng=${userCoords.lng}`;
    fetch(API_ENDPOINT)
        .then((response) => response.json())
        .then((data) => {
            if (data.error) {
                throw new Error(data.error);
            } else {
                const toast = document.querySelector("#toast-target");
                toast.innerHTML = `
                    <div role="alert" class="alert alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Badge collecté</span>
                    </div>`;
                currentInterestPoint.value.badge.isDone = true;
                setTimeout(() => {
                    toast.innerHTML = "";
                }, 2000);
            }
        })
        .catch((error) => {
            const toast = document.querySelector("#toast-target");
            toast.innerHTML = `
                <div role="alert" class="alert alert-error">
                              <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>${error.message}</span>
                        </div>`;
            setTimeout(() => {
                toast.innerHTML = "";
            }, 2000);
        });
};

const finish = () => {
    const API_ENDPOINT = `${window.location.origin}/api/route/go/${route.uuid}/finish?lat=${userCoords.lat}&lng=${userCoords.lng}`;
    fetch(API_ENDPOINT)
        .then((response) => response.json())
        .then((data) => {
            finishResponse.data = data;
            if (data.error) {
                throw new Error(data.error);
            }
        })
        .catch((error) => {
            console.error(error);
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
            console.error(error);
        });
};

const setRating = () => {
    if (!rate.value) {
        window.location.href = "/map";
    }
    const API_ENDPOINT = `${window.location.origin}/api/route/go/${route.uuid}/rate`;
    fetch(API_ENDPOINT, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ rate: rate.value }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.error) {
                throw new Error(data.error);
            } else {
                finish_modal.close();
                window.location.href = "/map";
            }
        })
        .catch((error) => {
            console.error(error);
        });
};
</script>

<template>
    <Head title="Sentier" />
    <MobileAppLayout>
        <template v-slot:main>
            <div
                id="toast-target"
                class="absolute z-10 w-full px-6 top-6"
            ></div>
            <!-- TOP BTNS -->
            <div class="absolute z-[1] p-6 top-0 left-0">
                <div>
                    <button
                        class="btn btn-circle btn-outline btn-primary bg-base-100 h-6"
                        @click="back()"
                    >
                        <ArrowLeft class="w-7 h-7" />
                    </button>
                </div>
            </div>
            <button
                class="absolute left-2 bottom-48 z-10 btn btn-circle btn-outline btn-primary bg-white"
                @click="showWeather = !showWeather"
            >
                <svg
                    class="w-7 h-7 text-primary"
                    viewBox="0 0 27 22"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M16.4762 7.28375C17.2418 7.01701 18.0468 6.88136 18.8575 6.8825C19.675 6.8825 20.4613 7.01875 21.195 7.26875M7.395 10.5113C7.05317 10.4448 6.70574 10.4113 6.3575 10.4113C3.39875 10.4125 1 12.7825 1 15.7063C1 18.63 3.39875 21 6.3575 21H18.8575C22.8025 21 26 17.84 26 13.9412C26 10.8512 23.9925 8.225 21.195 7.26875M7.395 10.5113C7.10139 9.72696 6.9515 8.89619 6.9525 8.05875C6.9525 4.16 10.15 1 14.095 1C17.77 1 20.7962 3.7425 21.195 7.26875M7.395 10.5113C8.08662 10.6456 8.745 10.9149 9.3325 11.3038L7.395 10.5113Z"
                    />
                    <path
                        d="M16.4762 7.28375C17.2418 7.01701 18.0468 6.88136 18.8575 6.8825C19.675 6.8825 20.4613 7.01875 21.195 7.26875M21.195 7.26875C23.9925 8.225 26 10.8512 26 13.9412C26 17.84 22.8025 21 18.8575 21H6.3575C3.39875 21 1 18.63 1 15.7063C1 12.7825 3.39875 10.4125 6.3575 10.4113C6.70574 10.4113 7.05317 10.4448 7.395 10.5113M21.195 7.26875C20.7963 3.7425 17.77 1 14.095 1C10.15 1 6.9525 4.16 6.9525 8.05875C6.9515 8.89619 7.10139 9.72696 7.395 10.5113M7.395 10.5113C8.08662 10.6456 8.745 10.9149 9.3325 11.3038"
                        stroke="#4c8c2b"
                        stroke-width="1.5"
                        stroke-linecap="round"
                    />
                </svg>
            </button>
            <AppWeatherForecastCard
                :lat="userCoords.lat"
                :long="userCoords.lng"
                v-model="showWeather"
            />
            <div class="h-[83vh] w-[100vw]">
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
                        <div class="font-bold text-lg">Es-tu sûr ?</div>
                        <div>
                            Si tu quittes ce sentier, tu perdras ta progression
                            ainsi que tous les badges et statistiques liés à ce
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
