<script setup>
import { back } from "@/utils/helper";
import { reactive, ref, watch } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ArrowLeft, UserCircle2, Setting } from "@iconsans/vue/linear";

import MobileAppLayout from "@/Layouts/AppLayout.vue";
import BaseInputError from "@/Components/Base/BaseInputError.vue";
import BasePrimaryButton from "@/Components/Base/BasePrimaryButton.vue";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
});

const PROFILE_PICTURE_API = "https://api.dicebear.com/8.x/initials/svg?seed=";
const user = reactive(props.auth.user);
const hasChanged = ref(false);
const sucessMessage = ref(null);

const form = useForm({
    firstname: user.firstname,
    lastname: user.lastname,
    email: user.email,
});

watch(
    form,
    () => {
        hasChanged.value = true;
    },
    { deep: true },
);

const submit = () => {
    form.put(route("profile.account.update"), {
        preserveScroll: true,
        onSuccess: () => {
            hasChanged.value = false;
            sucessMessage.value = "Vos informations ont été mises à jour";
            setTimeout(() => {
                window.location.reload();
            }, 300);
        },
    });
};
</script>

<template>
    <Head title="Détails" />
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
                        <Link :href="route('profile.settings')">
                            <Setting class="h-7 w-7" />
                        </Link>
                    </div>
                </div>
                <h1 class="text-2xl font-medium">Détails</h1>
                <!-- TODO : edit href path, img-path, img-alt -->
                <div class="flex flex-col gap-6 mt-6">
                    <div class="flex flex-col gap-4 items-center">
                        <div class="avatar">
                            <div
                                class="w-24 rounded-full border border-base-300"
                            >
                                <img
                                    :src="`${PROFILE_PICTURE_API}${user.firstname}+${user.lastname}`"
                                />
                            </div>
                        </div>
                        <div
                            class="flex flex-row items-center justify-between gap-4"
                        >
                            <h2 class="text-lg font-medium">
                                {{ user.firstname }} {{ user.lastname }}
                            </h2>
                        </div>
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-full h-[60vh] rounded-xl shadow-top-sm"
                    >
                        <div class="px-6 pt-6 flex flex-col gap-5">
                            <span
                                v-if="sucessMessage"
                                class="text-primary text-sm self-center"
                                >{{ sucessMessage }}</span
                            >
                            <form
                                @submit.prevent
                                class="flex flex-col justify-center items-center gap-4"
                            >
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text">Prénom</span>
                                    </div>
                                    <input
                                        type="text"
                                        placeholder="Prénom"
                                        v-model="form.firstname"
                                        class="input input-bordered w-full max-w-xs"
                                        name="firstname"
                                        required
                                    />
                                    <BaseInputError
                                        :message="form.errors.firstname"
                                    />
                                </label>
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text">Nom</span>
                                    </div>
                                    <input
                                        type="text"
                                        placeholder="Nom"
                                        v-model="form.lastname"
                                        class="input input-bordered w-full max-w-xs"
                                        name="lastname"
                                        required
                                    />
                                    <BaseInputError
                                        :message="form.errors.lastname"
                                    />
                                </label>
                                <label class="form-control w-full max-w-xs">
                                    <div class="label">
                                        <span class="label-text"
                                            >Adresse e-mail</span
                                        >
                                    </div>
                                    <input
                                        type="email"
                                        placeholder="Adresse e-mail"
                                        v-model="form.email"
                                        class="input input-bordered w-full max-w-xs"
                                        name="email"
                                        required
                                    />
                                    <BaseInputError
                                        :message="form.errors.email"
                                    />
                                </label>
                                <BasePrimaryButton
                                    v-show="hasChanged"
                                    type="submit"
                                    @click="submit()"
                                    class="w-full max-w-xs"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Enrgistrer
                                </BasePrimaryButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped>
.indicator-item {
    transform: translate(20%, -20%);
}
</style>
