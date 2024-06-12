<script setup>
import {
    Notes,
    Logout,
    Trash,
    ArrowDown2,
    ArrowUp2,
} from "@iconsans/vue/linear";
</script>

<template>
    <div class="px-6 pt-6 flex flex-col gap-5" v-bind="$attrs">
        <div class="font-medium text-xl">Barre de navigation</div>
        <div class="flex flex-row gap-5 items-center">
            <div class="h-12 w-12">
                <Notes class="h-12 w-12" />
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-medium">Référencement</p>
                <p class="text-sm">
                    Accède à la liste complète des sentiers et des points
                    d'intérêt.
                </p>
            </div>
        </div>
        <div class="flex flex-row gap-5 items-center">
            <div class="h-12 w-12">
                <Logout class="h-12 w-12" />
            </div>
            <div class="flex flex-col">
                <p class="text-lg font-medium">Retour à l’application</p>
                <p class="text-sm">
                    Revenir à vue de l'application comme utilisateur
                </p>
            </div>
        </div>
        <div class="font-medium text-xl mt-6">Actions sentier</div>
        <div class="flex flex-row gap-5 items-center">
            <div class="flex flex-col">
                <p class="text-lg font-medium">Création d’un sentier</p>
                <p class="text-sm">
                    Crée un nouveau sentier en ajoutant les détails et les
                    points d'intérêt.
                </p>
            </div>
        </div>
        <div class="flex flex-row gap-5 items-center">
            <div class="flex flex-col">
                <p class="text-lg font-medium">Modification d’un sentier</p>
                <p class="text-sm">
                    Modifie les informations d'un sentier existant.
                </p>
            </div>
        </div>
        <div class="flex flex-row gap-5 items-center">
            <div class="h-12 w-12">
                <Trash class="h-12 w-12" />
            </div>
            <div class="flex flex-col">
                <p class="text-sm">Supprime un point d'intérêt d'un sentier.</p>
            </div>
        </div>
        <div class="flex flex-row gap-5 items-center">
            <div class="w-12 flex flex-col justify-center items-center">
                <ArrowUp2 class="h-6 w-12" />
                <ArrowDown2 class="h-6 w-12" />
            </div>
            <div class="flex flex-col">
                <p class="text-sm">
                    Réorganise les points d'intérêt d'un sentier comme bon te
                    semble.
                </p>
            </div>
        </div>
        <div class="font-medium text-xl mt-6">Actions points d’intérêts</div>
        <div class="flex flex-row gap-5 items-center">
            <div class="flex flex-col">
                <p class="text-lg font-medium">Création d’un point d’intérêt</p>
                <p class="text-sm">
                    Ajoute un nouveau point d'intérêt pour enrichir les sentiers
                    et offrir plus de découvertes culturelles et historiques.
                </p>
            </div>
        </div>
        <div class="flex flex-row gap-5 items-center">
            <div class="flex flex-col">
                <p class="text-lg font-medium">
                    Modification d’un point d’intérêt
                </p>
                <p class="text-sm">
                    Mets à jour les informations d'un point d'intérêt existant.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
