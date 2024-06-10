<script setup>
import { ref, watch } from "vue";

import MobileAppLayout from "@/Layouts/AppLayout.vue";
import BaseInputError from "@/Components/Base/BaseInputError.vue";
import BasePrimaryButton from "@/Components/Base/BasePrimaryButton.vue";
import BaseLink from "@/Components/Base/BaseLink.vue";
import { useForm, Head } from "@inertiajs/vue3";
import { Eye, EyeSlash, Cross, Tick1 } from "@iconsans/vue/linear";

const form = useForm({
    firstname: "",
    lastname: "",
    email: "",
    password: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password"),
    });
};

const showPassword = ref(false);
const hasEightCharacters = ref(false);
const hasUpperCase = ref(false);
const hasLowerCase = ref(false);
const hasNumber = ref(false);
const hasSpecialCharacter = ref(false);

watch(
    () => form.password,
    (value) => {
        console.log(value);
        hasEightCharacters.value = value.length >= 8;
        hasUpperCase.value = /[A-Z]/.test(value);
        hasLowerCase.value = /[a-z]/.test(value);
        hasNumber.value = /[0-9]/.test(value);
        hasSpecialCharacter.value =
            /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(value);
    },
);
</script>

<template>
    <Head title="Enregistrement" />
    <MobileAppLayout>
        <template v-slot:main>
            <div
                class="flex flex-col justify-center h-screen items-center gap-6 w-3/4"
            >
                <form
                    @submit.prevent="submit"
                    class="flex flex-col justify-center items-center gap-4 w-full"
                >
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">Prénom</span>
                        </div>
                        <input
                            type="text"
                            v-model="form.firstname"
                            class="input input-bordered w-full max-w-xs"
                            name="firstname"
                            required
                        />
                        <BaseInputError :message="form.errors.firstname" />
                    </label>
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">Nom</span>
                        </div>
                        <input
                            type="text"
                            v-model="form.lastname"
                            class="input input-bordered w-full max-w-xs"
                            name="lastname"
                            required
                        />
                        <BaseInputError :message="form.errors.lastname" />
                    </label>
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">Adresse e-mail</span>
                        </div>
                        <input
                            type="email"
                            v-model="form.email"
                            class="input input-bordered w-full max-w-xs"
                            name="email"
                            required
                        />
                        <BaseInputError :message="form.errors.email" />
                    </label>
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">Mot de passe</span>
                        </div>
                        <div
                            class="input input-bordered flex items-center gap-2 pl-0"
                        >
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                class="grow border-none shadow-none"
                                v-model="form.password"
                                name="password"
                                required
                            />
                            <component
                                :is="showPassword ? EyeSlash : Eye"
                                @click="showPassword = !showPassword"
                            />
                        </div>
                        <BaseInputError :message="form.errors.password" />
                        <div class="flex flex-col mt-2 text-xs">
                            <div
                                class="inline-flex items-center"
                                :class="{
                                    'text-success': hasEightCharacters,
                                    'text-error': !hasEightCharacters,
                                }"
                            >
                                <component
                                    :is="hasEightCharacters ? Tick1 : Cross"
                                    class="h-8 w-8"
                                />
                                Min. 8 caractères
                            </div>
                            <div
                                class="inline-flex items-center"
                                :class="{
                                    'text-success':
                                        hasUpperCase && hasLowerCase,
                                    'text-error':
                                        !hasUpperCase || !hasLowerCase,
                                }"
                            >
                                <component
                                    :is="
                                        hasUpperCase && hasLowerCase
                                            ? Tick1
                                            : Cross
                                    "
                                    class="h-8 w-8"
                                />
                                Majuscules et minuscules
                            </div>
                            <div
                                class="inline-flex items-center"
                                :class="{
                                    'text-success':
                                        hasNumber && hasSpecialCharacter,
                                    'text-error':
                                        !hasNumber || !hasSpecialCharacter,
                                }"
                            >
                                <component
                                    :is="
                                        hasNumber && hasSpecialCharacter
                                            ? Tick1
                                            : Cross
                                    "
                                    class="h-8 w-8"
                                />
                                Chiffres et caractères spéciaux
                            </div>
                        </div>
                    </label>
                    <BasePrimaryButton
                        type="submit"
                        class="mt-4 w-full max-w-xs"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Créer un compte
                    </BasePrimaryButton>
                </form>
                <div class="text-xs text-center">
                    <span>Déjà un compte ? </span>
                    <BaseLink href="/login">Se connecter</BaseLink>
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped>
input[name="password"]:focus,
input[name="password_confirmation"]:focus {
    /* remove ring and ring shadow */
    box-shadow: none;
}
</style>
