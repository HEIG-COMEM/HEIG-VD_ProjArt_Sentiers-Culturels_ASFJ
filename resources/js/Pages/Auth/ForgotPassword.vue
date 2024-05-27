<script setup>
import MobileAppLayout from "@/Layouts/AppLayout.vue";
import BaseInputError from "@/Components/Base/BaseInputError.vue";
import BasePrimaryButton from "@/Components/Base/BasePrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <MobileAppLayout>
        <template v-slot:main>
            <div
                class="flex flex-col justify-center h-screen items-center gap-6"
            >
                <div class="p-6 max-w-96">
                    Vous avez oublié votre mot de passe ? Pas de problème.
                </div>
                <div class="text-sm p-6 max-w-96">
                    Indiquez-nous votre adresse électronique et nous vous
                    enverrons un lien de réinitialisation de votre mot de passe.
                    qui vous permettra d'en choisir un nouveau.
                </div>
                <div
                    v-if="status"
                    class="mb-4 font-medium text-sm text-green-600"
                >
                    {{ status }}
                </div>
                <form
                    @submit.prevent="submit"
                    class="flex flex-col justify-center items-center gap-4"
                >
                    <label class="form-control w-full max-w-xs">
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
                    <BasePrimaryButton
                        type="submit"
                        class="btn btn-wide btn-primary"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Réinitialisation du mot de passe
                    </BasePrimaryButton>
                </form>
            </div>
        </template>
    </MobileAppLayout>
</template>

<style scoped></style>
