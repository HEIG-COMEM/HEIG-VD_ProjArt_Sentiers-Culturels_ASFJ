<script setup>
import { defineProps, ref, watch } from "vue";

import MobileAppLayout from "@/Layouts/AppLayout.vue";
import BaseInputError from "@/Components/Base/BaseInputError.vue";
import BasePrimaryButton from "@/Components/Base/BasePrimaryButton.vue";
import BaseLink from "@/Components/Base/BaseLink.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { Eye, EyeSlash } from "@iconsans/vue/linear";

const form = useForm({
    firstname: "",
    lastname: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};

const showPassword = ref(false);
const showPasswordRep = ref(false);
</script>

<template>
    <MobileAppLayout>
        <template v-slot:main>
            <div
                class="flex flex-col justify-center h-screen items-center gap-6"
            >
                <form
                    @submit.prevent="submit"
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
                        <BaseInputError :message="form.errors.firstname" />
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
                        <BaseInputError :message="form.errors.lastname" />
                    </label>
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">Adresse e-mail</span>
                        </div>
                        <input
                            type="email"
                            placeholder="Adresse e-mail"
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
                                placeholder="Mot de passe"
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
                    </label>
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text"
                                >Confirmer le mot de passe</span
                            >
                        </div>
                        <div
                            class="input input-bordered flex items-center gap-2 pl-0"
                        >
                            <input
                                :type="showPasswordRep ? 'text' : 'password'"
                                class="grow border-none shadow-none"
                                placeholder="Mot de passe"
                                v-model="form.password_confirmation"
                                name="password_confirmation"
                                required
                            />
                            <component
                                :is="showPasswordRep ? EyeSlash : Eye"
                                @click="showPasswordRep = !showPasswordRep"
                            />
                        </div>
                        <BaseInputError :message="form.errors.password" />
                    </label>
                    <BasePrimaryButton
                        type="submit"
                        class="btn-wide"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Se connecter
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
