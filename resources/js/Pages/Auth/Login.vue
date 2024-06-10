<script setup>
import { defineProps, ref, watch } from "vue";

import MobileAppLayout from "@/Layouts/AppLayout.vue";
import BaseInputError from "@/Components/Base/BaseInputError.vue";
import BasePrimaryButton from "@/Components/Base/BasePrimaryButton.vue";
import BaseLink from "@/Components/Base/BaseLink.vue";
import { Link, useForm, Head } from "@inertiajs/vue3";
import { Eye, EyeSlash } from "@iconsans/vue/linear";

defineProps({
    canResetPassword: {
        type: Boolean,
        default: true,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    console.log(form);
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};

const showPassword = ref(false);
</script>

<template>
    <Head title="Connection" />
    <MobileAppLayout>
        <template v-slot:main>
            <div
                class="flex flex-col justify-center h-screen items-center gap-6 w-3/4"
            >
                <div
                    v-if="status"
                    class="mb-4 font-medium text-sm text-green-600"
                >
                    {{ status }}
                </div>
                <form
                    @submit.prevent="submit"
                    class="flex flex-col justify-center items-center gap-4 w-full"
                >
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

                        <div class="label">
                            <span class="label-text-alt"></span>
                            <BaseLink
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="link label-text-alt"
                            >
                                Mot de passe oublié ?
                            </BaseLink>
                        </div>
                    </label>
                    <BasePrimaryButton
                        type="submit"
                        class="w-full max-w-xs"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Se connecter
                    </BasePrimaryButton>
                </form>
                <div class="text-xs text-center">
                    <span>Pas encore inscrit ? </span>
                    <BaseLink href="/register">Créer un compte</BaseLink>
                </div>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped>
input[name="password"]:focus {
    /* remove ring and ring shadow */
    box-shadow: none;
}
</style>
