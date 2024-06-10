<script setup>
import { back } from "@/utils/helper";
import { Head, useForm } from "@inertiajs/vue3";
import MobileAppLayout from "@/Layouts/AppLayout.vue";

import {
    ArrowLeft,
    UserCircle2,
    Setting,
    ArrowRight2,
} from "@iconsans/vue/linear";

import { Link } from "@inertiajs/vue3";
import BaseLink from "@/Components/Base/BaseLink.vue";
import { reactive } from "vue";
import BaseSecondaryButton from "@/Components/Base/BaseSecondaryButton.vue";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
});

const user = reactive(props.auth.user);
const logoutForm = useForm({});

const logout = () => {
    logoutForm.post(route("logout"), {
        preserveScroll: true,
        onSuccess: () => {
            window.location.href = route("home");
        },
    });
};
</script>

<template>
    <Head title="Paramètres" />
    <MobileAppLayout>
        <template v-slot:main>
            <div class="h-full w-full p-6 flex flex-col gap-2">
                <div class="flex flex-row justify-between">
                    <div class="rounded-full border-black">
                        <button
                            class="btn btn-circle btn-outline bg-base-100 border-base-200 min-h-0 h-7 w-7"
                            @click="back()"
                        >
                            <ArrowLeft class="w-6 h-6" />
                        </button>
                    </div>
                    <div class="flex flex-row gap-4 justify-end">
                        <Link :href="route('profile.account')">
                            <UserCircle2 class="h-7 w-7" />
                        </Link>
                    </div>
                </div>
                <h1 class="text-2xl font-medium">Paramètres</h1>
                <div
                    class="absolute bottom-0 left-0 flex flex-col w-full min-h-[85vh] rounded-xl shadow-top-sm"
                >
                    <div class="px-6 pt-6 flex flex-col gap-5">
                        <BaseLink
                            :href="route('settings.tutorial')"
                            class="decoration-transparent"
                        >
                            <div
                                class="flex flex-row border-b border-base-300 items-center justify-between p-1"
                            >
                                <p>Comment ça marche ?</p>
                                <span><ArrowRight2 class="w-4 h-4" /></span>
                            </div>
                        </BaseLink>
                        <BaseLink
                            :href="route('settings.downloads')"
                            class="decoration-transparent"
                        >
                            <div
                                class="flex flex-row border-b border-base-300 items-center justify-between p-1"
                            >
                                <p>Gestion des téléchargements</p>
                                <span><ArrowRight2 class="w-4 h-4" /></span>
                            </div>
                        </BaseLink>
                        <BaseLink
                            v-if="user.role_int"
                            :href="route('backoffice')"
                            class="decoration-transparent"
                        >
                            <div
                                class="flex flex-row border-b border-base-300 items-center justify-between p-1"
                            >
                                <p>Accès back-office</p>
                                <span><ArrowRight2 class="w-4 h-4" /></span>
                            </div>
                        </BaseLink>
                        <BaseLink
                            v-else
                            href=""
                            class="decoration-transparent text-base-300 cursor-not-allowed hover:text-base-300"
                            aria-disabled="true"
                        >
                            <div
                                class="flex flex-row border-b border-base-300 items-center justify-between p-1"
                            >
                                <p>Demande d’accès admin</p>
                                <span><ArrowRight2 class="w-4 h-4" /></span>
                            </div>
                        </BaseLink>
                        <BaseLink
                            :href="route('settings.contact')"
                            class="decoration-transparent"
                        >
                            <div
                                class="flex flex-row border-b border-base-300 items-center justify-between p-1"
                            >
                                <p>Contact</p>
                                <span><ArrowRight2 class="w-4 h-4" /></span>
                            </div>
                        </BaseLink>
                        <div>
                            <BaseSecondaryButton class="mt-16" @click="logout()"
                                >Se déconnecter</BaseSecondaryButton
                            >
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
